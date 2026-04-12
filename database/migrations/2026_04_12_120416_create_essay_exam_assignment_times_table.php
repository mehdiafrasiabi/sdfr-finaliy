<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('essay_exam_assignment_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')
                ->constrained('essay_exam_assignments')
                ->cascadeOnDelete();
            $table->dateTime('start_at'); // شروع بازه
            $table->dateTime('end_at');   // پایان بازه
            $table->unsignedSmallInteger('duration_minutes'); // زمان آزمون (دقیقه)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('essay_exam_assignment_times');
    }
};

