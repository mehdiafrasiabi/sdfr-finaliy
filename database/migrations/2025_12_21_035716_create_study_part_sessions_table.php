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

        Schema::create('study_part_sessions', function (Blueprint $table) {

            $table->id();

            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');

            $table->foreignId('program_part_id')->constrained('program_parts')->onDelete('cascade');

            $table->foreignId('weekly_program_id')->constrained('weekly_programs')->onDelete('cascade');

            $table->timestamp('started_at')->nullable(); // زمان شروع مطالعه

            $table->timestamp('ended_at')->nullable(); // زمان پایان مطالعه

            $table->integer('duration_seconds')->default(0); // مدت زمان مطالعه به ثانیه

            $table->integer('planned_seconds')->nullable(); // زمان برنامه‌ریزی شده

            $table->boolean('is_completed')->default(false); // آیا تکمیل شده؟

            $table->timestamp('completed_at')->nullable(); // زمان تکمیل

            $table->timestamps();



            // Index برای جستجوی سریع‌تر

            $table->index(['student_id', 'program_part_id']);

            $table->index(['student_id', 'is_completed']);

        });

    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_part_sessions');
    }
};
