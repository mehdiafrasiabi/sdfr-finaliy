<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('assessment_questions')->cascadeOnDelete();
            $table->unsignedTinyInteger('order');
            $table->string('label_fa', 500);
            // canonical: 'A','B','V','A','R','K','yes','no','1'..'5'
            $table->string('value', 40);
            // e.g. {"E":1} for MBTI option, {"V":1} for VARK option
            $table->json('weights')->nullable();
            $table->timestamps();

            $table->index(['question_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_question_options');
    }
};
