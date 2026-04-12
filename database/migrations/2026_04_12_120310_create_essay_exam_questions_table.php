<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('essay_exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('essay_exam_id')->constrained('essay_exams')->cascadeOnDelete();
            $table->unsignedInteger('question_number'); // شماره سوال
            $table->decimal('score', 4, 2); // نمره سوال (مضارب 0.25)
            $table->unsignedSmallInteger('row_height')->default(110); // ارتفاع ردیف (px) برای پاسخ‌برگ
            $table->timestamps();
            $table->unique(['essay_exam_id', 'question_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('essay_exam_questions');
    }
};

