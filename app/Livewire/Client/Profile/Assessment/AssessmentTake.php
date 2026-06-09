<?php

namespace App\Livewire\Client\Profile\Assessment;

use App\Models\Assessment;
use App\Models\AssessmentQuestion;
use App\Models\StudentAssessmentAnswer;
use App\Models\StudentAssessmentAttempt;
use App\Services\AssessmentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AssessmentTake extends Component
{
    public string $slug = '';

    public ?int $selectedOptionId = null;     // single-select
    public array $selectedOptionIds = [];     // multi-select (VARK)
    public ?string $likertValue = null;       // '1'..'5'

    public function mount(string $slug, AssessmentService $service): void
    {
        $this->slug = $slug;

        $user = Auth::user();
        $assessment = Assessment::active()->where('slug', $slug)->firstOrFail();

        $attempt = $service->startOrResume($user, $assessment);

        if ($attempt->isCompleted()) {
            $this->redirect(route('client.profile.assessment.list'), navigate: true);
            return;
        }

        $this->preloadExistingAnswer();
    }

    private function getAttempt(): StudentAssessmentAttempt
    {
        return StudentAssessmentAttempt::where('user_id', Auth::id())
            ->whereHas('assessment', fn ($q) => $q->where('slug', $this->slug))
            ->firstOrFail();
    }

    private function getCurrentQuestion(): ?AssessmentQuestion
    {
        $attempt = $this->getAttempt();
        // اولین سوال فعال بی‌پاسخ. اگر همه پاسخ خورده باشند، آخرین فعال را برمی‌گردانیم تا UI درست رندر شود.
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
        if (!$question) {
            return;
        }

        $existing = StudentAssessmentAnswer::where('attempt_id', $this->getAttempt()->id)
            ->where('question_id', $question->id)
            ->first();

        if (!$existing) {
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

    public function submitAnswer(AssessmentService $service): void
    {
        $attempt = $this->getAttempt();
        $question = $this->getCurrentQuestion();

        if (!$question) {
            $this->redirect(route('client.profile.assessment.list'), navigate: true);
            return;
        }

        $payload = $this->buildPayload($question);
        if ($payload === null) {
            $this->addError('answer', 'لطفاً پاسخ خود را انتخاب کنید.');
            return;
        }

        $service->saveAnswer($attempt, $question, $payload);

        // بررسی تکمیل
        $attempt->refresh();
        $totalActive = $question->assessment->questions()->where('is_active', true)->count();
        if ($attempt->answered_count >= $totalActive) {
            $service->complete($attempt);

            // مرحله بعد: اگر آزمون دیگری باقی مانده، بدون توقف مستقیم به آن می‌رویم
            // (زنجیرهٔ MBTI ← مایندست). در غیر این صورت پایان و صفحهٔ تشکر.
            $next = $service->nextStudentAssessment(Auth::user());
            if ($next) {
                $this->redirect(
                    route('client.profile.assessment.take', ['slug' => $next->slug]),
                    navigate: true
                );
                return;
            }

            $service->checkAllCompleted(Auth::user());
            session()->flash('success', 'تمام آزمون‌ها با موفقیت تکمیل شد.');
            $this->redirect(route('client.profile.assessment.list'), navigate: true);
            return;
        }

        // فقط در همین صفحه سوال بعدی را بار می‌کنیم
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
            if (!in_array($this->likertValue, ['1','2','3','4','5'], true)) {
                return null;
            }
            $option = $question->options->firstWhere('value', $this->likertValue);
            return [
                'selected_option_id' => $option?->id,
                'selected_options'   => null,
                'free_value'         => $this->likertValue,
            ];
        }

        // mbti_binary, yes_no
        $optionId = (int) $this->selectedOptionId;
        if ($optionId <= 0) {
            return null;
        }
        if (!$question->options->pluck('id')->contains($optionId)) {
            return null;
        }
        return [
            'selected_option_id' => $optionId,
            'selected_options'   => null,
            'free_value'         => null,
        ];
    }
    public function goToPrevious(): void
    {
        if ($this->currentIndex > 1) {
            $this->currentIndex--;
            $this->resetValidation();
            // اگر متدی برای بارگذاری سوال/پاسخِ این ایندکس داری، اینجا صدا بزن:
            // $this->loadCurrentQuestion();
        }
    }
    public function render(): \Illuminate\Contracts\View\View
    {
        $attempt = $this->getAttempt();
        $question = $this->getCurrentQuestion();

        $totalActive = $attempt->assessment->questions()->where('is_active', true)->count();
        $currentIndex = $attempt->answered_count + 1;
        if ($currentIndex > $totalActive) {
            $currentIndex = $totalActive;
        }

        return view('livewire.client.profile.assessment.assessment-take', [
            'attempt'      => $attempt,
            'assessment'   => $attempt->assessment,
            'question'     => $question,
            'totalActive'  => $totalActive,
            'currentIndex' => $currentIndex,
        ])->layout('layouts.client.app');
    }
}
