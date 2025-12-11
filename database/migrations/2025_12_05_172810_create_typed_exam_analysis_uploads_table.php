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
        Schema::create('typed_exam_analysis_uploads', function (Blueprint $table) {

            $table->id();

            $table->foreignId('attempt_id')->constrained('typed_exam_attempts')->cascadeOnDelete();

            $table->string('file_path');

            $table->string('original_name');

            $table->unsignedInteger('file_size');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('typed_exam_analysis_uploads');
    }
};
