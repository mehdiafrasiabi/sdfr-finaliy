<?php

namespace App\Livewire\Client\Profile\Assessment;

use App\Services\AssessmentJourneyService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * صفحه‌ی خوش‌آمدگویی یک stage. هر بار ورود نمایش داده می‌شود با پیام
 * شخصی بر اساس وضعیت (بار اول vs بازگشت برای ادامه).
 */
class StageWelcome extends Component
{
    public string $stage = '';

    public function mount(string $stage, AssessmentJourneyService $journey): void
    {
        if (! in_array($stage, AssessmentJourneyService::STAGES, true)) {
            $this->redirect(route('client.profile.assessment.journey'), navigate: true);
            return;
        }

        $user = Auth::user();
        $status = $journey->getStageStatus($user, $stage);

        // اگر این stage تمام شده، به journey برمی‌گردانیم تا تصمیم بعدی گرفته شود.
        if ($status['is_completed']) {
            $this->redirect(route('client.profile.assessment.journey'), navigate: true);
            return;
        }

        $this->stage = $stage;
    }

    public function proceed(): void
    {
        $this->redirect(
            route('client.profile.assessment.take', ['stage' => $this->stage]),
            navigate: true,
        );
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $journey = app(AssessmentJourneyService::class);
        $user = Auth::user();
        $status = $journey->getStageStatus($user, $this->stage);

        return view('livewire.client.profile.assessment.stage-welcome', [
            'status'      => $status,
            'title'       => $journey->stageTitle($this->stage),
            'description' => $journey->stageDescription($this->stage),
            'userName'    => $user?->name ?? '',
        ])->layout('layouts.client.app');
    }
}
