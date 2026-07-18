<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('exam_sample_questions', function (Blueprint $table) {
            $table->unsignedTinyInteger('exam_period_month')->nullable()->after('duration_minutes');
            $table->unsignedSmallInteger('exam_period_year')->nullable()->after('exam_period_month');
            $table->index(
                ['exam_planning_setting_id', 'exam_period_year', 'exam_period_month'],
                'exam_sample_questions_setting_period_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::table('exam_sample_questions', function (Blueprint $table) {
            $table->dropIndex('exam_sample_questions_setting_period_idx');
            $table->dropColumn(['exam_period_month', 'exam_period_year']);
        });
    }
};
