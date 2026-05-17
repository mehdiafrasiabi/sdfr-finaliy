<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('school_student_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cc_subject_id')->constrained('cc_subjects')->cascadeOnDelete();
            $table->foreignId('cc_chapter_id')->nullable()
                ->constrained('cc_chapters')->nullOnDelete();
            $table->foreignId('recorded_by_admin_id')->nullable()
                ->constrained('admins')->nullOnDelete();
            $table->decimal('score', 5, 2);
            $table->enum('scale', ['20', '100'])->default('20');
            $table->string('note', 255)->nullable();
            $table->date('recorded_at');
            $table->timestamps();

            $table->index(['student_id', 'cc_subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_student_grades');
    }
};
