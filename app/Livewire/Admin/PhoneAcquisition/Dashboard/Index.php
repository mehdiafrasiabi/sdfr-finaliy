<?php

namespace App\Livewire\Admin\PhoneAcquisition\Dashboard;

use App\Livewire\Admin\PhoneAcquisition\Concerns\LogsPhoneCalls;
use App\Models\Payment;
use App\Models\PhoneCall;
use App\Models\PhoneLead;
use App\Models\PhoneLeadAssignment;
use App\Models\PhoneRegistrationLink;
use App\Models\RegistrationGoal;
use App\Models\TrialWeek;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * داشبورد مشاور جذب تلفنی — شماره‌هایی که موعد تماس مجددشان رسیده است
 * (تماس‌های ناموفقِ موکول‌شده و پیگیری‌های موفقِ سررسیده). امکان ثبت تماس در همین صفحه.
 */
class Index extends Component
{
    use WithPagination, LogsPhoneCalls;

    public function render()
    {
        $adminId = Auth::guard('admin')->id();

        $activeLeadsBase = fn () => PhoneLead::query()
            ->whereHas('assignments', fn ($q) =>
                $q->where('admin_id', $adminId)
                  ->where('status', PhoneLeadAssignment::STATUS_ACTIVE)
            );

        $dueBase = fn () => $activeLeadsBase()
            ->dueForCall()
            ->orderBy('next_call_at');

        $leads = $dueBase()
            ->with(['state:id,name', 'city:id,name'])
            ->paginate(15);

        $activeLead = $this->activeLeadId ? PhoneLead::find($this->activeLeadId) : null;
        $dashboardStats = $this->buildDashboardStats($adminId);
        $pendingRegistrationCount = PhoneLead::query()
            ->whereHas('assignments', fn ($q) =>
                $q->where('admin_id', $adminId)
                  ->where('status', PhoneLeadAssignment::STATUS_ACTIVE)
            )
            ->where('status', PhoneLead::STATUS_ACTIVE)
            ->where('last_outcome', PhoneCall::RESULT_REGISTRATION_FOLLOW_UP)
            ->whereNotNull('next_call_at')
            ->where('next_call_at', '<=', now())
            ->count();

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
            'leads'                  => $leads,
            'dueCount'               => $dueBase()->count(),
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
            'now'                    => now(),
        ])->layout('layouts.admin.app');
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
            ->get(['registered_user_id']);

        $callLogs = PhoneCall::query()
            ->where('admin_id', $adminId)
            ->get(['connected', 'called_at', 'talk_duration_seconds']);

        return [
            'notCalledCount'     => $activeAssignedLeads->where('attempts_count', 0)->count(),
            'needsFollowUpCount' => $activeAssignedLeads->whereNotNull('next_call_at')->count(),
            'myStudentsCount'    => $consultantLinks->pluck('registered_user_id')->filter()->unique()->count(),
            'colorCounts'        => $this->buildColorCounts($allAssignedLeads),
            'registrationStats'  => $this->buildRegistrationStats($consultantLinks),
            'teamRegistrationStats' => $this->buildRegistrationStats(
                PhoneRegistrationLink::query()
                    ->whereNotNull('registered_user_id')
                    ->get(['registered_user_id'])
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
                'purchase' => 0,
            ];
        }

        $trialEvents = TrialWeek::query()
            ->whereIn('user_id', $userIds)
            ->get(['user_id', 'created_at'])
            ->groupBy('user_id')
            ->map(fn (Collection $items) => $items->min(fn (TrialWeek $trialWeek) => optional($trialWeek->created_at)->timestamp));

        $purchaseEvents = Payment::query()
            ->whereIn('user_id', $userIds)
            ->where('status', 'completed')
            ->where(function ($q) {
                $q->whereIn('purpose', [Payment::PURPOSE_COURSE_FULL, Payment::PURPOSE_INSTALLMENT_INITIAL])
                    ->orWhereNull('purpose');
            })
            ->get(['user_id', 'created_at', 'updated_at'])
            ->groupBy('user_id')
            ->map(fn (Collection $items) => $items->min(fn (Payment $payment) => optional($payment->updated_at ?? $payment->created_at)->timestamp));

        $trialCount = 0;
        $purchaseCount = 0;

        foreach ($userIds as $userId) {
            $trialAt = $trialEvents->get($userId);
            $purchaseAt = $purchaseEvents->get($userId);

            if (! $trialAt && ! $purchaseAt) {
                continue;
            }

            if ($trialAt && (! $purchaseAt || $trialAt <= $purchaseAt)) {
                $trialCount++;
                continue;
            }

            $purchaseCount++;
        }

        return [
            'total'    => $trialCount + $purchaseCount,
            'trial'    => $trialCount,
            'purchase' => $purchaseCount,
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
