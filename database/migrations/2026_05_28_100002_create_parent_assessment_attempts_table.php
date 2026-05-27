<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parent_assessment_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_id')->constrained('parent_assessment_invitations')->cascadeOnDelete();
            $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
            // in_progress | completed
            $table->string('status', 20)->default('in_progress');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->unsignedSmallInteger('current_question_order')->default(1);
            $table->unsignedSmallInteger('answered_count')->default(0);
            // facet score % برای custom assessments (مشابه student)
            $table->json('computed_result')->nullable();
            $table->timestamps();

            $table->unique(['invitation_id', 'assessment_id']);
            $table->index(['invitation_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parent_assessment_attempts');
    }
};
