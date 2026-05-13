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

    public function mount(int $id): void
    {
        $this->trialWeek = TrialWeek::with(['user', 'supporter', 'student'])->findOrFail($id);
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

    public function assignSupporter(TrialWeekService $service): void
    {
        $this->validate([
            'selectedSupporterId' => ['required', 'exists:admins,id'],
        ], [
            'selectedSupporterId.required' => 'لطفاً یک پشتیبان انتخاب کنید.',
        ]);

        $supporter = Admin::findOrFail($this->selectedSupporterId);
        $service->assignSupporter($this->trialWeek, $supporter);

        $this->trialWeek->refresh();
        $this->closeAssignModal();

        session()->flash('success', 'پشتیبان با موفقیت تخصیص یافت و جلسه آزمایشی ایجاد شد.');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $supporters = Admin::role('acquisition_supporter')
            ->orderBy('name')
            ->get(['id', 'name', 'mobile', 'email']);

        return view('livewire.manager.trial-week.detail', [
            'supporters' => $supporters,
        ])->layout('layouts.manager.app');
    }
}
