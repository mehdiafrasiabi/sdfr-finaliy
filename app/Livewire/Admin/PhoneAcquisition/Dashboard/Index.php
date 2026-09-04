<?php

namespace App\Livewire\Admin\PhoneAcquisition\Dashboard;

use App\Livewire\Admin\PhoneAcquisition\Concerns\LogsPhoneCalls;
use App\Models\PhoneCall;
use App\Models\PhoneLead;
use App\Models\PhoneLeadAssignment;
use App\Models\PhoneRegistrationLink;
use App\Models\RegistrationGoal;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * داشبورد مشاور جذب تلفنی — شماره‌هایی که موعد تماس مجددشان رسیده است
 * (تماس‌های ناموفقِ موکول‌شده و پیگیری‌های موفقِ سررسیده). امکان ثبت تماس در همین صفحه.
 */
class Index extends Component
{
    use LogsPhoneCalls;

    public function render()
    {
        $adminId = Auth::guard('admin')->id();

        $activeLead = $this->activeLeadId ? PhoneLead::find($this->activeLeadId) : null;
        $dashboardStats = $this->buildDashboardStats($adminId);
        $pendingRegistrationCount = $this->registrationQueueLinksQuery($adminId)->count();
        $phoneFollowUpCount = $this->phoneFollowUpLeadsQuery($adminId)->where('next_call_at', '<=', now())->count();
        $temporaryDisinterestCount = $this->disinterestLeadsQuery($adminId, PhoneCall::DISINTEREST_TEMPORARY)->count();
        $definitiveDisinterestCount = $this->disinterestLeadsQuery($adminId, PhoneCall::DISINTEREST_DEFINITIVE)->count();

        // اهداف فعال (تیمی + شخصیِ این مشاور) که مهلتشان نگذشته است + پیشرفت.
        $goals = RegistrationGoal::query()
            ->where(fn ($q) => $q->whereNull('admin_id')->orWhere('admin_id', $adminId))
            ->whereDate('goal_date', '>=', now()->toDateString())
            ->orderBy('goal_date')
            ->get()
            ->map(function (RegistrationGoal $goal) use ($dashboardStats) {
                // هدف تیمی → کل تیم؛ هدف شخصی → فقط همین مشاور.
                $goal->achieved = $goal->admin_id
                    ? $dashboardStats['registrationStats']['total']
                    : $dashboardStats['teamRegistrationStats']['total'];
                return $goal;
            });

        return view('livewire.admin.phone-acquisition.dashboard.index', [
            'dueCount'               => $phoneFollowUpCount,
            'goals'                  => $goals,
            'activeLead'             => $activeLead,
            'notCalledCount'         => $dashboardStats['notCalledCount'],
            'needsFollowUpCount'     => $dashboardStats['needsFollowUpCount'],
            'myStudentsCount'        => $dashboardStats['myStudentsCount'],
            'colorCounts'            => $dashboardStats['colorCounts'],
            'registrationStats'      => $dashboardStats['registrationStats'],
            'myCallsCount'           => $dashboardStats['myCallsCount'],
            'totalTalkTimeLabel'     => $dashboardStats['totalTalkTimeLabel'],
            'bestHour'               => $dashboardStats['bestHour'],
            'bestHourCandidates'     => $dashboardStats['bestHourCandidates'],
            'pendingRegistrationCount' => $pendingRegistrationCount,
            'temporaryDisinterestCount' => $temporaryDisinterestCount,
            'definitiveDisinterestCount' => $definitiveDisinterestCount,
            'now'                    => now(),
        ])->layout('layouts.admin.app');
    }

    protected function phoneFollowUpLeadsQuery(int $adminId)
    {
        return PhoneLead::query()
            ->where('status', PhoneLead::STATUS_ACTIVE)
            ->where('last_outcome', PhoneCall::RESULT_FOLLOW_UP)
            ->whereDoesntHave('registrationLinks')
            ->whereHas('assignments', fn ($assignmentQuery) => $assignmentQuery
                ->where('admin_id', $adminId)
                ->where('status', PhoneLeadAssignment::STATUS_ACTIVE)
            );
    }

