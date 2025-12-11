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
        Schema::create('question_options', function (Blueprint $table) {

            $table->id();

            $table->foreignId('question_id')->constrained()->cascadeOnDelete();

            $table->unsignedTinyInteger('option_number'); // 1, 2, 3, 4

            $table->longText('content'); // محتوای گزینه (CKEditor HTML)

            $table->boolean('is_correct')->default(false);

            $table->timestamps();



            // هر سوال فقط 4 گزینه با شماره‌های یکتا دارد

            $table->unique(['question_id', 'option_number']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_options');
    }
};
