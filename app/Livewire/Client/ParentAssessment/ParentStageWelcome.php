<?php

namespace App\Livewire\Client\ParentAssessment;

use App\Models\Assessment;
use App\Models\ParentAssessmentAttempt;
use App\Models\ParentAssessmentInvitation;
use App\Services\ParentInvitationService;
use Livewire\Component;

class ParentStageWelcome extends Component
{
    public string $token = '';
    public ?ParentAssessmentInvitation $invitation = null;
    public bool $expired = false;

    public int $totalQuestions = 0;
    public int $answered = 0;
    public bool $isFirstEntry = true;
    public bool $isCompleted = false;

    public function mount(string $token, ParentInvitationService $svc): void
    {
        $this->token = $token;

        $inv = $svc->verifyToken($token);
        if (! $inv) {
            $this->expired = true;
            return;
        }
        $this->invitation = $inv;

        $this->loadStatus();

        if ($this->isCompleted) {
            $this->redirect(
                route('client.parent.assessment.thank-you', ['token' => $this->token]),
                navigate: true,
            );
        }
    }

    private function loadStatus(): void
    {
        $assessmentIds = Assessment::active()
            ->where('audience', Assessment::AUDIENCE_PARENT)
            ->pluck('id');

        $this->totalQuestions = (int) \App\Models\AssessmentQuestion::whereIn('assessment_id', $assessmentIds)
            ->where('is_active', true)
            ->count();

        $attemptIds = ParentAssessmentAttempt::where('invitation_id', $this->invitation->id)
            ->pluck('id');

        $this->answered = (int) \App\Models\ParentAssessmentAnswer::whereIn('attempt_id', $attemptIds)->count();
        $this->isFirstEntry = $this->answered === 0;
        $this->isCompleted = $this->totalQuestions > 0 && $this->answered >= $this->totalQuestions;
    }

    public function proceed(): void
    {
        if (! $this->invitation || $this->expired) {
            return;
        }
        $this->redirect(
            route('client.parent.assessment.take', ['token' => $this->token]),
            navigate: true,
        );
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $percent = $this->totalQuestions > 0
            ? (int) round(($this->answered / $this->totalQuestions) * 100)
            : 0;

        return view('livewire.client.parent-assessment.stage-welcome', [
            'percent' => $percent,
        ])->layout('layouts.client.app-auth');
    }
}
