<?php

namespace App\Livewire\Client\Profile\Assessment;

use App\Models\Assessment;
use App\Models\AssessmentQuestion;
use App\Models\StudentAssessmentAnswer;
use App\Services\AssessmentJourneyService;
use App\Services\AssessmentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * ویزارد یک‌سوال‌در‌صفحه برای یک stage. برای mbti/vark یک assessment،
 * برای mindset چندین assessment که سوالاتشان به‌ترتیب پشت‌سر‌هم نمایش
 * داده می‌شوند (بدون اعلان بین تست‌ها).
 */
class StageWizard extends Component
{
    public string $stage = '';

    public ?int $selectedOptionId = null;     // single-select
    public array $selectedOptionIds = [];     // multi-select (VARK)
    public ?string $likertValue = null;       // '1'..'5'

    public function mount(string $stage, AssessmentJourneyService $journey, AssessmentService $service): void
    {
        if (! in_array($stage, AssessmentJourneyService::STAGES, true)) {
            $this->redirect(route('client.profile.assessment.journey'), navigate: true);
            return;
        }
        $this->stage = $stage;

        // اطمینان از وجود attempt برای همه‌ی آزمون‌های stage تا nextQuestionInStage درست کار کند.
        $user = Auth::user();
        foreach ($journey->getStageAssessments($stage) as $assessment) {
            $service->startOrResume($user, $assessment);
        }

        $this->preloadExistingAnswer();
    }

    private function getCurrent(): ?array
    {
        $journey = app(AssessmentJourneyService::class);
        return $journey->nextQuestionInStage(Auth::user(), $this->stage);
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
        $assessment = $current['assessment'];
        $attempt = Auth::user()->assessmentAttempts()
            ->where('assessment_id', $assessment->id)
            ->first();
        if (! $attempt) {
            return;
        }

        $existing = StudentAssessmentAnswer::where('attempt_id', $attempt->id)
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

    public function submitAnswer(AssessmentService $service, AssessmentJourneyService $journey): void
    {
        $current = $this->getCurrent();
        if (! $current) {
            // stage تمام شده — به journey برگرد
            $this->redirect(route('client.profile.assessment.journey'), navigate: true);
            return;
        }

        $question = $current['question'];
        $assessment = $current['assessment'];
        $attempt = Auth::user()->assessmentAttempts()
            ->where('assessment_id', $assessment->id)
            ->firstOrFail();

        $payload = $this->buildPayload($question);
        if ($payload === null) {
            $this->addError('answer', 'لطفاً پاسخ خود را انتخاب کنید.');
            return;
        }

        $service->saveAnswer($attempt, $question, $payload);

        // بررسی تکمیل assessment فعلی
        $attempt->refresh();
        $totalActive = $assessment->questions()->where('is_active', true)->count();
        if ($attempt->answered_count >= $totalActive) {
            $service->complete($attempt);
        }

        // بررسی تکمیل stage / همه‌ی آزمون‌ها
        if ($journey->isStageCompleted(Auth::user(), $this->stage)) {
            // اگر آخرین stage بود، checkAllCompleted را صدا می‌زنیم تا parent invitation
            // و assessments_completed_at تنظیم شوند.
            if ($this->stage === AssessmentJourneyService::STAGE_MINDSET) {
                $service->checkAllCompleted(Auth::user());
            }
            $this->redirect(route('client.profile.assessment.journey'), navigate: true);
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
        $journey = app(AssessmentJourneyService::class);
        $current = $this->getCurrent();
        $status = $journey->getStageStatus(Auth::user(), $this->stage);

        return view('livewire.client.profile.assessment.stage-wizard', [
            'stage'        => $this->stage,
            'title'        => $journey->stageTitle($this->stage),
            'assessment'   => $current['assessment'] ?? null,
            'question'     => $current['question']   ?? null,
            'totalActive'  => $status['total_questions'],
            'answered'     => $status['answered'],
            'percent'      => $status['percent'],
            'currentIndex' => min($status['answered'] + 1, $status['total_questions']),
        ])->layout('layouts.client.app');
    }
}