    protected function registrationQueueLinksQuery(int $adminId)
    {
        return PhoneRegistrationLink::query()
            ->where('admin_id', $adminId)
            ->whereNull('registered_user_id')
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('phone_registration_links as newer_links')
                    ->whereColumn('newer_links.phone_lead_id', 'phone_registration_links.phone_lead_id')
                    ->whereNull('newer_links.registered_user_id')
                    ->whereColumn('newer_links.id', '>', 'phone_registration_links.id');
            })
            ->whereHas('lead', fn ($leadQuery) => $leadQuery
                ->where('status', PhoneLead::STATUS_ACTIVE)
                ->whereIn('last_outcome', [
                    PhoneCall::RESULT_REGISTERED,
                    PhoneCall::RESULT_REGISTRATION_FOLLOW_UP,
                ])
                ->whereHas('assignments', fn ($assignmentQuery) => $assignmentQuery
                    ->where('admin_id', $adminId)
                    ->where('status', PhoneLeadAssignment::STATUS_ACTIVE)
                )
            );
    }

    protected function disinterestLeadsQuery(int $adminId, string $status)
    {
        return PhoneLead::query()
            ->where('last_outcome', PhoneCall::RESULT_NO_INTEREST)
            ->where('disinterest_status', $status)
            ->whereHas('assignments', fn ($assignmentQuery) => $assignmentQuery->where('admin_id', $adminId));
    }

    protected function buildDashboardStats(int $adminId): array
    {
        $allAssignedLeads = PhoneLead::query()
            ->whereHas('assignments', fn ($q) => $q->where('admin_id', $adminId))
            ->get(['id', 'status', 'attempts_count']);

        $activeAssignedLeads = PhoneLead::query()
            ->whereHas('assignments', fn ($q) =>
                $q->where('admin_id', $adminId)
                  ->where('status', PhoneLeadAssignment::STATUS_ACTIVE)
            )
            ->get(['id', 'status', 'attempts_count', 'next_call_at']);

        $consultantLinks = PhoneRegistrationLink::query()
            ->where('admin_id', $adminId)
            ->whereNotNull('registered_user_id')
            ->with(['user.trialWeek', 'user.student.examSchedules', 'user.examSchedules'])
            ->latest('used_at')
            ->get();

        $callLogs = PhoneCall::query()
            ->where('admin_id', $adminId)
            ->get(['connected', 'called_at', 'talk_duration_seconds']);

        return [
            'notCalledCount'     => $activeAssignedLeads->where('attempts_count', 0)->count(),
            'needsFollowUpCount' => $activeAssignedLeads->whereNotNull('next_call_at')->count(),
            'myStudentsCount'    => $this->buildRegistrationStats($consultantLinks)['total'],
            'colorCounts'        => $this->buildColorCounts($allAssignedLeads),
            'registrationStats'  => $this->buildRegistrationStats($consultantLinks),
            'teamRegistrationStats' => $this->buildRegistrationStats(
                PhoneRegistrationLink::query()
                    ->whereNotNull('registered_user_id')
                    ->with(['user.trialWeek', 'user.student.examSchedules', 'user.examSchedules'])
                    ->latest('used_at')
                    ->get()
            ),
            'myCallsCount'       => $callLogs->count(),
            'totalTalkTimeLabel' => $this->formatTalkDuration((int) $callLogs->sum('talk_duration_seconds')),
            'bestHour'           => $this->findBestHour($callLogs),
            'bestHourCandidates' => $this->topHourCandidates($callLogs),
        ];
    }

    protected function buildColorCounts(Collection $leads): array
    {
        $defaults = [
            'primary'   => 0,
            'success'   => 0,
            'warning'   => 0,
            'danger'    => 0,
            'secondary' => 0,
        ];

        $counts = $leads
            ->groupBy(fn (PhoneLead $lead) => $lead->color)
            ->map(fn (Collection $items) => $items->count())
            ->all();

        return array_replace($defaults, $counts);
    }

    protected function buildRegistrationStats(Collection $links): array
    {
        $userIds = $links->pluck('registered_user_id')->filter()->unique()->values();

        if ($userIds->isEmpty()) {
            return [
                'total'    => 0,
                'trial'    => 0,
                'exam'     => 0,
            ];
        }

        $trialCount = 0;
        $examCount = 0;

        foreach ($links->unique('registered_user_id') as $link) {
            $programType = $link->completedRegistrationProgramType();
            if (! $programType) {
                continue;
            }

            if ($programType === PhoneRegistrationLink::PLAN_EXAM) {
                $examCount++;
            } else {
                $trialCount++;
            }
        }

        return [
            'total'    => $trialCount + $examCount,
            'trial'    => $trialCount,
            'exam'     => $examCount,
        ];
    }

    protected function findBestHour(Collection $callLogs): ?array
    {
        return $this->topHourCandidates($callLogs)[0] ?? null;
    }

    protected function topHourCandidates(Collection $callLogs): array
    {
        if ($callLogs->isEmpty()) {
            return [];
        }

        $stats = $callLogs
            ->filter(fn (PhoneCall $call) => $call->called_at !== null)
            ->groupBy(fn (PhoneCall $call) => $call->called_at->format('H'))
            ->map(function (Collection $items, string $hour) {
                $total = $items->count();
                $answered = $items->where('connected', true)->count();

                return [
                    'hour'        => (int) $hour,
                    'label'       => sprintf('%02d:00 تا %02d:00', (int) $hour, ((int) $hour + 1) % 24),
                    'total'       => $total,
                    'answered'    => $answered,
                    'answer_rate' => $total > 0 ? (int) round(($answered / $total) * 100) : 0,
                ];
            })
            ->values();

        $eligible = $stats->where('total', '>=', 3)->values();
        if ($eligible->isEmpty()) {
            $eligible = $stats;
        }

        $sorted = $eligible->all();

        usort($sorted, function (array $a, array $b) {
            return [$b['answer_rate'], $b['answered'], $b['total'], $a['hour']]
                <=> [$a['answer_rate'], $a['answered'], $a['total'], $b['hour']];
        });

        return array_slice($sorted, 0, 3);
    }

    protected function formatTalkDuration(int $seconds): string
    {
        if ($seconds <= 0) {
            return '0 دقیقه';
        }

        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $remainingSeconds = $seconds % 60;

        $parts = [];

        if ($hours > 0) {
            $parts[] = $hours . ' ساعت';
        }

        if ($minutes > 0) {
            $parts[] = $minutes . ' دقیقه';
        }

        if ($remainingSeconds > 0 && $hours === 0) {
            $parts[] = $remainingSeconds . ' ثانیه';
        }

        return implode(' و ', $parts);
    }
}
