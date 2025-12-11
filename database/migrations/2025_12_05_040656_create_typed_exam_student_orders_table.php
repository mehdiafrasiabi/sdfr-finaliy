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
        Schema::create('typed_exam_student_orders', function (Blueprint $table) {

            $table->id();

            $table->foreignId('attempt_id')->constrained('typed_exam_attempts')->cascadeOnDelete();

            $table->foreignId('question_id')->constrained()->cascadeOnDelete();

            $table->unsignedInteger('question_order'); // ترتیب نمایش سوال برای این دانش‌آموز

            $table->json('options_order')->nullable(); // ترتیب گزینه‌ها [2,4,1,3]

            $table->timestamps();



            // هر سوال فقط یکبار در هر attempt

            $table->unique(['attempt_id', 'question_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('typed_exam_student_orders');
    }
};
