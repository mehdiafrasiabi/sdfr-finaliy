<?php

namespace App\Livewire\Client\Profile\Assessment;

use App\Models\Assessment;
use App\Models\AssessmentQuestion;
use App\Models\StudentAssessmentAttempt;
use App\Services\AssessmentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * صفحهٔ پاسخ‌دهی به آزمون — همهٔ سوالاتِ یک دسته (assessment) یک‌جا نمایش داده می‌شوند.
 * پاسخ‌ها در $answers (کلید = question_id) نگه‌داری و در پایان به‌صورت دسته‌ای ذخیره می‌شوند.
 *   - لیکرت:        '1'..'5'
 *   - تک‌انتخابی:    option_id (به‌صورت رشته)
 *   - چندانتخابی:   آرایه‌ای از option_id
 */
class AssessmentTake extends Component
{
    public string $slug = '';

    /** پاسخ‌ها به تفکیک question_id */
    public array $answers = [];

    /** مودالِ معرفیِ تست در اولین ورود */
    public bool $showIntro = true;

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

        // محافظ: تستِ بدونِ سوالِ فعال نباید کاربر را گیر بیندازد.
        if (! $assessment->questions()->where('is_active', true)->exists()) {
            $this->redirect(route('client.profile.assessment.list'), navigate: true);
            return;
        }

        $this->preloadAnswers($attempt, $assessment);

        // مودالِ معرفی فقط وقتی هنوز هیچ پاسخی ثبت نشده باشد.
        $this->showIntro = (int) $attempt->answered_count === 0;
    }

    private function getAttempt(): StudentAssessmentAttempt
    {
        return StudentAssessmentAttempt::where('user_id', Auth::id())
            ->whereHas('assessment', fn ($q) => $q->where('slug', $this->slug))
            ->firstOrFail();
    }

    private function preloadAnswers(StudentAssessmentAttempt $attempt, Assessment $assessment): void
    {
        $questions = $assessment->questions()->where('is_active', true)->with('options')->get();
        $existing  = $attempt->answers()->get()->keyBy('question_id');

        foreach ($questions as $q) {
            $ans = $existing->get($q->id);
            if (! $ans) {
                continue;
            }

            if ($q->isMultiSelect()) {
                $this->answers[$q->id] = array_map('strval', $ans->selected_options ?? []);
            } elseif ($q->type === AssessmentQuestion::TYPE_LIKERT5) {
                $this->answers[$q->id] = $ans->free_value;
            } else {
                $this->answers[$q->id] = $ans->selected_option_id ? (string) $ans->selected_option_id : null;
            }
        }
    }

    private function buildPayload(AssessmentQuestion $question): ?array
    {
        $val = $this->answers[$question->id] ?? null;

        if ($question->isMultiSelect()) {
            $ids = array_values(array_filter(array_map('intval', (array) $val)));
            $validIds = $question->options->pluck('id')->all();
            $ids = array_values(array_intersect($ids, $validIds));
            if (empty($ids)) {
                return null;
            }
            return ['selected_options' => $ids, 'selected_option_id' => null, 'free_value' => null];
        }

        if ($question->type === AssessmentQuestion::TYPE_LIKERT5) {
            if (! in_array((string) $val, ['1', '2', '3', '4', '5'], true)) {
                return null;
            }
            $option = $question->options->firstWhere('value', (string) $val);
            return ['selected_option_id' => $option?->id, 'selected_options' => null, 'free_value' => (string) $val];
        }

        // mbti_binary, yes_no و سایر تک‌انتخابی‌ها
        $optionId = (int) $val;
        if ($optionId <= 0 || ! $question->options->pluck('id')->contains($optionId)) {
            return null;
        }
        return ['selected_option_id' => $optionId, 'selected_options' => null, 'free_value' => null];
    }

    public function submitAll(AssessmentService $service): void
    {
        $attempt = $this->getAttempt();

        // اگر قبلاً تکمیل شده (دوبار کلیک) — بی‌سروصدا به مرحله بعد می‌رویم.
        if ($attempt->isCompleted()) {
            $this->goNext($service);
            return;
        }

        $assessment = $attempt->assessment;
        $questions  = $assessment->questions()->where('is_active', true)->with('options')->orderBy('order')->get();

        // اعتبارسنجی: همهٔ سوالات باید پاسخ داشته باشند.
        $missing = 0;
        foreach ($questions as $q) {
            if ($this->buildPayload($q) === null) {
                $missing++;
            }
        }
        if ($missing > 0) {
            $this->addError('answers', "لطفاً به همهٔ سوالات پاسخ دهید. {$missing} سوال بی‌پاسخ مانده است.");
            return;
        }

        foreach ($questions as $q) {
            $service->saveAnswer($attempt, $q, $this->buildPayload($q));
        }

        $attempt->refresh();
        $service->complete($attempt);

        $this->goNext($service);
    }

    private function goNext(AssessmentService $service): void
    {
        $next = $service->nextStudentAssessment(Auth::user());

        if ($next) {
            $this->redirect(route('client.profile.assessment.take', ['slug' => $next->slug]), navigate: true);
            return;
        }

        $service->checkAllCompleted(Auth::user());
        session()->flash('success', 'تمام آزمون‌ها با موفقیت تکمیل شد.');
        $this->redirect(route('client.profile.assessment.list'), navigate: true);
    }

    public function render(AssessmentService $service): \Illuminate\Contracts\View\View
    {
        $attempt    = $this->getAttempt();
        $assessment = $attempt->assessment;
        $questions  = $assessment->questions()->where('is_active', true)->with('options')->orderBy('order')->get();

        // ───── پیشرفتِ کلی روی همهٔ آزمون‌ها (C5) ─────
        $stageAssessments = $service->studentAssessmentsInStageOrder();
        $allAttempts = StudentAssessmentAttempt::where('user_id', Auth::id())
            ->whereIn('assessment_id', $stageAssessments->pluck('id'))
            ->get()->keyBy('assessment_id');

        $globalTotal   = 0;
        $otherAnswered = 0;  // پاسخ‌های ثبت‌شده در سایرِ آزمون‌ها (به‌جز آزمون جاری)
        foreach ($stageAssessments as $a) {
            $globalTotal += $a->questions()->where('is_active', true)->count();
            if ($a->id !== $assessment->id) {
                $otherAnswered += (int) ($allAttempts->get($a->id)?->answered_count ?? 0);
            }
        }

        $isMulti = $assessment->question_type === AssessmentQuestion::TYPE_VARK_MULTI
            || $assessment->kind === Assessment::KIND_VARK;

        return view('livewire.client.profile.assessment.assessment-take', [
            'assessment'    => $assessment,
            'questions'     => $questions,
            'questionIds'   => $questions->pluck('id')->values()->all(),
            'globalTotal'   => $globalTotal,
            'otherAnswered' => $otherAnswered,
            'isMulti'       => $isMulti,
        ])->layout('layouts.client.app');
    }
}
