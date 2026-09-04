<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\PhoneCall;
use App\Models\PhoneLead;
use App\Models\PhoneRegistrationLink;
use App\Models\TrialAcquisitionCall;
use App\Models\TrialWeek;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AcquisitionAnalyticsService
{
    public function dashboard(string $period = 'all'): array
    {
        $period = in_array($period, ['all', 'today', '7', '30'], true) ? $period : 'all';
        $phoneLeads = $this->phoneLeadQuery($period)
            ->with(['assignments.consultant:id,name', 'registrationLinks.consultant:id,name', 'registrationLinks.user.trialWeek', 'registrationLinks.user.student.examSchedules'])
            ->withCount('calls')
            ->get();
        $trials = $this->trialQuery($period)
            ->with(['user:id,name,mobile', 'acquisitionSupporter:id,name', 'trialAcquisitionCalls'])
            ->get();

        $phoneCalls = $this->phoneCallQuery($period)->get();
        $trialCalls = $this->trialCallQuery($period)->get();
        $links = PhoneRegistrationLink::query()
            ->with(['consultant:id,name', 'user.trialWeek', 'user.student.examSchedules'])
            ->whereNotNull('registered_user_id')
            ->get()
            ->unique('registered_user_id')
            ->values();
        $completedLinks = $links->filter(fn (PhoneRegistrationLink $link) => $link->hasCompletedProgramRegistration())->values();
        $phoneUserIds = $links->pluck('registered_user_id')->filter()->flip();

        return [
            'period' => $period,
            'phone' => $this->phoneStats($phoneLeads, $phoneCalls, $completedLinks),
            'trial' => $this->trialStats($trials, $trialCalls, $phoneUserIds),
            'phoneConsultants' => $this->phoneConsultantStats($phoneLeads, $phoneCalls, $completedLinks),
            'trialConsultants' => $this->trialConsultantStats($trials, $trialCalls, $phoneUserIds),
            'recentRegistrations' => $this->recentRegistrations($trials, $links),
            'dailyTrend' => $this->dailyTrend(),
        ];
    }

    public function completedPhoneLeadIds(): Collection
    {
        return PhoneRegistrationLink::query()
            ->with(['user.trialWeek', 'user.student.examSchedules'])
            ->whereNotNull('registered_user_id')
            ->get()
            ->filter(fn (PhoneRegistrationLink $link) => $link->hasCompletedProgramRegistration())
            ->pluck('phone_lead_id')
            ->filter()
            ->unique()
            ->values();
    }

    public function registrationSourceMap(Collection $userIds): Collection
    {
        return PhoneRegistrationLink::query()
            ->with('consultant:id,name')
            ->whereIn('registered_user_id', $userIds->filter()->unique())
            ->whereNotNull('used_at')
            ->latest('used_at')
            ->get()
            ->unique('registered_user_id')
            ->keyBy('registered_user_id');
    }

    public function applyPeriod(Builder $query, string $period, string $column = 'created_at'): Builder
    {
        return match ($period) {
            'today' => $query->whereDate($column, today()),
            '7' => $query->where($column, '>=', now()->subDays(6)->startOfDay()),
            '30' => $query->where($column, '>=', now()->subDays(29)->startOfDay()),
            default => $query,
        };
    }

    protected function phoneLeadQuery(string $period): Builder
    {
        return $this->applyPeriod(PhoneLead::query(), $period);
    }

    protected function trialQuery(string $period): Builder
    {
        return $this->applyPeriod(TrialWeek::query(), $period);
    }

    protected function phoneCallQuery(string $period): Builder
    {
        return $this->applyPeriod(PhoneCall::query(), $period, 'called_at');
    }

    protected function trialCallQuery(string $period): Builder
    {
        return $this->applyPeriod(TrialAcquisitionCall::query(), $period, 'called_at');
    }

    protected function phoneStats(Collection $leads, Collection $calls, Collection $completedLinks): array
    {
        $leadIds = $leads->pluck('id')->flip();
        $calls = $calls->filter(fn (PhoneCall $call) => $leadIds->has($call->phone_lead_id));
        $completedLinks = $completedLinks->filter(fn (PhoneRegistrationLink $link) => $leadIds->has($link->phone_lead_id));
        $answered = $calls->where('connected', true)->count();
        $registrations = $completedLinks->unique('registered_user_id');
        $trialRegistrations = $registrations->filter(fn (PhoneRegistrationLink $link) => $link->completedRegistrationProgramType() === PhoneRegistrationLink::PLAN_TRIAL)->count();
        $examRegistrations = $registrations->count() - $trialRegistrations;

        return [
            'consultants' => Admin::role('مشاور جذب تلفنی')->count(),
            'total' => $leads->count(),
            'active' => $leads->where('status', PhoneLead::STATUS_ACTIVE)->count(),
            'noCall' => $leads->where('calls_count', 0)->count(),
            'temporary' => $leads->where('disinterest_status', PhoneCall::DISINTEREST_TEMPORARY)->count(),
            'definitive' => $leads->where('disinterest_status', PhoneCall::DISINTEREST_DEFINITIVE)->count(),
            'registrations' => $registrations->count(),
            'trialRegistrations' => $trialRegistrations,
            'examRegistrations' => $examRegistrations,
            'calls' => $calls->count(),
            'answered' => $answered,
            'unanswered' => $calls->count() - $answered,
            'answerRate' => $this->percent($answered, $calls->count()),
            'conversionRate' => $this->percent($registrations->count(), $leads->count()),
            'talkTime' => $this->duration((int) $calls->sum('talk_duration_seconds')),
        ];
    }

    protected function trialStats(Collection $trials, Collection $calls, Collection $phoneUserIds): array
    {
        $trialIds = $trials->pluck('id')->flip();
        $calls = $calls->filter(fn (TrialAcquisitionCall $call) => $trialIds->has($call->trial_week_id));
        $answered = $calls->where('answered', true)->count();
        $registered = $trials->filter(fn (TrialWeek $trial) => $this->trialIsRegistered($trial));

        return [
            'consultants' => Admin::role('site acquisition')->count(),
            'total' => $trials->count(),
            'active' => $trials->whereNull('acq_disinterest_status')->count(),
            'unassigned' => $trials->whereNull('acquisition_supporter_id')->count(),
            'temporary' => $trials->where('acq_disinterest_status', TrialWeek::ACQ_DISINTEREST_TEMPORARY)->count(),
            'definitive' => $trials->where('acq_disinterest_status', TrialWeek::ACQ_DISINTEREST_DEFINITIVE)->count(),
            'registrations' => $registered->count(),
            'pending' => $trials->whereNull('acq_disinterest_status')->count() - $registered->whereNull('acq_disinterest_status')->count(),
            'siteSource' => $trials->filter(fn (TrialWeek $trial) => ! $phoneUserIds->has($trial->user_id))->count(),
            'phoneSource' => $trials->filter(fn (TrialWeek $trial) => $phoneUserIds->has($trial->user_id))->count(),
            'calls' => $calls->count(),
            'answered' => $answered,
            'answerRate' => $this->percent($answered, $calls->count()),
            'conversionRate' => $this->percent($registered->count(), $trials->count()),
            'talkTime' => $this->duration((int) $calls->sum('talk_duration_seconds')),
        ];
    }

    protected function phoneConsultantStats(Collection $leads, Collection $calls, Collection $completedLinks): Collection
    {
        $consultants = Admin::role('مشاور جذب تلفنی')->orderBy('name')->get(['id', 'name']);

        return $consultants->map(function (Admin $admin) use ($leads, $calls, $completedLinks) {
            $assignedIds = $leads->filter(fn (PhoneLead $lead) => $lead->assignments->contains('admin_id', $admin->id))->pluck('id')->unique();
            $adminCalls = $calls->where('admin_id', $admin->id);
            $registrations = $completedLinks->where('admin_id', $admin->id)->unique('registered_user_id')->count();

            return [
                'id' => $admin->id,
                'name' => $admin->name,
                'assigned' => $assignedIds->count(),
                'calls' => $adminCalls->count(),
                'answered' => $adminCalls->where('connected', true)->count(),
                'temporary' => $leads->whereIn('id', $assignedIds)->where('disinterest_status', PhoneCall::DISINTEREST_TEMPORARY)->count(),
                'definitive' => $leads->whereIn('id', $assignedIds)->where('disinterest_status', PhoneCall::DISINTEREST_DEFINITIVE)->count(),
                'registrations' => $registrations,
                'conversion' => $this->percent($registrations, $assignedIds->count()),
                'talkTime' => $this->duration((int) $adminCalls->sum('talk_duration_seconds')),
            ];
        })->sortByDesc('registrations')->values();
    }

    protected function trialConsultantStats(Collection $trials, Collection $calls, Collection $phoneUserIds): Collection
    {
        $consultants = Admin::role('site acquisition')->orderBy('name')->get(['id', 'name']);

        return $consultants->map(function (Admin $admin) use ($trials, $calls, $phoneUserIds) {
            $mine = $trials->where('acquisition_supporter_id', $admin->id);
            $trialIds = $mine->pluck('id');
            $adminCalls = $calls->filter(fn (TrialAcquisitionCall $call) => $call->admin_id === $admin->id || $trialIds->contains($call->trial_week_id));
            $registrations = $mine->filter(fn (TrialWeek $trial) => $this->trialIsRegistered($trial))->count();

            return [
                'id' => $admin->id,
                'name' => $admin->name,
                'assigned' => $mine->count(),
                'calls' => $adminCalls->count(),
                'answered' => $adminCalls->where('answered', true)->count(),
                'temporary' => $mine->where('acq_disinterest_status', TrialWeek::ACQ_DISINTEREST_TEMPORARY)->count(),
                'definitive' => $mine->where('acq_disinterest_status', TrialWeek::ACQ_DISINTEREST_DEFINITIVE)->count(),
                'registrations' => $registrations,
                'siteSource' => $mine->filter(fn (TrialWeek $trial) => ! $phoneUserIds->has($trial->user_id))->count(),
                'phoneSource' => $mine->filter(fn (TrialWeek $trial) => $phoneUserIds->has($trial->user_id))->count(),
                'conversion' => $this->percent($registrations, $mine->count()),
                'talkTime' => $this->duration((int) $adminCalls->sum('talk_duration_seconds')),
            ];
        })->sortByDesc('registrations')->values();
    }

    protected function recentRegistrations(Collection $trials, Collection $links): Collection
    {
        $linkMap = $links->keyBy('registered_user_id');

        return $trials->filter(fn (TrialWeek $trial) => $this->trialIsRegistered($trial))
            ->sortByDesc(fn (TrialWeek $trial) => $trial->program_built_at ?? $trial->updated_at)
            ->take(12)
            ->map(function (TrialWeek $trial) use ($linkMap) {
                $link = $linkMap->get($trial->user_id);

                return [
                    'trialId' => $trial->id,
                    'name' => $trial->user?->name ?? '—',
                    'mobile' => $trial->user?->mobile ?? '—',
                    'trialConsultant' => $trial->acquisitionSupporter?->name ?? 'بدون تخصیص',
                    'source' => $link ? 'مشاور جذب تلفنی' : 'ورود مستقیم از سایت',
                    'sourceConsultant' => $link?->consultant?->name,
                    'date' => $trial->program_built_at ?? $trial->updated_at,
                ];
            })->values();
    }

    protected function dailyTrend(): Collection
    {
        $start = today()->subDays(13);
        $phone = PhoneCall::query()->where('called_at', '>=', $start)->get(['called_at']);
        $trial = TrialAcquisitionCall::query()->where('called_at', '>=', $start)->get(['called_at']);
        $registrations = TrialWeek::query()->whereNotNull('program_built_at')->where('program_built_at', '>=', $start)->get(['program_built_at']);

        return collect(range(0, 13))->map(function (int $offset) use ($start, $phone, $trial, $registrations) {
            $date = $start->copy()->addDays($offset);

            return [
                'date' => $date,
                'phoneCalls' => $phone->filter(fn (PhoneCall $call) => $call->called_at?->isSameDay($date))->count(),
                'trialCalls' => $trial->filter(fn (TrialAcquisitionCall $call) => $call->called_at?->isSameDay($date))->count(),
                'registrations' => $registrations->filter(fn (TrialWeek $item) => $item->program_built_at?->isSameDay($date))->count(),
            ];
        });
    }

    protected function trialIsRegistered(TrialWeek $trial): bool
    {
        return $trial->program_built_at !== null || $trial->status === TrialWeek::STATUS_PROGRAM_BUILT;
    }

    protected function percent(int $value, int $total): float
    {
        return $total > 0 ? round(($value / $total) * 100, 1) : 0;
    }

    protected function duration(int $seconds): string
    {
        if ($seconds <= 0) {
            return '۰ دقیقه';
        }

        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);

        return $hours > 0 ? "{$hours} ساعت و {$minutes} دقیقه" : max(1, $minutes).' دقیقه';
    }
}
