<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exam_sample_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_planning_setting_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cc_subject_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('pdf_path');
            $table->unsignedSmallInteger('duration_minutes')->default(0);
            $table->boolean('is_main')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['exam_planning_setting_id', 'cc_subject_id', 'is_main'], 'exam_sample_questions_setting_subject_main_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_sample_questions');
    }
};
