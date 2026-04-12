<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('essay_exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')
                ->constrained('essay_exam_assignments')
                ->cascadeOnDelete();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->decimal('total_score', 5, 2)->nullable(); // مجموع نمره داده‌شده توسط مشاور
            $table->enum('status', ['in_progress', 'submitted', 'graded'])->default('in_progress');
            $table->text('consultant_message')->nullable(); // پیام مشاور
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('essay_exam_attempts');
    }
};

