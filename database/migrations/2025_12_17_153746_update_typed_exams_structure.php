<?php



use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;



return new class extends Migration

{

    /**

     * Run the migrations.

     * به‌روزرسانی ساختار آزمون‌ها

     */

    public function up(): void

    {

        // اضافه کردن رشته به آزمون

        Schema::table('typed_exams', function (Blueprint $table) {

            $table->foreignId('cc_field_id')->nullable()->after('title')->constrained('cc_fields')->nullOnDelete();

            $table->foreignId('cc_topic_id')->nullable()->after('cc_field_id')->constrained('cc_topics')->nullOnDelete();

            $table->softDeletes();

        });



        // به‌روزرسانی typed_exam_settings

        Schema::table('typed_exam_settings', function (Blueprint $table) {

            // حذف فیلدهای غیر ضروری

            $table->dropColumn(['result_visibility', 'answer_key_visibility', 'randomization_type', 'description']);

        });



        // به‌روزرسانی random_configs برای فیلتر سلسله مراتبی

        Schema::table('typed_exam_random_configs', function (Blueprint $table) {

            $table->foreignId('education_level_id')->nullable()->after('typed_exam_id')->constrained('education_levels')->nullOnDelete();

            $table->foreignId('cc_grade_id')->nullable()->after('education_level_id')->constrained('cc_grades')->nullOnDelete();

            $table->foreignId('cc_field_id')->nullable()->after('cc_grade_id')->constrained('cc_fields')->nullOnDelete();

            $table->foreignId('cc_subject_id')->nullable()->after('cc_field_id')->constrained('cc_subjects')->nullOnDelete();

            $table->foreignId('cc_chapter_id')->nullable()->after('cc_subject_id')->constrained('cc_chapters')->nullOnDelete();

            $table->foreignId('cc_topic_id')->nullable()->after('cc_chapter_id')->constrained('cc_topics')->nullOnDelete();

        });



        // اضافه کردن تنظیمات به assignment برای هر دانش‌آموز

        Schema::table('typed_exam_assignments', function (Blueprint $table) {

            $table->enum('result_visibility', ['after_exam_end', 'immediately', 'custom'])->default('after_exam_end')->after('status');

            $table->enum('answer_key_visibility', ['after_exam_end', 'immediately', 'custom'])->default('after_exam_end')->after('result_visibility');

            $table->timestamp('result_visible_at')->nullable()->after('answer_key_visibility');

            $table->timestamp('answer_key_visible_at')->nullable()->after('result_visible_at');

        });

    }



    /**

     * Reverse the migrations.

     */

    public function down(): void

    {

        Schema::table('typed_exam_assignments', function (Blueprint $table) {

            $table->dropColumn(['result_visibility', 'answer_key_visibility', 'result_visible_at', 'answer_key_visible_at']);

        });



        Schema::table('typed_exam_random_configs', function (Blueprint $table) {

            $table->dropForeign(['education_level_id']);

            $table->dropForeign(['cc_grade_id']);

            $table->dropForeign(['cc_field_id']);

            $table->dropForeign(['cc_subject_id']);

            $table->dropForeign(['cc_chapter_id']);

            $table->dropForeign(['cc_topic_id']);

            $table->dropColumn(['education_level_id', 'cc_grade_id', 'cc_field_id', 'cc_subject_id', 'cc_chapter_id', 'cc_topic_id']);

        });



        Schema::table('typed_exam_settings', function (Blueprint $table) {

            $table->enum('result_visibility', ['after_exam_end', 'immediately'])->default('after_exam_end');

            $table->enum('answer_key_visibility', ['after_exam_end', 'immediately'])->default('after_exam_end');

            $table->enum('randomization_type', ['none', 'questions_only', 'options_only', 'both'])->default('none');

            $table->text('description')->nullable();

        });



        Schema::table('typed_exams', function (Blueprint $table) {

            $table->dropSoftDeletes();

            $table->dropForeign(['cc_field_id']);

            $table->dropForeign(['cc_topic_id']);

            $table->dropColumn(['cc_field_id', 'cc_topic_id']);

        });

    }

};

