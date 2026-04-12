<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('essay_exam_question_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')
                ->constrained('essay_exam_attempts')
                ->cascadeOnDelete();
            $table->foreignId('essay_exam_question_id')
                ->constrained('essay_exam_questions')
                ->cascadeOnDelete();
            $table->decimal('score', 4, 2)->default(0); // نمره داده‌شده توسط مشاور
            $table->timestamps();
            $table->unique(['attempt_id', 'essay_exam_question_id'], 'eeqs_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('essay_exam_question_scores');
    }
};

