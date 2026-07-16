<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('parent_assessment_answers');
        Schema::dropIfExists('parent_assessment_attempts');
        Schema::dropIfExists('parent_assessment_invitations');

        if (! Schema::hasTable('assessments')) {
            return;
        }

        $assessmentIds = DB::table('assessments')
            ->whereIn('slug', [
                'fear-of-parents-parent',
                'friends-influence-parent',
            ])
            ->pluck('id')
            ->all();

        if (empty($assessmentIds)) {
            return;
        }

        $questionIds = Schema::hasTable('assessment_questions')
            ? DB::table('assessment_questions')
                ->whereIn('assessment_id', $assessmentIds)
                ->pluck('id')
                ->all()
            : [];

        if (! empty($questionIds) && Schema::hasTable('assessment_question_options')) {
            DB::table('assessment_question_options')
                ->whereIn('question_id', $questionIds)
                ->delete();
        }

        if (Schema::hasTable('assessment_questions')) {
            DB::table('assessment_questions')
                ->whereIn('assessment_id', $assessmentIds)
                ->delete();
        }

        DB::table('assessments')
            ->whereIn('id', $assessmentIds)
            ->delete();
    }

    public function down(): void
    {
        // Parent assessment data was intentionally removed.
    }
};
