<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * تماس‌های «مشاور جذب یک هفته آزمایشی» — جریان یکپارچهٔ جدید.
 * مراحل بر مبنای روزهای هفتهٔ آزمایشی: روز اول (ثبت‌نام)، روز سوم، روز هفتم + تماس اضطراری.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trial_acquisition_calls', function (Blueprint $table) {
            $table->id();

            $table->foreignId('trial_week_id')->constrained('trial_weeks')->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();

            // day1 | day3 | day7 | emergency
            $table->string('stage', 20);
            // شمارهٔ تلاش در همان مرحله (۱ یا ۲)
            $table->unsignedTinyInteger('attempt_number')->default(1);
            // آیا تماس پاسخ داده شد؟
            $table->boolean('answered')->default(false);

            // با چه شخصی صحبت شد: father|mother|student|other
            $table->string('spoke_with', 20)->nullable();
            // پیگیر آموزشی انتخاب‌شده (در روز اول): father|mother
            $table->string('educational_follow_up', 20)->nullable();

            // چک‌لیست کارهای انجام‌شدهٔ هر مرحله (خوش‌آمد، گزارش به اولیا، صحبت مالی و ...)
            $table->json('checklist')->nullable();

            // احتمال ثبت‌نام (از مرحلهٔ روز سوم به بعد) و توضیحات آن
            $table->unsignedTinyInteger('registration_probability')->nullable();
            $table->text('probability_note')->nullable();
            // تأیید قطعی احتمال ثبت‌نام (در روز هفتم)
            $table->boolean('is_definitive')->default(false);

            // علت تماس اضطراری
            $table->text('emergency_reason')->nullable();

            // یادآور: اگر دانش‌آموز گفت بعداً خبر بدهید
            $table->dateTime('reminder_at')->nullable();

            $table->text('notes')->nullable();
            $table->timestamp('called_at');
            $table->timestamps();

            $table->index('trial_week_id');
            $table->index('admin_id');
            $table->index('stage');
            $table->index('reminder_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trial_acquisition_calls');
    }
};
