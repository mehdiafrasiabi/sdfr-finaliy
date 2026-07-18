<?php

namespace App\Livewire\Admin\TrialAcquisition;

use App\Models\AdminNotification;
use App\Models\TrialAcquisitionCall;
use App\Models\TrialWeek;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * داشبورد «مشاور جذب یک هفته آزمایشی».
 */
class Dashboard extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public ?int $pendingCallTrialId = null;
    public string $pendingCallStage = '';
    public string $pendingCallSubject = '';
    public bool $showCallConfirmModal = false;

    public function promptCall(int $trialId, string $stage): void
    {
        if (! array_key_exists($stage, TrialAcquisitionCall::STAGE_DUE_DAY)) {
            $this->dispatch('warning', 'مرحله تماس معتبر نیست.');
            return;
        }

        $belongsToSupporter = TrialWeek::where('acquisition_supporter_id', Auth::guard('admin')->id())
            ->whereKey($trialId)
            ->exists();

        if (! $belongsToSupporter) {
            $this->dispatch('warning', 'دانش‌آموز یافت نشد یا به شما تخصیص ندارد.');
            return;
        }

        $this->pendingCallTrialId = $trialId;
        $this->pendingCallStage = $stage;
        $this->pendingCallSubject = ''; // Subject is no longer used
        $this->showCallConfirmModal = true;
    }

    public function cancelCallPrompt(): void
    {
        $this->reset(['pendingCallTrialId', 'pendingCallStage', 'pendingCallSubject', 'showCallConfirmModal']);
    }

    public function continueCallPrompt()
    {
        if (! $this->pendingCallTrialId || ! $this->pendingCallStage) {
            $this->cancelCallPrompt();
            return null;
        }

        return redirect()->route('admin.trial-acquisition.index', [
            'callTrialId' => $this->pendingCallTrialId,
            'callStage' => $this->pendingCallStage,
        ]);
    }

    public function render()
    {
        $adminId = Auth::guard('admin')->id();

        $base = TrialWeek::where('acquisition_supporter_id', $adminId);

        $total = (clone $base)->count();
        $calledStudents = (clone $base)
            ->whereHas('trialAcquisitionCalls', fn ($q) => $q
                ->where('answered', true)
                ->where('stage', '!=', TrialAcquisitionCall::STAGE_EXTRA)
            )
            ->count();
        $notCalled = $total - $calledStudents;
        $confirmed = (clone $base)->where('acq_confirmed', true)->count();

        $waitingForCall = (clone $base)
            ->with(['user.personalInformation', 'student.examSchedules', 'trialAcquisitionCalls'])
            ->latest()
            ->get()
            ->flatMap(function (TrialWeek $trial) {
                $daysSinceRegistration = $trial->daysSinceAcquisitionStart();

                return collect(TrialAcquisitionCall::STAGE_DUE_DAY)
                    ->filter(fn (int $dueDay) => $daysSinceRegistration >= $dueDay)
                    ->map(function (int $dueDay, string $stage) use ($trial, $daysSinceRegistration) {
                        if ($trial->trialAcquisitionCalls
                            ->where('stage', $stage)
                            ->where('answered', true)
                            ->isNotEmpty()) {
                            return null;
                        }

                        return [
                            'trial' => $trial,
                            'stage' => $stage,
                            'subject' => '',
                            'stage_label' => TrialAcquisitionCall::STAGE_LABELS[$stage] ?? $stage,
                            'days_overdue' => max(0, $daysSinceRegistration - $dueDay),
                            'is_due_today' => $daysSinceRegistration === $dueDay,
                        ];
                    })
                    ->filter(); // Remove nulls
            })
            ->sortByDesc('days_overdue')
            ->values()
            ->take(12);

        // یادآورهای سررسیده
        $dueReminders = (clone $base)
            ->whereNotNull('acq_reminder_at')
            ->with('user:id,name,mobile')
            ->orderBy('acq_reminder_at')
            ->limit(50)
            ->get();

        $notifications = AdminNotification::where('admin_id', $adminId)
            ->latest()
            ->paginate(20);

        return view('livewire.admin.trial-acquisition.dashboard', [
            'total'          => $total,
            'calledStudents' => $calledStudents,
            'notCalled'      => $notCalled,
            'confirmed'      => $confirmed,
            'dueReminders'   => $dueReminders,
            'waitingForCall' => $waitingForCall,
            'notifications'  => $notifications,
            'now'            => now(),
        ])->layout('layouts.admin.app');
    }
}
