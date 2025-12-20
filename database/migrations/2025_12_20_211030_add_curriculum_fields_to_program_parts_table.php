<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('program_parts', function (Blueprint $table) {
            $table->foreignId('education_level_id')->nullable()->after('weekly_program_id')->constrained()->nullOnDelete();
            $table->foreignId('cc_grade_id')->nullable()->after('education_level_id')->constrained('cc_grades')->nullOnDelete();
            $table->foreignId('cc_field_id')->nullable()->after('cc_grade_id')->constrained('cc_fields')->nullOnDelete();
            $table->foreignId('cc_subject_id')->nullable()->after('cc_field_id')->constrained('cc_subjects')->nullOnDelete();
            $table->foreignId('cc_chapter_id')->nullable()->after('cc_subject_id')->constrained('cc_chapters')->nullOnDelete();
            $table->foreignId('cc_topic_id')->nullable()->after('cc_chapter_id')->constrained('cc_topics')->nullOnDelete();
            $table->string('grade_label')->nullable()->after('grade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_parts', function (Blueprint $table) {
            $table->dropForeign(['education_level_id']);
            $table->dropForeign(['cc_grade_id']);
            $table->dropForeign(['cc_field_id']);
            $table->dropForeign(['cc_subject_id']);
            $table->dropForeign(['cc_chapter_id']);
            $table->dropForeign(['cc_topic_id']);
            $table->dropColumn([
                'education_level_id',
                'cc_grade_id',
                'cc_field_id',
                'cc_subject_id',
                'cc_chapter_id',
                'cc_topic_id',
                'grade_label',
            ]);
        });
    }
};
