<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // جدول زمان‌بندی جلسات مطالعه - جداسازی از study_sessions
        Schema::create('ss_timings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_session_id')->constrained('study_sessions')->cascadeOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->integer('planned_seconds')->nullable();

            $table->index('study_session_id');
        });

        // انتقال داده‌های موجود
        DB::statement("
            INSERT INTO ss_timings (study_session_id, started_at, ended_at, duration_seconds, planned_seconds)
            SELECT id, started_at, ended_at, duration_seconds, planned_seconds
            FROM study_sessions
            WHERE started_at IS NOT NULL OR ended_at IS NOT NULL
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('ss_timings');
    }
};
