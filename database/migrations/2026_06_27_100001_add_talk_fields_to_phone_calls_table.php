<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * فیلدهای فلوی تماس زنده: لحظهٔ پاسخ‌گویی و مدت مکالمه (ثانیه).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('phone_calls', function (Blueprint $table) {
            // لحظه‌ای که مخاطب پاسخ داد (دکمهٔ «پاسخ کاربر»)
            $table->dateTime('answered_at')->nullable()->after('connected');
            // مدت مکالمه برحسب ثانیه (از زدن «پاسخ کاربر» تا «اتمام مکالمه»)
            $table->unsignedInteger('talk_duration_seconds')->nullable()->after('answered_at');
        });
    }

    public function down(): void
    {
        Schema::table('phone_calls', function (Blueprint $table) {
            $table->dropColumn(['answered_at', 'talk_duration_seconds']);
        });
    }
};
