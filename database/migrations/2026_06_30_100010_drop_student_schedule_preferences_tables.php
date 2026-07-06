<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * حذفِ جدول‌های قدیمیِ «ترجیحِ برنامه» دانش‌آموز.
 * جریانِ تعیینِ وقت/مشاور از نو طراحی شده (advisor_selections + students.session_day).
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('student_schedule_preference_times');
        Schema::dropIfExists('student_schedule_preferences');
    }

    public function down(): void
    {
        // بازگردانی نمی‌شود؛ این جریان به‌کلی حذف شده است.
    }
};
