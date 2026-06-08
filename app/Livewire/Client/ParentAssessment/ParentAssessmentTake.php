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
    public ?ParentAssessmentInvitation $invitation = null;
    public bool $expired = false;

    public function mount(string $token, ParentInvitationService $svc, AssessmentService $assessmentService): void
    {
        $this->token = $token;

        $inv = $svc->verifyToken($token);
        if (! $inv) { $this->expired = true; return; }
        $this->invitation = $inv;

        // attempt برای همه آزمون‌های parent ایجاد/بازیابی کن
        foreach ($this->allAssessments() as $a) {
            $assessmentService->startOrResumeParent($inv, $a);
        }
    }

    /** همه آزمون‌های parent به ترتیب */
    private function allAssessments(): \Illuminate\Support\Collection
    {
        return Assessment::active()
            ->where('audience', Assessment::AUDIENCE_PARENT)
            ->ordered()
            ->get()
            ->unique('slug')
            ->values();
    }

    /**
     * ثبت همه پاسخ‌ها یکجا — هر جواب می‌رود سر attempt متناسب با assessment خودش
     */
    public function submitAll(array $payload, AssessmentService $assessmentService): void
    {
        if ($this->expired || ! $this->invitation) return;

        $assessments = $this->allAssessments();

        // map: question_id => [assessment, attempt]
        $questionMap = [];
        foreach ($assessments as $assessment) {
            $attempt = ParentAssessmentAttempt::where('invitation_id', $this->invitation->id)
                ->where('assessment_id', $assessment->id)
                ->first();
            if (! $attempt) continue;

            $questions = AssessmentQuestion::where('assessment_id', $assessment->id)
                ->where('is_active', true)
                ->with('options')
                ->get();

            foreach ($questions as $q) {
                $questionMap[$q->id] = ['assessment' => $assessment, 'attempt' => $attempt, 'question' => $q];
            }
        }

        // ذخیره هر پاسخ
        foreach ($payload as $item) {
            $qId  = (int) ($item['qId'] ?? 0);
            $info = $questionMap[$qId] ?? null;
            if (! $info) continue;

            $p = $this->buildPayload(
                $info['question'],
                isset($item['optionId']) ? (int)$item['optionId'] : null,
                $item['value'] ?? null,
                $item['multi'] ?? null,
            );
            if ($p === null) continue;

            $assessmentService->saveParentAnswer($info['attempt'], $info['question'], $p);
        }

        // بررسی تکمیل هر attempt
        foreach ($assessments as $assessment) {
            $attempt = ParentAssessmentAttempt::where('invitation_id', $this->invitation->id)
                ->where('assessment_id', $assessment->id)
                ->first();
            if (! $attempt) continue;

            $attempt->refresh();
            $total = $assessment->questions()->where('is_active', true)->count();
            if ($attempt->answered_count >= $total) {
                $assessmentService->completeParent($attempt);
            }
        }

        $this->invitation->refresh();
        $this->redirect(
            route('client.parent.assessment.thank-you', ['token' => $this->token]),
            navigate: true,
        );
    }

    private function buildPayload(AssessmentQuestion $question, ?int $optionId, ?string $value, ?array $multi): ?array
    {
        if ($question->isMultiSelect()) {
            $ids = array_values(array_filter(array_map('intval', $multi ?? [])));
            if (empty($ids)) return null;
            $validIds = $question->options->pluck('id')->all();
            $ids = array_values(array_intersect($ids, $validIds));
            if (empty($ids)) return null;
            return ['selected_options' => $ids, 'selected_option_id' => null, 'free_value' => null];
        }
        if ($question->type === AssessmentQuestion::TYPE_LIKERT5) {
            $v = (string)($value ?? '');
            if (! in_array($v, ['1','2','3','4','5'], true)) return null;
            $option = $question->options->firstWhere('value', $v);
            return ['selected_option_id' => $option?->id, 'selected_options' => null, 'free_value' => $v];
        }
        if (! $optionId || $optionId <= 0) return null;
        if (! $question->options->pluck('id')->contains($optionId)) return null;
        return ['selected_option_id' => $optionId, 'selected_options' => null, 'free_value' => null];
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        if ($this->expired || ! $this->invitation) {
            return view('livewire.client.parent-assessment.expired')
                ->layout('layouts.client.app-auth');
        }

        $assessments = $this->allAssessments();

        // ─── همه سوالات همه آزمون‌ها را flat کن با شماره‌گذاری یکپارچه ───
        $allQuestions = collect();
        foreach ($assessments as $assessment) {
            $attempt = ParentAssessmentAttempt::where('invitation_id', $this->invitation->id)
                ->where('assessment_id', $assessment->id)
                ->first();

            $questions = AssessmentQuestion::where('assessment_id', $assessment->id)
                ->where('is_active', true)
                ->with(['options' => fn($qq) => $qq->orderBy('order')])
                ->orderBy('order')
                ->get();

            foreach ($questions as $q) {
                // محافظت در برابر گزینه‌های تکراری (در صورت ناسازگاری داده):
                // بر اساس متنِ گزینه یکتا می‌کنیم تا حتی اگر رکوردهای تکراری
                // با value متفاوت در دیتابیس باشند، فقط یک‌بار نمایش داده شود.
                // چون options بر اساس order مرتب است، نسخه‌ی اصلی (کم‌ترین order) نگه داشته می‌شود.
                //

                $uniqueOpts = $q->options
                    ->sortBy('order')
                    ->unique(fn($o) => trim((string) $o->label_fa))
                    ->values();
                $q->setRelation('options', $uniqueOpts);

                // به هر سوال assessment_id و attempt_id اضافه کن
                $q->setAttribute('_assessment_id',   $assessment->id);
                $q->setAttribute('_assessment_name', $assessment->name_fa);
                $q->setAttribute('_attempt_id',      $attempt?->id);
                $allQuestions->push($q);
            }
        }

        // پاسخ‌های قبلی
        $attemptIds = ParentAssessmentAttempt::where('invitation_id', $this->invitation->id)->pluck('id');
        $existingAnswers = ParentAssessmentAnswer::whereIn('attempt_id', $attemptIds)
            ->get()->keyBy('question_id');

        $preAnswers = [];
        foreach ($allQuestions as $q) {
            $ans = $existingAnswers->get($q->id);
            if (! $ans) continue;
            if ($q->isMultiSelect()) {
                $preAnswers[$q->id] = ['multi' => $ans->selected_options ?? [], 'optionId' => ($ans->selected_options[0] ?? null), 'value' => null];
            } elseif ($q->type === AssessmentQuestion::TYPE_LIKERT5) {
                $preAnswers[$q->id] = ['optionId' => $ans->selected_option_id, 'value' => $ans->free_value, 'multi' => null];
            } else {
                $preAnswers[$q->id] = ['optionId' => $ans->selected_option_id, 'value' => null, 'multi' => null];
            }
        }

        return view('livewire.client.parent-assessment.take', [
            'questions'  => $allQuestions,
            'preAnswers' => $preAnswers,
            'totalCount' => $allQuestions->count(),
        ])->layout('layouts.client.app-auth');
    }
}
