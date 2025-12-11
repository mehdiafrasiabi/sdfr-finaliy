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
        Schema::create('typed_exams', function (Blueprint $table) {

            $table->id();

            $table->string('title'); // عنوان آزمون

            $table->string('academic_year'); // دوره زمانی (1404-1405, ...)

            $table->enum('difficulty', ['easy', 'medium', 'hard', 'comprehensive']); // درجه سختی

            $table->boolean('is_random_selection')->default(false); // انتخاب تصادفی سوالات

            $table->boolean('is_published')->default(false); // وضعیت انتشار

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('typed_exams');
    }
};
