<?php

namespace App\Livewire\Manager\TrialWeek;

use App\Models\Admin;
use App\Models\ParentAssessmentInvitation;
use App\Models\TrialWeek;
use App\Services\ParentInvitationService;
use App\Services\TrialWeekService;
use Livewire\Component;

class Detail extends Component
{
    public TrialWeek $trialWeek;
    public ?int      $selectedSupporterId = null;
    public bool      $showAssignModal     = false;

    public function mount(int $id): void
    {
        $this->trialWeek = TrialWeek::with(['user', 'supporter', 'student', 'parentAssessmentInvitations'])->findOrFail($id);
    }

    public function resendParentInvite(int $invitationId, ParentInvitationService $svc): void
    {
        $inv = ParentAssessmentInvitation::where('id', $invitationId)
            ->where('trial_week_id', $this->trialWeek->id)
            ->firstOrFail();
        $svc->resend($inv);
        $this->trialWeek->refresh();
        session()->flash('success', 'لینک تازه‌ای به ' . $inv->parent_role_label . ' ارسال شد.');
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
