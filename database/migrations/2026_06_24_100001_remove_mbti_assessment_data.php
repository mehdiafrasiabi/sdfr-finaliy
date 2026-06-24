<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * حذف کامل دادهٔ MBTI از دیتابیس (تست، سوال‌ها، گزینه‌ها، تلاش‌ها و پاسخ‌ها).
 * MBTI از فلوی آزمون حذف شده است. این مهاجرت idempotent است.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            $ids = DB::table('assessments')
                ->where('slug', 'mbti')
                ->orWhere('kind', 'mbti')
                ->pluck('id')
                ->all();

            if (empty($ids)) {
                return;
            }

            $questionIds = DB::table('assessment_questions')
                ->whereIn('assessment_id', $ids)
                ->pluck('id')
                ->all();

            $attemptIds = DB::table('student_assessment_attempts')
                ->whereIn('assessment_id', $ids)
                ->pluck('id')
                ->all();

            if (! empty($attemptIds)) {
                DB::table('student_assessment_answers')->whereIn('attempt_id', $attemptIds)->delete();
            }
            DB::table('student_assessment_attempts')->whereIn('assessment_id', $ids)->delete();

            if (! empty($questionIds)) {
                DB::table('assessment_question_options')->whereIn('question_id', $questionIds)->delete();
            }
            DB::table('assessment_questions')->whereIn('assessment_id', $ids)->delete();

            DB::table('assessments')->whereIn('id', $ids)->delete();
        });
    }

    public function down(): void
    {
        // بازگشت‌ناپذیر — دادهٔ MBTI عمداً حذف شده است. در صورت نیاز، AssessmentSeeder را اجرا کنید.
    }
};
