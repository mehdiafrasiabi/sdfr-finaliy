<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('study_part_sessions', function (Blueprint $table) {
            // فلگ زودتر تمام کردن پارت توسط دانش‌آموز
            $table->boolean('is_early_finish')->default(false)->after('is_completed');

            // مدت زمان واقعی سپری‌شده در فاز اضافه بر مشاور (به ثانیه)
            $table->unsignedInteger('extra_seconds')->default(0)->after('completed_at');

            // مدت زمان اضافی برنامه‌ریزی‌شده توسط دانش‌آموز (انتخاب در مودال، حداکثر ۳ ساعت)
            $table->unsignedInteger('extra_target_seconds')->nullable()->after('extra_seconds');

            // زمان شروع فاز اضافه بر مشاور
            $table->timestamp('extra_started_at')->nullable()->after('extra_target_seconds');

            // زمان پایان فاز اضافه بر مشاور
            $table->timestamp('extra_ended_at')->nullable()->after('extra_started_at');

            $table->index('is_early_finish');
        });
    }

    public function down(): void
    {
        Schema::table('study_part_sessions', function (Blueprint $table) {
            $table->dropIndex(['is_early_finish']);
            $table->dropColumn([
                'is_early_finish',
                'extra_seconds',
                'extra_target_seconds',
                'extra_started_at',
                'extra_ended_at',
            ]);
        });
    }
};
