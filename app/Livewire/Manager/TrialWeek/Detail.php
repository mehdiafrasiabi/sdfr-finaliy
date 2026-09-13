<?php

namespace App\Livewire\Manager\TrialWeek;

use App\Models\Admin;
use App\Models\TrialWeek;
use App\Services\TrialWeekService;
use Livewire\Component;

class Detail extends Component
{
    public TrialWeek $trialWeek;
    public ?int      $selectedSupporterId = null;
    public bool      $showAssignModal     = false;
    public bool      $showResetConfirm    = false;

    public function mount(int $id): void
    {
        $this->trialWeek = TrialWeek::with(['user', 'acquisitionSupporter', 'student'])->findOrFail($id);
    }

    public function openAssignModal(): void
    {
        $this->showAssignModal = true;
    }

    public function closeAssignModal(): void
    {
        $this->showAssignModal = false;
        $this->selectedSupporterId = null;
    }

    /**
     * آیا این هفتهٔ آزمایشی قابل ریست است؟
     * فقط کسانی که در بازهٔ هفتهٔ آزمایشی هستند و از آن استفاده می‌کنند
     * (برنامه ساخته شده و هنوز منقضی نشده).
     */
    public function getCanResetProperty(): bool
    {
        return $this->trialWeek->hasFullAccess();
    }

    public function openResetConfirm(): void
    {
        if (! $this->canReset) {
            return;
        }
        $this->showResetConfirm = true;
    }

    public function closeResetConfirm(): void
    {
        $this->showResetConfirm = false;
    }

    public function resetTrial(TrialWeekService $service): void
    {
        if (! $this->canReset) {
            $this->showResetConfirm = false;
            session()->flash('error', 'ریست فقط برای دانش‌آموزانی که در حال حاضر در بازهٔ هفتهٔ آزمایشی هستند امکان‌پذیر است.');
            return;
        }

        $service->resetTrialWeek($this->trialWeek);

        $this->trialWeek->refresh();
        $this->showResetConfirm = false;

        session()->flash('success', 'فرایند هفتهٔ آزمایشی برای این دانش‌آموز ریست شد. طبقه‌بندی، پیش‌جلسه و برنامهٔ ساخته‌شده پاک شد؛ نتایج آزمون‌های شخصیتی (مایندست) دست‌نخورده ماند.');
    }

    public function assignSupporter(TrialWeekService $service): void
    {
        $this->validate([
            'selectedSupporterId' => ['required', 'exists:admins,id'],
        ], [
            'selectedSupporterId.required' => 'لطفاً یک پشتیبان انتخاب کنید.',
        ]);

        $supporter = Admin::findOrFail($this->selectedSupporterId);
        try {
            $service->assignSupporter($this->trialWeek, $supporter);
        } catch (\LogicException $e) {
            $this->closeAssignModal();
            session()->flash('error', $e->getMessage());
            return;
        }

        $this->trialWeek->refresh();
        $this->closeAssignModal();

        session()->flash('success', 'پشتیبان با موفقیت تخصیص یافت و جلسه آزمایشی ایجاد شد.');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $supporters = Admin::role('site acquisition')
            ->orderBy('name')
            ->get(['id', 'name', 'mobile', 'email']);

        return view('livewire.manager.trial-week.detail', [
            'supporters' => $supporters,
        ])->layout('layouts.manager.app');
    }
}
