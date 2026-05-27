<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\AssessmentQuestion;
use App\Models\Student;
use App\Models\StudentAssessmentAnswer;
use App\Models\StudentAssessmentAttempt;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AssessmentService
{
    public function __construct(private AssessmentScoringService $scoring) {}

    /**
     * یا یک attempt در حال انجام را برمی‌گرداند، یا attempt جدید می‌سازد.
     */
    public function startOrResume(User $user, Assessment $assessment): StudentAssessmentAttempt
    {
        return DB::transaction(function () use ($user, $assessment) {
            $attempt = StudentAssessmentAttempt::firstOrCreate(
                [
                    'user_id'       => $user->id,
                    'assessment_id' => $assessment->id,
                ],
                [
                    'student_id'             => $this->resolveStudentId($user),
                    'status'                 => StudentAssessmentAttempt::STATUS_IN_PROGRESS,
                    'started_at'             => now(),
                    'current_question_order' => 1,
                    'answered_count'         => 0,
                ]
            );

            return $attempt;
        });
    }

    /**
     * ذخیره/به‌روزرسانی پاسخ کاربر برای یک سوال. پیشروی cursor به اولین سوال بی‌پاسخ.
     *
     * $payload:
     *   - selected_option_id (int)  — برای پاسخ تک‌گزینه‌ای
     *   - selected_options   (int[]) — برای VARK چندگزینه‌ای
     *   - free_value         (string) — مقدار raw اختیاری
     */
    public function saveAnswer(
        StudentAssessmentAttempt $attempt,
        AssessmentQuestion $question,
        array $payload
    ): StudentAssessmentAnswer {
        if ($attempt->isCompleted()) {
            // attempt قبلاً تکمیل شده — ویرایش پاسخ مجاز نیست
            throw new \LogicException('این آزمون قبلاً تکمیل شده است.');
        }

        if ($question->assessment_id !== $attempt->assessment_id) {
            throw new \InvalidArgumentException('سوال متعلق به آزمون این تلاش نیست.');
        }

        return DB::transaction(function () use ($attempt, $question, $payload) {
            $answer = StudentAssessmentAnswer::updateOrCreate(
                [
                    'attempt_id'  => $attempt->id,
                    'question_id' => $question->id,
                ],
                [
                    'selected_option_id' => $payload['selected_option_id'] ?? null,
                    'selected_options'   => $payload['selected_options']   ?? null,
                    'free_value'         => $payload['free_value']         ?? null,
                    'answered_at'        => now(),
                ]
            );

            $this->refreshProgress($attempt);

            return $answer;
        });
    }

    /**
     * علامت‌گذاری attempt به‌عنوان تکمیل‌شده + محاسبه‌ی نمره (MBTI / VARK).
     */
    public function complete(StudentAssessmentAttempt $attempt): void
    {
        if ($attempt->isCompleted()) {
            return;
        }

        DB::transaction(function () use ($attempt) {
            $attempt->refresh()->loadMissing('assessment');

            $totalActive = $attempt->assessment->questions()->where('is_active', true)->count();
            $answeredActive = $attempt->answers()
                ->whereIn('question_id', $attempt->assessment->questions()
                    ->where('is_active', true)
                    ->pluck('id'))
                ->count();

            if ($answeredActive < $totalActive) {
                throw new \LogicException('تمام سوالات پاسخ داده نشده‌اند.');
            }

            $result = $this->scoring->score($attempt);

            $attempt->update([
                'status'          => StudentAssessmentAttempt::STATUS_COMPLETED,
                'completed_at'    => now(),
                'computed_result' => $result,
            ]);
        });
    }

    /**
     * سوال بعدی برای نمایش — اولین سوال فعال که هنوز پاسخ نخورده.
     * اگر همه پاسخ خورده‌اند، null برمی‌گرداند.
     */
    public function nextQuestion(StudentAssessmentAttempt $attempt): ?AssessmentQuestion
    {
        $answeredIds = $attempt->answers()->pluck('question_id')->all();

        return AssessmentQuestion::where('assessment_id', $attempt->assessment_id)
            ->where('is_active', true)
            ->when(!empty($answeredIds), fn ($q) => $q->whereNotIn('id', $answeredIds))
            ->orderBy('order')
            ->first();
    }

    /**
     * اگر همه‌ی assessment های فعال (audience=student) برای کاربر completed باشند،
     * تاریخ تکمیل را روی trial_weeks ثبت می‌کند.
     */
    public function checkAllCompleted(User $user): bool
    {
        $done = $user->hasCompletedAllAssessments();
        if (!$done) {
            return false;
        }

        $trial = $user->trialWeek;
        if ($trial && !$trial->assessments_completed_at) {
            $trial->update(['assessments_completed_at' => Carbon::now()]);
        }

        return true;
    }

    private function refreshProgress(StudentAssessmentAttempt $attempt): void
    {
        $attempt->refresh();
        $answeredIds = $attempt->answers()->pluck('question_id')->all();
        $answeredCount = count($answeredIds);

        $nextOrder = AssessmentQuestion::where('assessment_id', $attempt->assessment_id)
            ->where('is_active', true)
            ->when(!empty($answeredIds), fn ($q) => $q->whereNotIn('id', $answeredIds))
            ->orderBy('order')
            ->value('order');

        $attempt->update([
            'answered_count'         => $answeredCount,
            'current_question_order' => $nextOrder ?? $attempt->current_question_order,
        ]);
    }

    private function resolveStudentId(User $user): int
    {
        $student = $user->student;
        if (!$student) {
            // در سناریوی trial، Student همزمان با TrialWeekService::start ساخته می‌شود.
            // در صورت نبود، fallback به ایجاد رکورد trial.
            $student = Student::firstOrCreate(
                ['user_id' => $user->id],
                ['is_trial' => true]
            );
        }
        return $student->id;
    }
}
