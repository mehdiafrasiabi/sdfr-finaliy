<?php

namespace App\Livewire\Client\Profile\Assessment;

use App\Services\AssessmentInterpretationService;
use App\Services\TrialWeekService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * صفحه‌ی نمایش پروفایل به دانش‌آموز قبل از ساخت برنامه.
 * تأیید او پروفایل را acknowledged می‌کند که gate ساخت برنامه است.
 */
class ProfileReview extends Component
{
    public function mount(): void
    {
        $user = Auth::user();
        $trial = $user?->trialWeek;

        // اگر هنوز همه‌ی آزمون‌ها تمام نشده‌اند، به journey برگرد.
        if (! $trial || ! $trial->assessments_completed_at) {
            $this->redirect(route('client.profile.assessment.journey'), navigate: true);
            return;
        }

        // اگر قبلاً تأیید کرده، به waiting برو.
        if ($trial->profile_acknowledged_at) {
            $this->redirect(route('client.profile.waiting-for-supporter'), navigate: true);
            return;
        }
    }

    public function acknowledge(TrialWeekService $service): void
    {
        $service->acknowledgeProfile(Auth::user());
        session()->flash('success', 'پروفایل شما با موفقیت تأیید شد. منتظر تخصیص پشتیبان باشید.');
        $this->redirect(route('client.profile.waiting-for-supporter'), navigate: true);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $summary = app(AssessmentInterpretationService::class)
            ->summarizeForReview(Auth::user());

        return view('livewire.client.profile.assessment.profile-review', [
            'summary' => $summary,
        ])->layout('layouts.client.app');
    }
}
