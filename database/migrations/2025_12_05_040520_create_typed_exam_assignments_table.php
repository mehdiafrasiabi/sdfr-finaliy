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
        Schema::create('typed_exam_assignments', function (Blueprint $table) {

            $table->id();

            $table->foreignId('typed_exam_id')->constrained()->cascadeOnDelete();

            $table->foreignId('student_id')->constrained()->cascadeOnDelete();

            $table->foreignId('admin_id')->constrained(); // مشاور/پشتیبان که اختصاص داده

            $table->enum('status', ['pending', 'started', 'completed', 'expired'])->default('pending');

            $table->softDeletes();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('typed_exam_assignments');
    }
};
