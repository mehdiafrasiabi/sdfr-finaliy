<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * کارنامهٔ ماهانه‌ای که مشاور پر می‌کند:
     *  - دو نمرهٔ مجزا از ۲۰: فعالیت کلاسی و امتحان
     *  - نظر دبیر (اختیاری)
     *  - تفکیک بر اساس ماه شمسی (مثل «1404-07») تا هر ماه نمرهٔ مخصوص خودش را داشته باشد
     */
    public function up(): void
    {
        Schema::table('school_student_grades', function (Blueprint $table) {
            $table->decimal('class_activity', 5, 2)->nullable()->after('cc_chapter_id');
            $table->decimal('exam', 5, 2)->nullable()->after('class_activity');
            $table->text('teacher_comment')->nullable()->after('note');
            $table->string('jalali_month', 7)->nullable()->index()->after('recorded_at');

            // یک ردیف نمره به‌ازای هر دانش‌آموز/درس/ماه.
            $table->unique(['student_id', 'cc_subject_id', 'jalali_month'], 'ssg_student_subject_month_unique');
        });
    }

    public function down(): void
    {
        Schema::table('school_student_grades', function (Blueprint $table) {
            $table->dropUnique('ssg_student_subject_month_unique');
            $table->dropColumn(['class_activity', 'exam', 'teacher_comment', 'jalali_month']);
        });
    }
};
