<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('study_sessions', function (Blueprint $table) {
            $table->id();

            // ارتباط با دانش‌آموز
            $table->foreignId('student_id')->constrained()->onDelete('cascade');

            // زمان شروع و پایان
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();

            // مدت واقعی و زمان برنامه‌ریزی شده
            $table->integer('duration_seconds')->nullable();
            $table->integer('planned_seconds')->nullable();

            // اختیاری: یادداشت/موضوع مطالعه
            $table->string('note')->nullable();

            $table->timestamps();

            // ایندکس‌ها برای کوئری‌های متداول
            $table->index(['student_id', 'started_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_sessions');
    }
};
