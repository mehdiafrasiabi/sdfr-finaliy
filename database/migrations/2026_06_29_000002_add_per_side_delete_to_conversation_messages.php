<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * حذفِ «فقط برای خودم» (per-side).
 * هر طرف می‌تواند یک پیام را فقط از نمای خودش پنهان کند، بدون اینکه برای طرف مقابل
 * تغییری ایجاد شود و بدون نمایش «این پیام حذف شد».
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conversation_messages', function (Blueprint $table) {
            $table->timestamp('student_deleted_at')->nullable()->after('deleted_at');
            $table->timestamp('advisor_deleted_at')->nullable()->after('student_deleted_at');
        });
    }

    public function down(): void
    {
        Schema::table('conversation_messages', function (Blueprint $table) {
            $table->dropColumn(['student_deleted_at', 'advisor_deleted_at']);
        });
    }
};
