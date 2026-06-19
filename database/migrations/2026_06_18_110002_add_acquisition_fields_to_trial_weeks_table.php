<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * فیلدهای denormalized روی trial_weeks برای نمایش سریع وضعیت جذب
 * (احتمال ثبت‌نام، تأیید قطعی، یادآور، پیگیر آموزشی).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trial_weeks', function (Blueprint $table) {
            $table->unsignedTinyInteger('acq_probability')->nullable()->after('daily_study_hours');
            $table->text('acq_probability_note')->nullable()->after('acq_probability');
            $table->boolean('acq_confirmed')->default(false)->after('acq_probability_note');
            $table->dateTime('acq_reminder_at')->nullable()->after('acq_confirmed');
            // پیگیر آموزشی نهایی (پدر/مادر) که در روز اول انتخاب شده
            $table->string('acq_follow_up', 20)->nullable()->after('acq_reminder_at');
        });
    }

    public function down(): void
    {
        Schema::table('trial_weeks', function (Blueprint $table) {
            $table->dropColumn([
                'acq_probability',
                'acq_probability_note',
                'acq_confirmed',
                'acq_reminder_at',
                'acq_follow_up',
            ]);
        });
    }
};
