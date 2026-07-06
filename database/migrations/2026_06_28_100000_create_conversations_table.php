<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * مکالمه‌ی مستقیم دانش‌آموز ↔ مشاور تحصیلی.
 * هر دانش‌آموز حداکثر یک مکالمه دارد (با مشاورِ فعلی‌اش).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            // مشاور فعلی؛ اگر مشاور دانش‌آموز عوض شود به‌روزرسانی می‌شود (پیام‌ها می‌مانند).
            $table->foreignId('advisor_id')->nullable()->constrained('admins')->nullOnDelete();

            $table->timestamp('last_message_at')->nullable();

            // مبنای شمارش نخوانده + تیک خوانده‌شدن
            $table->timestamp('student_last_read_at')->nullable();
            $table->timestamp('advisor_last_read_at')->nullable();

            // وضعیت آنلاین / آخرین بازدید
            $table->timestamp('student_last_seen_at')->nullable();
            $table->timestamp('advisor_last_seen_at')->nullable();

            // نشانگر «در حال تایپ»
            $table->timestamp('student_typing_at')->nullable();
            $table->timestamp('advisor_typing_at')->nullable();

            $table->timestamps();

            $table->unique('student_id');
            $table->index('advisor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
