<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_assessment_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
            // in_progress | completed
            $table->string('status', 20)->default('in_progress');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            // 1-based, points to next question to be shown
            $table->unsignedSmallInteger('current_question_order')->default(1);
            $table->unsignedSmallInteger('answered_count')->default(0);
            // MBTI: {"type":"INTJ","axes":{"E":7,"I":9,...}}
            // VARK: {"profile":"VK","scores":{"V":7,"A":3,"R":4,"K":6}}
            // Custom: null (Phase 1 stores raw answers only)
            $table->json('computed_result')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'assessment_id']);
            $table->index(['student_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_assessment_attempts');
    }
};
