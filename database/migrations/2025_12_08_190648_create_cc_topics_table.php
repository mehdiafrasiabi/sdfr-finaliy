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

        Schema::create('cc_topics', function (Blueprint $table) {

            $table->id();

            $table->foreignId('cc_chapter_id')->constrained()->cascadeOnDelete();

            $table->string('name'); // نام مبحث مثل: تابع مثلثاتی

            $table->integer('order')->default(0);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

        });

    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cc_topics');
    }
};
