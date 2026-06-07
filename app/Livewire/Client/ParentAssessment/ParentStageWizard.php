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

class ParentStageWizard extends Component
{
    public string $token = '';
    public ?ParentAssessmentInvitation $invitation = null;
    public bool $expired = false;

    // تمام پاسخ‌ها یکجا نگه داشته می‌شن — key: "q_{question_id}"
    public array $answers = [];

    public function mount(string $token, ParentInvitationService $svc, AssessmentService $assessmentService): void
    {
        $this->token = $token;

        $inv = $svc->verifyToken($token);
        if (! $inv) { $this->expired = true; return; }
        $this->invitation = $inv;

        foreach ($this->parentAssessments() as $a) {
            $assessmentService->startOrResumeParent($inv, $a);
        }

        // بارگذاری پاسخ‌های قبلی
        $this->loadExistingAnswers();
    }

    private function parentAssessments(): \Illuminate\Support\Collection
    {
        return Assessment::active()
            ->where('audience', Assessment::AUDIENCE_PARENT)
            ->ordered()
            ->with(['questions' => fn($q) => $q->where('is_active', true)->orderBy('order')->with('options')])
            ->get();
    }

    private function loadExistingAnswers(): void
    {
        if (! $this->invitation) return;

        $attemptIds = ParentAssessmentAttempt::where('invitation_id', $this->invitation->id)->pluck('id');

        $existing = ParentAssessmentAnswer::whereIn('attempt_id', $attemptIds)->get();

        foreach ($existing as $ans) {
            $key = 'q_' . $ans->question_id;
            if (! empty($ans->selected_options)) {
                // multi-select: ذخیره به صورت آرایه
                $this->answers[$key] = $ans->selected_options;
            } elseif ($ans->free_value !== null) {
                // likert
                $this->answers[$key] = $ans->free_value;
            } else {
                $this->answers[$key] = $ans->selected_option_id ? (string) $ans->selected_option_id : null;
            }
        }
    }

    /**
     * ثبت همه پاسخ‌ها یکجا
     */
    public function submitAll(AssessmentService $assessmentService): void
    {
        if ($this->expired || ! $this->invitation) return;

        $assessments = $this->parentAssessments();
        $hasError = false;

        foreach ($assessments as $assessment) {
            $attempt = ParentAssessmentAttempt::where('invitation_id', $this->invitation->id)
                ->where('assessment_id', $assessment->id)
                ->first();

            if (! $attempt) continue;

            foreach ($assessment->questions as $question) {
                $key = 'q_' . $question->id;
                $value = $this->answers[$key] ?? null;

                if ($value === null || $value === '' || $value === []) {
                    $hasError = true;
                    continue;
                }

                $payload = $this->buildPayload($question, $value);
                if ($payload === null) { $hasError = true; continue; }

                $assessmentService->saveParentAnswer($attempt, $question, $payload);
            }

            $attempt->refresh();
            $totalActive = $assessment->questions->count();
            if ($attempt->answered_count >= $totalActive) {
                $assessmentService->completeParent($attempt);
            }
        }

        if ($hasError) {
            $this->addError('submit', 'لطفاً به تمام سوالات پاسخ دهید.');
            return;
        }

        $this->invitation->refresh();
        if ($this->invitation->isCompleted()) {
            $this->redirect(
                route('client.parent.assessment.thank-you', ['token' => $this->token]),
                navigate: true,
            );
        }
    }

    private function buildPayload(AssessmentQuestion $question, mixed $value): ?array
    {
        if ($question->isMultiSelect()) {
            $ids = is_array($value)
                ? array_values(array_filter(array_map('intval', $value)))
                : [];
            if (empty($ids)) return null;
            $validIds = $question->options->pluck('id')->all();
            $ids = array_values(array_intersect($ids, $validIds));
            if (empty($ids)) return null;
            return ['selected_options' => $ids, 'selected_option_id' => null, 'free_value' => null];
        }

        if ($question->type === AssessmentQuestion::TYPE_LIKERT5) {
            $v = (string) $value;
            if (! in_array($v, ['1','2','3','4','5'], true)) return null;
            $option = $question->options->firstWhere('value', $v);
            return ['selected_option_id' => $option?->id, 'selected_options' => null, 'free_value' => $v];
        }

        $optionId = (int) $value;
        if ($optionId <= 0) return null;
        if (! $question->options->pluck('id')->contains($optionId)) return null;
        return ['selected_option_id' => $optionId, 'selected_options' => null, 'free_value' => null];
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        if ($this->expired || ! $this->invitation) {
            return view('livewire.client.parent-assessment.expired')
                ->layout('layouts.client.app-auth');
        }

        $assessments = $this->parentAssessments();

        $assessmentIds = $assessments->pluck('id');
        $totalQuestions = (int) AssessmentQuestion::whereIn('assessment_id', $assessmentIds)
            ->where('is_active', true)->count();
        $attemptIds = ParentAssessmentAttempt::where('invitation_id', $this->invitation->id)->pluck('id');
        $answered   = (int) ParentAssessmentAnswer::whereIn('attempt_id', $attemptIds)->count();
        $percent    = $totalQuestions > 0 ? (int) round(($answered / $totalQuestions) * 100) : 0;

        return view('livewire.client.parent-assessment.stage-wizard', [
            'assessments'    => $assessments,
            'totalQuestions' => $totalQuestions,
            'answered'       => $answered,
            'percent'        => $percent,
        ])->layout('layouts.client.app-auth');
    }
}
