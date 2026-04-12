<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('essay_exam_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('essay_exam_id')->constrained('essay_exams')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete(); // اختصاص‌دهنده
            $table->enum('status', ['pending', 'in_progress', 'submitted', 'graded'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('essay_exam_assignments');
    }
};

