<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\AssessmentQuestion;
use App\Models\StudentAssessmentAnswer;
use App\Models\StudentAssessmentAttempt;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * مغز هدایت Journey سه‌مرحله‌ای آزمون‌های روان‌شناختی:
 *   MBTI (1 آزمون) → VARK (1 آزمون) → Mindset (9 آزمون پشت‌سر‌هم)
 * سپس صفحه‌ی ProfileReview برای تأیید پروفایل توسط دانش‌آموز.
 */
class AssessmentJourneyService
{
    public const STAGE_MBTI    = 'mbti';
    public const STAGE_VARK    = 'vark';
    public const STAGE_MINDSET = 'mindset';

    public const STAGES = [
        self::STAGE_MBTI,
        self::STAGE_VARK,
        self::STAGE_MINDSET,
    ];

    /**
     * تصمیم می‌گیرد کاربر بعد از ورود به journey به کجا برود.
     * یکی از مقادیر برگشتی:
     *   ['route' => 'welcome', 'stage' => 'mbti'|'vark'|'mindset']
     *   ['route' => 'review']
     *   ['route' => 'waiting']
     */
    public function determineNextStep(User $user): array
    {
        $trial = $user->trialWeek;

        foreach (self::STAGES as $stage) {
            if (! $this->isStageCompleted($user, $stage)) {
                return ['route' => 'welcome', 'stage' => $stage];
            }
        }

        if ($trial && ! $trial->profile_acknowledged_at) {
            return ['route' => 'review'];
        }

        return ['route' => 'waiting'];
    }

    /**
     * وضعیت یک stage برای کاربر:
     *   total_questions, answered, percent, is_first_entry, is_completed
     */
    public function getStageStatus(User $user, string $stage): array
    {
        $assessments = $this->getStageAssessments($stage);

        $assessmentIds = $assessments->pluck('id')->all();

        $totalQuestions = AssessmentQuestion::whereIn('assessment_id', $assessmentIds)
            ->where('is_active', true)
            ->count();

        $attempts = StudentAssessmentAttempt::where('user_id', $user->id)
            ->whereIn('assessment_id', $assessmentIds)
            ->get();

        $answered = (int) StudentAssessmentAnswer::whereIn('attempt_id', $attempts->pluck('id'))->count();

        $percent = $totalQuestions > 0 ? (int) round(($answered / $totalQuestions) * 100) : 0;

        return [
            'stage'           => $stage,
            'total_questions' => $totalQuestions,
            'answered'        => $answered,
            'percent'         => min(100, $percent),
            'is_first_entry'  => $answered === 0,
            'is_completed'    => $totalQuestions > 0 && $answered >= $totalQuestions,
        ];
    }

    /**
     * آزمون‌های stage به ترتیب display_order.
     */
    public function getStageAssessments(string $stage): Collection
    {
        return Assessment::active()
            ->forStudent()
            ->where('stage', $stage)
            ->ordered()
            ->get();
    }

    /**
     * سوال بعدی برای نمایش در ویزارد stage. اولین سوال بی‌پاسخ در میان همه‌ی
     * assessmentهای این stage بر اساس ترتیب آزمون و سپس ترتیب سوال.
     * خروجی: ['assessment' => Assessment, 'question' => AssessmentQuestion] یا null
     */
    public function nextQuestionInStage(User $user, string $stage): ?array
    {
        $assessments = $this->getStageAssessments($stage);

        foreach ($assessments as $assessment) {
            $attempt = StudentAssessmentAttempt::where('user_id', $user->id)
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
                return ['assessment' => $assessment, 'question' => $question];
            }
        }

        return null;
    }

    public function isStageCompleted(User $user, string $stage): bool
    {
        $assessmentIds = $this->getStageAssessments($stage)->pluck('id')->all();

        if (empty($assessmentIds)) {
            return true;
        }

        $completedCount = StudentAssessmentAttempt::where('user_id', $user->id)
            ->whereIn('assessment_id', $assessmentIds)
            ->where('status', StudentAssessmentAttempt::STATUS_COMPLETED)
            ->count();

        return $completedCount >= count($assessmentIds);
    }

    /**
     * عنوان فارسی stage برای نمایش در welcome/wizard.
     */
    public function stageTitle(string $stage): string
    {
        return match ($stage) {
            self::STAGE_MBTI    => 'تست شخصیت‌شناسی (MBTI)',
            self::STAGE_VARK    => 'تست سبک یادگیری (VARK)',
            self::STAGE_MINDSET => 'تست ذهنیت تحصیلی',
            default             => $stage,
        };
    }

    /**
     * توضیح کوتاه stage برای صفحه‌ی welcome.
     */
    public function stageDescription(string $stage): string
    {
        return match ($stage) {
            self::STAGE_MBTI    => 'این مرحله شامل ۶۴ سوال درباره‌ی شخصیت شماست. حدود ۱۰ دقیقه زمان می‌برد.',
            self::STAGE_VARK    => 'این مرحله شامل ۱۶ سوال درباره‌ی سبک یادگیری شماست. حدود ۵ دقیقه زمان می‌برد.',
            self::STAGE_MINDSET => 'این مرحله شامل چند بخش کوتاه درباره‌ی ذهنیت و انگیزه‌ی تحصیلی شماست. حدود ۱۵ دقیقه زمان می‌برد.',
            default             => '',
        };
    }
}
