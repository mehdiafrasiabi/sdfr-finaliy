<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1. Modify part_type ENUM to add new values
        DB::statement("ALTER TABLE program_parts MODIFY COLUMN part_type ENUM('test', 'descriptive', 'video', 'topic_exam', 'comprehensive_exam', 'exam_analysis') DEFAULT 'descriptive'");

        // 2. Create comprehensive exam days table (like rest days)
        Schema::create('weekly_program_exam_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weekly_program_id')->constrained('weekly_programs')->onDelete('cascade');
            $table->tinyInteger('day_index');
            $table->timestamps();
            $table->unique(['weekly_program_id', 'day_index']);
        });

        // 3. Add cc_subject_id and cc_chapter_id to pre-session exams
        Schema::table('advising_pre_session_exams', function (Blueprint $table) {
            $table->foreignId('cc_subject_id')->nullable()->after('subject')->constrained('cc_subjects')->onDelete('set null');
            $table->foreignId('cc_chapter_id')->nullable()->after('cc_subject_id')->constrained('cc_chapters')->onDelete('set null');
        });

        // 4. Add cc_subject_id to pre-session assignments
        Schema::table('advising_pre_session_assignments', function (Blueprint $table) {
            $table->foreignId('cc_subject_id')->nullable()->after('subject')->constrained('cc_subjects')->onDelete('set null');
        });

        // 5. Add cc_subject_id and cc_chapter_id to pre-session QAs
        Schema::table('advising_pre_session_qas', function (Blueprint $table) {
            $table->foreignId('cc_subject_id')->nullable()->after('subject')->constrained('cc_subjects')->onDelete('set null');
            $table->foreignId('cc_chapter_id')->nullable()->after('cc_subject_id')->constrained('cc_chapters')->onDelete('set null');
        });
    }

    public function down(): void
    {
        // Revert part_type ENUM
        DB::statement("ALTER TABLE program_parts MODIFY COLUMN part_type ENUM('test', 'descriptive', 'video') DEFAULT 'descriptive'");

        Schema::dropIfExists('weekly_program_exam_days');

        Schema::table('advising_pre_session_exams', function (Blueprint $table) {
            $table->dropForeign(['cc_subject_id']);
            $table->dropForeign(['cc_chapter_id']);
            $table->dropColumn(['cc_subject_id', 'cc_chapter_id']);
        });

        Schema::table('advising_pre_session_assignments', function (Blueprint $table) {
            $table->dropForeign(['cc_subject_id']);
            $table->dropColumn('cc_subject_id');
        });

        Schema::table('advising_pre_session_qas', function (Blueprint $table) {
            $table->dropForeign(['cc_subject_id']);
            $table->dropForeign(['cc_chapter_id']);
            $table->dropColumn(['cc_subject_id', 'cc_chapter_id']);
        });
    }
};
