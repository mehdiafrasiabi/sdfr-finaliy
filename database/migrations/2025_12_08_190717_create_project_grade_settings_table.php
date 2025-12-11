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

        Schema::create('project_grade_settings', function (Blueprint $table) {

            $table->id();

            $table->foreignId('classification_project_id')->constrained()->cascadeOnDelete();

            $table->tinyInteger('student_grade'); // پایه دانش‌آموز: 10, 11, 12

            $table->tinyInteger('target_grade'); // پایه هدف: 10, 11, 12

            $table->enum('type', ['progress', 'review']); // پیشروی یا جمع‌بندی

            $table->boolean('has_general')->default(false); // عمومی دارد؟

            $table->timestamps();

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_grade_settings');
    }
};
