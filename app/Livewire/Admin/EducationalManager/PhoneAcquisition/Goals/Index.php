<?php

namespace App\Livewire\Admin\EducationalManager\PhoneAcquisition\Goals;

use App\Models\Admin;
use App\Models\PhoneCall;
use App\Models\RegistrationGoal;
use App\Traits\NormalizesDigits;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * مدیر آموزشی — هدف‌گذاری ثبت‌نام جذب تلفنی (تیمی و هر مشاور).
 */
class Index extends Component
{
    use NormalizesDigits;

    public string $scope = 'team';        // team | consultant
    public $adminId = '';
    public $targetCount = '';
    public string $description = '';      // توضیحات هدف (نمایش به مشاوران)
    public string $goalDate = '';         // مقدار میلادی "Y-m-d" که تقویم شمسی پر می‌کند

    public function saveGoal(): void
    {
        $this->targetCount = $this->convertToEnglishDigits((string) $this->targetCount);

        $this->validate([
            'scope'       => ['required', 'in:team,consultant'],
            'adminId'     => ['nullable', 'required_if:scope,consultant', 'exists:admins,id'],
            'targetCount' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:2000'],
            'goalDate'    => ['required', 'date'],
        ], [
            'adminId.required_if' => 'مشاور را انتخاب کنید.',
            'targetCount.required' => 'تعداد هدف را وارد کنید.',
            'targetCount.min'      => 'تعداد هدف باید حداقل ۱ باشد.',
            'goalDate.required'    => 'تاریخ هدف را انتخاب کنید.',
        ]);

        RegistrationGoal::create([
            'admin_id'     => $this->scope === 'consultant' ? (int) $this->adminId : null,
            'target_count' => (int) $this->targetCount,
            'description'  => $this->description ?: null,
            'goal_date'    => $this->goalDate,
            'created_by'   => Auth::guard('admin')->id(),
        ]);

        $this->reset(['adminId', 'targetCount', 'description', 'goalDate']);
        $this->scope = 'team';
        $this->dispatch('goal-saved'); // پاک‌سازی تقویم در سمت کلاینت
        $this->dispatch('success', 'هدف با موفقیت ثبت شد.');
    }

    public function deleteGoal(int $id): void
    {
        RegistrationGoal::whereKey($id)->delete();
        $this->dispatch('success', 'هدف حذف شد.');
    }

    public function render()
    {
        $consultants = Admin::role('مشاور جذب تلفنی')->orderBy('name')->get();

        $goals = RegistrationGoal::with('admin:id,name', 'createdBy:id,name')
            ->latest()
            ->get()
            ->map(function (RegistrationGoal $goal) {
                $query = PhoneCall::where('result', PhoneCall::RESULT_REGISTERED);
                if ($goal->admin_id) {
                    $query->where('admin_id', $goal->admin_id);
                }
                $goal->achieved = $query->count();
                return $goal;
            });

        return view('livewire.admin.educational-manager.phone-acquisition.goals.index', [
            'consultants' => $consultants,
            'goals'       => $goals,
        ])->layout('layouts.admin.app');
    }
}
