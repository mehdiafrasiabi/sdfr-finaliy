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
        Schema::create('typed_exam_random_configs', function (Blueprint $table) {

            $table->id();

            $table->foreignId('typed_exam_id')->constrained()->cascadeOnDelete();

            $table->enum('difficulty', ['easy', 'medium', 'hard', 'special']); // درجه سوالات

            $table->unsignedInteger('count'); // تعداد سوالات

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('typed_exam_random_configs');
    }
};
