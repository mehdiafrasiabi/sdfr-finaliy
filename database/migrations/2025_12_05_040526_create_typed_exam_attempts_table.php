<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('typed_exam_attempts', function (Blueprint $table) {

            $table->id();

            $table->foreignId('assignment_id')->constrained('typed_exam_assignments')->cascadeOnDelete();

            $table->foreignId('student_id')->constrained()->cascadeOnDelete();

            $table->timestamp('started_at')->nullable();

            $table->timestamp('submitted_at')->nullable();

            $table->boolean('is_finished')->default(false);

            $table->decimal('score', 5, 2)->nullable(); // درصد نمره

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('typed_exam_attempts');
    }
};
