<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 80)->unique();
            $table->string('name_fa', 150);
            $table->text('description_fa')->nullable();
            // mbti | vark | custom
            $table->string('kind', 20);
            // mbti_binary | vark_multi | mixed (custom may mix likert + yes_no)
            $table->string('question_type', 20);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_required')->default(true);
            $table->unsignedSmallInteger('display_order')->default(0);
            // student | parent  (parent reserved for Phase 2)
            $table->string('audience', 20)->default('student');
            $table->unsignedSmallInteger('expected_question_count')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'display_order']);
            $table->index('audience');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
