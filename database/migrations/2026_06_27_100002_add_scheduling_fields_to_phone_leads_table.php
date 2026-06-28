<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * زمان‌بندی تماس بعدی و علت خاکستری‌شدن شماره.
 * next_call_at: زمانی که شماره باید دوباره تماس گرفته شود (same-day یا next-day).
 * grey_reason: علت خاکستری‌شدن (no_answer | rejected | off | wrong).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('phone_leads', function (Blueprint $table) {
            $table->string('grey_reason', 20)->nullable()->after('last_outcome');
            $table->dateTime('next_call_at')->nullable()->after('grey_reason');
            $table->index('next_call_at');
        });
    }

    public function down(): void
    {
        Schema::table('phone_leads', function (Blueprint $table) {
            $table->dropIndex(['next_call_at']);
            $table->dropColumn(['grey_reason', 'next_call_at']);
        });
    }
};
