<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * حذفِ جدولِ قدیمیِ درخواست‌های جابجایی.
 * جریانِ جابجایی از نو طراحی شده: جابجاییِ سلف‌سرویسِ دانش‌آموز که مستقیماً
 * یک «جلسه‌ی جبرانی» (advising_sessions.is_makeup) می‌سازد.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('session_reschedule_requests');
    }

    public function down(): void
    {
        // بازگردانی نمی‌شود؛ این جریان به‌کلی حذف شده است.
    }
};
