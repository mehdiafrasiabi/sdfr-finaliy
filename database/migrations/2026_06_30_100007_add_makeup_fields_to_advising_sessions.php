<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * فیلدهای «جلسه‌ی جبرانی» روی جلسات مشاوره.
 * makeup_reason: student_reschedule (جابجایی توسط دانش‌آموز) | advisor_leave (مرخصی مشاور).
 * activation_date به nullable تغییر می‌کند تا جبرانی‌هایِ ناشی از مرخصی که هنوز روزشان
 * توسط مشاور تعیین نشده، بدون تاریخ نگه داشته شوند.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('advising_sessions', function (Blueprint $table) {
            $table->boolean('is_makeup')->default(false)->after('finalized');
            $table->string('makeup_reason')->nullable()->after('is_makeup');
            $table->unsignedBigInteger('source_session_id')->nullable()->after('makeup_reason');
            $table->date('activation_date')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('advising_sessions', function (Blueprint $table) {
            $table->dropColumn(['is_makeup', 'makeup_reason', 'source_session_id']);
        });
    }
};
