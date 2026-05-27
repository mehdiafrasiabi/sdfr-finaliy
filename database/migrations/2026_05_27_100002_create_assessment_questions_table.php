<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
            $table->unsignedSmallInteger('order');
            $table->text('question_text_fa');
            // likert5 | yes_no | mbti_binary | vark_multi
            $table->string('type', 20);
            // MBTI: {"axis":"EI","a_pole":"E","b_pole":"I"}
            // VARK: weights live on options
            // Custom: {"facet":"perfectionism","reverse":false,"yes_weight":1,"no_weight":0}
            $table->json('scoring_meta')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['assessment_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_questions');
    }
};
