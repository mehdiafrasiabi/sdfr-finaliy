<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('school_report_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_report_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cc_subject_id')->constrained('cc_subjects')->cascadeOnDelete();
            $table->foreignId('cc_chapter_id')->constrained('cc_chapters')->cascadeOnDelete();
            $table->unsignedInteger('study_minutes')->default(0);
            $table->unsignedInteger('mobile_minutes')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_report_parts');
    }
};
