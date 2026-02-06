<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // جدول زمان‌بندی پارت‌های مطالعه - جداسازی از study_part_sessions
        Schema::create('sps_timings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_part_session_id')->constrained('study_part_sessions')->cascadeOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->integer('duration_seconds')->default(0);
            $table->integer('planned_seconds')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->index('study_part_session_id');
        });

        // انتقال داده‌های موجود
        DB::statement("
            INSERT INTO sps_timings (study_part_session_id, started_at, ended_at, duration_seconds, planned_seconds, completed_at)
            SELECT id, started_at, ended_at, duration_seconds, planned_seconds, completed_at
            FROM study_part_sessions
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('sps_timings');
    }
};
