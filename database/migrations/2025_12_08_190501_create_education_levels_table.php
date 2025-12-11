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

        Schema::create('education_levels', function (Blueprint $table) {

            $table->id();

            $table->string('name'); // نام دوره مثل: ابتدایی، متوسطه اول، متوسطه دوم

            $table->string('slug')->unique();

            $table->integer('order')->default(0); // ترتیب نمایش

            $table->boolean('is_active')->default(true);

            $table->timestamps();

        });

    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_levels');
    }
};
