<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * فلگِ «نهایی‌شده» برای جلسات مشاوره.
 * جلساتی که مشاور در جریانِ تماسِ یک‌روز‌قبل ذخیره می‌کند تا قبل از «ثبت نهایی»
 * finalized=false هستند و به دانش‌آموز نمایش داده نمی‌شوند. مقدارِ پیش‌فرضِ true
 * تا جلساتِ قبلی بدونِ تغییر همچنان نمایش داده شوند.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('advising_sessions', function (Blueprint $table) {
            $table->boolean('finalized')->default(true)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('advising_sessions', function (Blueprint $table) {
            $table->dropColumn('finalized');
        });
    }
};
