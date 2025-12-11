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
        Schema::create('typed_exam_attempt_answers', function (Blueprint $table) {

            $table->id();

            $table->foreignId('attempt_id')->constrained('typed_exam_attempts')->cascadeOnDelete();

            $table->foreignId('question_id')->constrained()->cascadeOnDelete();

            $table->unsignedTinyInteger('selected_option')->nullable(); // 1, 2, 3, 4 or null

            $table->boolean('is_correct')->nullable();

            $table->timestamp('answered_at')->nullable();

            $table->timestamps();



            // هر سوال فقط یک پاسخ در هر attempt

            $table->unique(['attempt_id', 'question_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('typed_exam_attempt_answers');
    }
};
