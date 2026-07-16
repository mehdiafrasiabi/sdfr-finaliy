<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * حذف کامل آزمون حذف‌شده از مایندت.
 * این آزمون دیگر نباید در فرم، پاسخ‌های قبلی، یا تحلیل‌ها باقی بماند.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            $ids = DB::table('assessments')
                ->where('slug', 'mood-state')
                ->pluck('id')
                ->all();

            if (empty($ids)) {
                return;
            }

            $questionIds = DB::table('assessment_questions')
                ->whereIn('assessment_id', $ids)
                ->pluck('id')
                ->all();

            $studentAttemptIds = DB::table('student_assessment_attempts')
                ->whereIn('assessment_id', $ids)
                ->pluck('id')
                ->all();

            if (! empty($studentAttemptIds)) {
                DB::table('student_assessment_answers')
                    ->whereIn('attempt_id', $studentAttemptIds)
                    ->delete();
            }

            DB::table('student_assessment_attempts')
                ->whereIn('assessment_id', $ids)
                ->delete();

            if (! empty($questionIds)) {
                DB::table('assessment_question_options')
                    ->whereIn('question_id', $questionIds)
                    ->delete();
            }

            DB::table('assessment_questions')
                ->whereIn('assessment_id', $ids)
                ->delete();

            DB::table('assessments')
                ->whereIn('id', $ids)
                ->delete();
        });
    }

    public function down(): void
    {
        // بازگشت‌ناپذیر؛ در صورت نیاز باید نسخهٔ قدیمی AssessmentSeeder برگردانده شود.
    }
};
