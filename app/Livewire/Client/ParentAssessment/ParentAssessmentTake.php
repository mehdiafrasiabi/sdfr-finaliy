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

class ParentAssessmentTake extends Component
{
    public string $token = '';
    public string $slug = '';
    public ?ParentAssessmentInvitation $invitation = null;
    public bool $expired = false;

    public ?int $selectedOptionId = null;
    public array $selectedOptionIds = [];
    public ?string $likertValue = null;

    public function mount(string $token, string $slug, ParentInvitationService $svc, AssessmentService $assessmentService): void
    {
        $this->token = $token;
        $this->slug = $slug;

        $inv = $svc->verifyToken($token);
        if (! $inv) {
            $this->expired = true;
            return;
        }
        $this->invitation = $inv;

        $assessment = Assessment::active()
            ->where('audience', Assessment::AUDIENCE_PARENT)
            ->where('slug', $slug)
            ->firstOrFail();

        $attempt = $assessmentService->startOrResumeParent($inv, $assessment);
        if ($attempt->isCompleted()) {
            $this->redirect(
                route('client.parent.assessment.list', ['token' => $token]),
                navigate: true,
            );
            return;
        }

        $this->preloadExistingAnswer();
    }

    private function getAttempt(): ParentAssessmentAttempt
    {
        return ParentAssessmentAttempt::where('invitation_id', $this->invitation->id)
            ->whereHas('assessment', fn ($q) => $q->where('slug', $this->slug))
            ->firstOrFail();
    }

    private function getCurrentQuestion(): ?AssessmentQuestion
    {
        $attempt = $this->getAttempt();
        $answeredIds = $attempt->answers()->pluck('question_id')->all();

        $next = AssessmentQuestion::where('assessment_id', $attempt->assessment_id)
            ->where('is_active', true)
            ->when(!empty($answeredIds), fn ($q) => $q->whereNotIn('id', $answeredIds))
            ->orderBy('order')
            ->with('options')
            ->first();

        if ($next) {
            return $next;
        }

        return AssessmentQuestion::where('assessment_id', $attempt->assessment_id)
            ->where('is_active', true)
            ->orderBy('order', 'desc')
            ->with('options')
            ->first();
    }

    private function preloadExistingAnswer(): void
    {
        $this->selectedOptionId = null;
        $this->selectedOptionIds = [];
        $this->likertValue = null;

        $question = $this->getCurrentQuestion();
        if (! $question) {
            return;
        }
        $existing = ParentAssessmentAnswer::where('attempt_id', $this->getAttempt()->id)
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
        $attempt = $this->getAttempt();
        $question = $this->getCurrentQuestion();
        if (! $question) {
            $this->redirect(
                route('client.parent.assessment.list', ['token' => $this->token]),
                navigate: true,
            );
            return;
        }

        $payload = $this->buildPayload($question);
        if ($payload === null) {
            $this->addError('answer', 'لطفاً پاسخ خود را انتخاب کنید.');
            return;
        }

        $assessmentService->saveParentAnswer($attempt, $question, $payload);

        $attempt->refresh();
        $totalActive = $question->assessment->questions()->where('is_active', true)->count();
        if ($attempt->answered_count >= $totalActive) {
            $assessmentService->completeParent($attempt);

            $this->invitation->refresh();
            if ($this->invitation->isCompleted()) {
                session()->flash('success', 'تمام تست‌های والدینی با موفقیت ثبت شد. متشکریم!');
            } else {
                session()->flash('success', 'این آزمون با موفقیت تکمیل شد. لطفاً آزمون بعدی را شروع کنید.');
            }
            $this->redirect(
                route('client.parent.assessment.list', ['token' => $this->token]),
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

        $attempt = $this->getAttempt();
        $question = $this->getCurrentQuestion();
        $totalActive = $attempt->assessment->questions()->where('is_active', true)->count();
        $currentIndex = $attempt->answered_count + 1;
        if ($currentIndex > $totalActive) {
            $currentIndex = $totalActive;
        }

        return view('livewire.client.parent-assessment.take', [
            'attempt'      => $attempt,
            'assessment'   => $attempt->assessment,
            'question'     => $question,
            'totalActive'  => $totalActive,
            'currentIndex' => $currentIndex,
        ])->layout('layouts.client.app-auth');
    }
}
