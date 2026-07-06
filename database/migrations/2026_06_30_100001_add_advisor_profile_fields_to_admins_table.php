<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * فیلدهای پروفایلِ مشاور تحصیلی + ظرفیتِ اختصاصیِ پذیرش دانش‌آموز.
 * این فیلدها فقط برای ادمین‌هایی با نقش «مشاور تحصیلی» معنا دارند ولی روی
 * همه‌ی ردیف‌های جدول admins به‌صورت nullable اضافه می‌شوند.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('education')->nullable()->after('picture');         // تحصیلات
            $table->string('field_of_study')->nullable()->after('education');  // رشته
            $table->text('bio')->nullable()->after('field_of_study');          // توضیحات
            $table->unsignedSmallInteger('student_capacity')->nullable()->after('bio'); // ظرفیت اختصاصی (در صورت null از مقدار سراسری استفاده می‌شود)
        });
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['education', 'field_of_study', 'bio', 'student_capacity']);
        });
    }
};
