<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_assessment_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('student_assessment_attempts')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('assessment_questions')->cascadeOnDelete();
            // single-select (MBTI binary, Likert, Yes/No)
            $table->foreignId('selected_option_id')->nullable()
                ->constrained('assessment_question_options')->nullOnDelete();
            // multi-select (VARK): array of option ids
            $table->json('selected_options')->nullable();
            // optional raw scalar value (e.g. likert 1..5, 'yes'/'no')
            $table->string('free_value', 40)->nullable();
            $table->timestamp('answered_at')->nullable();
            $table->timestamps();

            $table->unique(['attempt_id', 'question_id']);
            $table->index('question_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_assessment_answers');
    }
};
