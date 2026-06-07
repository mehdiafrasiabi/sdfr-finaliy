<?php

namespace App\Livewire\Client\ParentAssessment;

use App\Models\Assessment;
use App\Models\AssessmentQuestion;
use App\Models\ParentAssessmentAnswer;
use App\Models\ParentAssessmentAttempt;
use App\Models\ParentAssessmentInvitation;
use App\Services\AssessmentService;
use App\Services\ParentInvitationService;
use Livewire\Component;

/**
 * ویزارد ترکیبی برای والد — دو تست والدینی پشت‌سر‌هم در یک sequence.
 */
class ParentStageWizard extends Component
{
    public string $token = '';
    public ?ParentAssessmentInvitation $invitation = null;
    public bool $expired = false;

    public ?int $selectedOptionId = null;
    public array $selectedOptionIds = [];
    public ?string $likertValue = null;

    public function mount(string $token, ParentInvitationService $svc, AssessmentService $assessmentService): void
    {
        $this->token = $token;

        $inv = $svc->verifyToken($token);
        if (! $inv) {
            $this->expired = true;
            return;
        }
        $this->invitation = $inv;

        // اطمینان از وجود attempt برای همه‌ی آزمون‌های parent
        foreach ($this->parentAssessments() as $a) {
            $assessmentService->startOrResumeParent($inv, $a);
        }

        $this->preloadExistingAnswer();
    }

    /** @return \Illuminate\Support\Collection<int, Assessment> */
    private function parentAssessments(): \Illuminate\Support\Collection
    {
        return Assessment::active()
            ->where('audience', Assessment::AUDIENCE_PARENT)
            ->ordered()
            ->get();
    }

    private function getCurrent(): ?array
    {
        if (! $this->invitation) {
            return null;
        }

        foreach ($this->parentAssessments() as $assessment) {
            $attempt = ParentAssessmentAttempt::where('invitation_id', $this->invitation->id)
                ->where('assessment_id', $assessment->id)
                ->first();

            $answeredIds = $attempt
                ? $attempt->answers()->pluck('question_id')->all()
                : [];

            $question = AssessmentQuestion::where('assessment_id', $assessment->id)
                ->where('is_active', true)
                ->when(!empty($answeredIds), fn ($q) => $q->whereNotIn('id', $answeredIds))
                ->orderBy('order')
                ->with('options')
                ->first();

            if ($question) {
                return ['assessment' => $assessment, 'question' => $question, 'attempt' => $attempt];
            }
        }
        return null;
    }

    private function preloadExistingAnswer(): void
    {
        $this->selectedOptionId = null;
        $this->selectedOptionIds = [];
        $this->likertValue = null;

        $current = $this->getCurrent();
        if (! $current) {
            return;
        }
        $question = $current['question'];
        $attempt = $current['attempt'];
        if (! $attempt) {
            return;
        }
        $existing = ParentAssessmentAnswer::where('attempt_id', $attempt->id)
            ->where('question_id', $question->id)
            ->first();
        if (! $existing) {
            return;
        }
        if ($question->isMultiSelect()) {
            $this->selectedOptionIds = $existing->selected_options ?? [];
        } elseif ($question->type === AssessmentQuestion::TYPE_LIKERT5) {
            $this->likertValue = $existing->free_value;
            $this->selectedOptionId = $existing->selected_option_id;
        } else {
            $this->selectedOptionId = $existing->selected_option_id;
        }
    }

    public function submitAnswer(AssessmentService $assessmentService): void
    {
        if ($this->expired || ! $this->invitation) {
            return;
        }

        $current = $this->getCurrent();
        if (! $current) {
            $this->redirect(
                route('client.parent.assessment.thank-you', ['token' => $this->token]),
                navigate: true,
            );
            return;
        }

        $question = $current['question'];
        $assessment = $current['assessment'];
        $attempt = $current['attempt'] ?? ParentAssessmentAttempt::where('invitation_id', $this->invitation->id)
            ->where('assessment_id', $assessment->id)->firstOrFail();

        $payload = $this->buildPayload($question);
        if ($payload === null) {
            $this->addError('answer', 'لطفاً پاسخ خود را انتخاب کنید.');
            return;
        }

        $assessmentService->saveParentAnswer($attempt, $question, $payload);

        $attempt->refresh();
        $totalActive = $assessment->questions()->where('is_active', true)->count();
        if ($attempt->answered_count >= $totalActive) {
            $assessmentService->completeParent($attempt);
        }

        $this->invitation->refresh();
        if ($this->invitation->isCompleted()) {
            $this->redirect(
                route('client.parent.assessment.thank-you', ['token' => $this->token]),
                navigate: true,
            );
            return;
        }

        $this->preloadExistingAnswer();
    }

    private function buildPayload(AssessmentQuestion $question): ?array
    {
        if ($question->isMultiSelect()) {
            $ids = array_values(array_filter(array_map('intval', $this->selectedOptionIds)));
            if (empty($ids)) {
                return null;
            }
            $validIds = $question->options->pluck('id')->all();
            $ids = array_values(array_intersect($ids, $validIds));
            if (empty($ids)) {
                return null;
            }
            return [
                'selected_options'   => $ids,
                'selected_option_id' => null,
                'free_value'         => null,
            ];
        }
        if ($question->type === AssessmentQuestion::TYPE_LIKERT5) {
            if (! in_array($this->likertValue, ['1','2','3','4','5'], true)) {
                return null;
            }
            $option = $question->options->firstWhere('value', $this->likertValue);
            return [
                'selected_option_id' => $option?->id,
                'selected_options'   => null,
                'free_value'         => $this->likertValue,
            ];
        }
        $optionId = (int) $this->selectedOptionId;
        if ($optionId <= 0) {
            return null;
        }
        if (! $question->options->pluck('id')->contains($optionId)) {
            return null;
        }
        return [
            'selected_option_id' => $optionId,
            'selected_options'   => null,
            'free_value'         => null,
        ];
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        if ($this->expired || ! $this->invitation) {
            return view('livewire.client.parent-assessment.expired')
                ->layout('layouts.client.app-auth');
        }

        $current = $this->getCurrent();

        $assessmentIds = $this->parentAssessments()->pluck('id');
        $totalQuestions = (int) AssessmentQuestion::whereIn('assessment_id', $assessmentIds)
            ->where('is_active', true)->count();
        $attemptIds = ParentAssessmentAttempt::where('invitation_id', $this->invitation->id)
            ->pluck('id');
        $answered = (int) ParentAssessmentAnswer::whereIn('attempt_id', $attemptIds)->count();
        $percent = $totalQuestions > 0 ? (int) round(($answered / $totalQuestions) * 100) : 0;

        return view('livewire.client.parent-assessment.stage-wizard', [
            'assessment'   => $current['assessment'] ?? null,
            'question'     => $current['question']   ?? null,
            'totalActive'  => $totalQuestions,
            'answered'     => $answered,
            'percent'      => $percent,
            'currentIndex' => min($answered + 1, $totalQuestions),
        ])->layout('layouts.client.app-auth');
    }
}
