<?php

namespace App\Livewire\Admin\EducationalManager\PhoneAcquisition;

use App\Models\PhoneCall;
use App\Models\PhoneLead;
use App\Models\PhoneLeadAssignment;
use App\Models\RegistrationGoal;
use Livewire\Component;

/**
 * داشبورد مدیر آموزشی برای جذب تلفنی.
 */
class Dashboard extends Component
{
    public function render()
    {
        $totalLeads = PhoneLead::count();

        // شمارش نتایج تماس‌های موفق و ناموفق
        $resultCounts = PhoneCall::where('connected', true)
            ->selectRaw('result, COUNT(*) as c')
            ->groupBy('result')
            ->pluck('c', 'result');

        $failCounts = PhoneCall::where('connected', false)
            ->selectRaw('fail_reason, COUNT(*) as c')
            ->groupBy('fail_reason')
            ->pluck('c', 'fail_reason');

        $successful = [
            'registered'  => (int) ($resultCounts[PhoneCall::RESULT_REGISTERED] ?? 0),
            'follow_up'   => (int) ($resultCounts[PhoneCall::RESULT_FOLLOW_UP] ?? 0),
            'no_interest' => (int) ($resultCounts[PhoneCall::RESULT_NO_INTEREST] ?? 0),
        ];
        $unsuccessful = [
            'off'       => (int) ($failCounts[PhoneCall::FAIL_OFF] ?? 0),
            'no_answer' => (int) ($failCounts[PhoneCall::FAIL_NO_ANSWER] ?? 0),
            'rejected'  => (int) ($failCounts[PhoneCall::FAIL_REJECTED] ?? 0),
            'wrong'     => (int) ($failCounts[PhoneCall::FAIL_WRONG] ?? 0),
        ];

        $successfulTotal   = array_sum($successful);
        $unsuccessfulTotal = array_sum($unsuccessful);
        $totalCalls        = $successfulTotal + $unsuccessfulTotal;

        // (B) شماره‌های اختصاص‌یافته که هنوز تماسی روی آن‌ها ثبت نشده
        $notCalled = PhoneLead::query()
            ->with('activeAssignment.consultant:id,name')
            ->whereHas('assignments', fn ($q) => $q->where('status', PhoneLeadAssignment::STATUS_ACTIVE))
            ->whereDoesntHave('calls')
            ->latest()
            ->limit(100)
            ->get();

        $notCalledCount = PhoneLead::query()
            ->whereHas('assignments', fn ($q) => $q->where('status', PhoneLeadAssignment::STATUS_ACTIVE))
            ->whereDoesntHave('calls')
            ->count();

        // تعداد کل ثبت‌نام‌ها (مبنای پیشرفت هدف)
        $registeredTotal = $successful['registered'];

        // هدف تیمی (آخرین هدف)
        $teamGoal = RegistrationGoal::team()->latest()->first();

        // اهداف هر مشاور
        $consultantGoals = RegistrationGoal::whereNotNull('admin_id')
            ->with('admin:id,name')
            ->latest()
            ->get()
            ->map(function (RegistrationGoal $goal) {
                $goal->achieved = PhoneCall::where('admin_id', $goal->admin_id)
                    ->where('result', PhoneCall::RESULT_REGISTERED)
                    ->count();
                return $goal;
            });

        return view('livewire.admin.educational-manager.phone-acquisition.dashboard', [
            'totalLeads'        => $totalLeads,
            'totalCalls'        => $totalCalls,
            'successful'        => $successful,
            'unsuccessful'      => $unsuccessful,
            'successfulTotal'   => $successfulTotal,
            'unsuccessfulTotal' => $unsuccessfulTotal,
            'notCalled'         => $notCalled,
            'notCalledCount'    => $notCalledCount,
            'registeredTotal'   => $registeredTotal,
            'teamGoal'          => $teamGoal,
            'consultantGoals'   => $consultantGoals,
        ])->layout('layouts.admin.app');
    }
}
