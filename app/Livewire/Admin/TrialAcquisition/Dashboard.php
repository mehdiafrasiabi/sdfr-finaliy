<?php

namespace App\Livewire\Admin\TrialAcquisition;

use App\Models\TrialAcquisitionCall;
use App\Models\TrialWeek;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * داشبورد «مشاور جذب یک هفته آزمایشی».
 */
class Dashboard extends Component
{
    public function render()
    {
        $adminId = Auth::guard('admin')->id();

        $base = TrialWeek::where('acquisition_supporter_id', $adminId);

        $total = (clone $base)->count();
        $calledStudents = (clone $base)
            ->whereHas('trialAcquisitionCalls', fn ($q) => $q->where('answered', true))
            ->count();
        $notCalled = $total - $calledStudents;
        $confirmed = (clone $base)->where('acq_confirmed', true)->count();

        // پیشرفت هر مرحله (دانش‌آموزانی که تماس موفق آن مرحله ثبت شده)
        $stageProgress = [];
        foreach (['day1', 'day3', 'day7'] as $stage) {
            $stageProgress[$stage] = (clone $base)
                ->whereHas('trialAcquisitionCalls', fn ($q) => $q->where('stage', $stage)->where('answered', true))
                ->count();
        }

        // میانگین احتمال ثبت‌نام
        $avgProbability = (int) round((clone $base)->whereNotNull('acq_probability')->avg('acq_probability') ?? 0);

        // یادآورهای سررسیده
        $dueReminders = (clone $base)
            ->whereNotNull('acq_reminder_at')
            ->with('user:id,name,mobile')
            ->orderBy('acq_reminder_at')
            ->limit(50)
            ->get();

        // تماس‌های اضطراری اخیر
        $emergencyCalls = TrialAcquisitionCall::where('admin_id', $adminId)
            ->where('stage', TrialAcquisitionCall::STAGE_EMERGENCY)
            ->with('trialWeek.user:id,name,mobile')
            ->latest('called_at')
            ->limit(50)
            ->get();

        return view('livewire.admin.trial-acquisition.dashboard', [
            'total'          => $total,
            'calledStudents' => $calledStudents,
            'notCalled'      => $notCalled,
            'confirmed'      => $confirmed,
            'stageProgress'  => $stageProgress,
            'avgProbability' => $avgProbability,
            'dueReminders'   => $dueReminders,
            'emergencyCalls' => $emergencyCalls,
            'now'            => now(),
        ])->layout('layouts.admin.app');
    }
}
