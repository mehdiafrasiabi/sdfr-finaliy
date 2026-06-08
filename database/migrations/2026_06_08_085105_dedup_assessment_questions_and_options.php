<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * پاک‌سازی داده‌های تکراری در سوالات و گزینه‌های آزمون‌ها.
 *
 * Seeder با updateOrCreate روی (assessment_id, order) کار می‌کند و چون روی این
 * ستون‌ها unique index وجود ندارد، رکوردهای تکراری قدیمی باقی می‌مانند. این باعث
 * می‌شد در آزمون والدین، گزینه‌ها چند بار نمایش داده شوند و تعداد سوالِ مورد انتظار
 * (برای تشخیص «تکمیل شدن») بیشتر از واقعیت شمرده شود؛ در نتیجه attempt هیچ‌وقت کامل
 * نمی‌شد.
 *
 * این migration:
 *   1) گزینه‌های تکراری هر سوال را بر اساس متن گزینه (label_fa) ادغام می‌کند
 *      (کم‌ترین id نگه داشته می‌شود) و پاسخ‌های ثبت‌شده را به گزینه‌ی نگه‌داشته‌شده
 *      ریمپ می‌کند.
 *   2) سوالات تکراری هر آزمون را بر اساس متن سوال (question_text_fa) ادغام می‌کند،
 *      پاسخ‌ها را به سوال نگه‌داشته‌شده منتقل می‌کند (با احترام به unique
 *      (attempt_id, question_id)) و گزینه‌ی انتخابی را بر اساس متن، روی گزینه‌ی
 *      معادلِ سوالِ نگه‌داشته‌شده تنظیم می‌کند.
 *
 * idempotent است: اجرای مجدد، چون دیگر تکراری وجود ندارد، بی‌اثر است.
 */
return new class extends Migration
{
    private array $answerTables = ['parent_assessment_answers', 'student_assessment_answers'];

    public function up(): void
    {
        DB::transaction(function () {
            // ابتدا سوالات تکراری را ادغام کن، سپس گزینه‌های تکراریِ سوالاتِ باقی‌مانده را.
            $this->dedupQuestions();
            $this->dedupOptions();
        });
    }

    public function down(): void
    {
        // داده‌ی حذف‌شده قابل بازگردانی نیست.
    }

    /** گزینه‌های تکراری هر سوال را بر اساس label_fa ادغام می‌کند. */
    private function dedupOptions(): void
    {
        $groups = DB::table('assessment_question_options')
            ->select('question_id', 'label_fa', DB::raw('MIN(id) as keep_id'))
            ->groupBy('question_id', 'label_fa')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($groups as $g) {
            $dupIds = DB::table('assessment_question_options')
                ->where('question_id', $g->question_id)
                ->where('label_fa', $g->label_fa)
                ->where('id', '!=', $g->keep_id)
                ->pluck('id')
                ->all();

            if (empty($dupIds)) {
                continue;
            }

            foreach ($this->answerTables as $tbl) {
                DB::table($tbl)
                    ->whereIn('selected_option_id', $dupIds)
                    ->update(['selected_option_id' => $g->keep_id]);
            }

            DB::table('assessment_question_options')->whereIn('id', $dupIds)->delete();
        }
    }

    /** سوالات تکراری هر آزمون را بر اساس question_text_fa ادغام می‌کند. */
    private function dedupQuestions(): void
    {
        $groups = DB::table('assessment_questions')
            ->select('assessment_id', 'question_text_fa', DB::raw('MIN(id) as keep_id'))
            ->groupBy('assessment_id', 'question_text_fa')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($groups as $g) {
            $keepId = (int) $g->keep_id;

            $dupIds = DB::table('assessment_questions')
                ->where('assessment_id', $g->assessment_id)
                ->where('question_text_fa', $g->question_text_fa)
                ->where('id', '!=', $keepId)
                ->pluck('id')
                ->all();

            if (empty($dupIds)) {
                continue;
            }

            // نگاشت متن گزینه → id گزینه‌ی سوالِ نگه‌داشته‌شده (برای ریمپ پاسخ‌ها)
            $keepOptionByLabel = DB::table('assessment_question_options')
                ->where('question_id', $keepId)
                ->pluck('id', 'label_fa'); // [label_fa => id]

            // نگاشت id گزینه‌ی سوالِ تکراری → متن آن (برای پیدا کردن معادل)
            $dupOptionLabels = DB::table('assessment_question_options')
                ->whereIn('question_id', $dupIds)
                ->pluck('label_fa', 'id'); // [option_id => label_fa]

            foreach ($this->answerTables as $tbl) {
                $rows = DB::table($tbl)
                    ->whereIn('question_id', $dupIds)
                    ->get(['id', 'attempt_id', 'selected_option_id']);

                foreach ($rows as $r) {
                    $existing = DB::table($tbl)
                        ->where('attempt_id', $r->attempt_id)
                        ->where('question_id', $keepId)
                        ->exists();

                    // اگر برای همین attempt قبلاً پاسخی روی سوالِ نگه‌داشته‌شده هست،
                    // پاسخ تکراری را حذف کن (رعایت unique(attempt_id, question_id)).
                    if ($existing) {
                        DB::table($tbl)->where('id', $r->id)->delete();
                        continue;
                    }

                    // گزینه‌ی انتخابی را به گزینه‌ی معادل در سوالِ نگه‌داشته‌شده ریمپ کن.
                    $newOptionId = $r->selected_option_id;
                    if ($r->selected_option_id !== null) {
                        $label = $dupOptionLabels[$r->selected_option_id] ?? null;
                        $newOptionId = ($label !== null && isset($keepOptionByLabel[$label]))
                            ? $keepOptionByLabel[$label]
                            : null;
                    }

                    DB::table($tbl)->where('id', $r->id)->update([
                        'question_id'        => $keepId,
                        'selected_option_id' => $newOptionId,
                    ]);
                }
            }

            // گزینه‌های سوالاتِ تکراری را حذف کن، سپس خودِ سوالاتِ تکراری را.
            DB::table('assessment_question_options')->whereIn('question_id', $dupIds)->delete();
            DB::table('assessment_questions')->whereIn('id', $dupIds)->delete();
        }
    }
};
