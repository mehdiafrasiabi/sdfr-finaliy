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
        Schema::create('typed_exam_questions', function (Blueprint $table) {

            $table->id();

            $table->foreignId('typed_exam_id')->constrained()->cascadeOnDelete();

            $table->foreignId('question_id')->constrained()->cascadeOnDelete();

            $table->unsignedInteger('order')->default(0); // ترتیب نمایش سوال

            $table->timestamps();


            // هر سوال فقط یکبار در هر آزمون

            $table->unique(['typed_exam_id', 'question_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('typed_exam_questions');
    }
};
