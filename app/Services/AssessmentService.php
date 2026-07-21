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
    private const REMOVED_STUDENT_ASSESSMENT_SLUGS = ['mood-state'];

    public function __construct(
        private AssessmentScoringService $scoring,
    ) {}

    /**
     * ترتیب مرحله‌ای آزمون‌های دانش‌آموز: مرحله ۱ فقط MBTI، مرحله ۲ «مایندست»
     * (همهٔ آزمون‌های دیگر شامل VARK و اختصاصی) به ترتیب display_order.
     *
     * @return \Illuminate\Support\Collection<int,Assessment>
     */
    public function studentAssessmentsInStageOrder(): \Illuminate\Support\Collection
    {
        // ترتیب کاملاً داینامیک بر اساس display_order — بدون هیچ تست هاردکدی.
        return Assessment::active()
            ->forStudent()
            ->whereNotIn('slug', self::REMOVED_STUDENT_ASSESSMENT_SLUGS)
            ->ordered()
            ->get()
            ->values();
    }

    /**
     * نگه‌داشته‌شده برای سازگاری — دیگر مرحله‌بندی هاردکد نداریم.
     */
    public function stageNumberFor(Assessment $assessment): int
    {
        return 1;
    }

    /**
     * اولین آزمون دانش‌آموزیِ فعال که هنوز تکمیل نشده و حداقل یک سوال فعال دارد.
     * تست‌های بی‌سوال/غیرفعال‌شده نادیده گرفته می‌شوند تا کاربر هیچ‌وقت گیر نکند.
     * اگر همه تکمیل شده‌اند null برمی‌گرداند.
     */
    public function nextStudentAssessment(User $user): ?Assessment
    {
        $completedIds = StudentAssessmentAttempt::where('user_id', $user->id)
            ->where('status', StudentAssessmentAttempt::STATUS_COMPLETED)
            ->pluck('assessment_id')
            ->all();

        return $this->studentAssessmentsInStageOrder()
            ->reject(fn (Assessment $a) => in_array($a->id, $completedIds, true))
            ->first(fn (Assessment $a) => $a->questions()->where('is_active', true)->exists());
    }

    /**
     * یا یک attempt در حال انجام را برمی‌گرداند، یا attempt جدید می‌سازد.
     */
    public function startOrResume(User $user, Assessment $assessment): StudentAssessmentAttempt
    {
        return DB::transaction(function () use ($user, $assessment) {
            $attempt = StudentAssessmentAttempt::updateOrCreate(
                [
                    'user_id'       => $user->id,
                    'assessment_id' => $assessment->id,
                ],
                [
                    'student_id'             => $this->resolveStudentId($user),
                    'status'                 => StudentAssessmentAttempt::STATUS_IN_PROGRESS,
                    'started_at'             => now(),
                    'completed_at'           => null,
                    'computed_result'        => null,
                    'current_question_order' => 1,
                    'answered_count'         => 0,
                ]
            );

            // C10: اگر تلاش قبلاً انجام و تکمیل شده بود، پاسخ‌های قبلی حذف می‌شوند
            // تا اطمینان حاصل شود که تلاشِ جدید از صفر شروع می‌شود.
            if ($attempt->wasRecentlyCreated === false) {
                $attempt->answers()->delete();
                $this->refreshProgress($attempt);
            }


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
            // attempt قبلاً تکمیل شده (مثلاً کلیک دوبار/ریس) — به‌جای پرتاب خطا،
            // بی‌سروصدا پاسخ موجود را برمی‌گردانیم تا UI به مرحله بعد هدایت شود.
            return StudentAssessmentAnswer::firstOrNew([
                'attempt_id'  => $attempt->id,
                'question_id' => $question->id,
            ]);
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
            $trial->refresh();
            // تخصیص «مشاور جذب» در صفحهٔ انتظار (waiting-for-supporter) به‌صورت خودکار انجام می‌شود.
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
