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
        Schema::create('exam_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('question_number');
            $table->string('correct_option'); // گزینه صحیح: 1 تا 4
            $table->timestamps();

            // ایندکس برای جلوگیری از تکرار کلیدها برای یک سوال در یک آزمون
            $table->unique(['exam_id', 'question_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_keys');
    }
};
