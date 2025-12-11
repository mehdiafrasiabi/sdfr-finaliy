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

        Schema::create('cc_fields', function (Blueprint $table) {

            $table->id();

            $table->string('name'); // ریاضی، تجربی، انسانی، بدون رشته

            $table->string('slug')->unique(); // math, experimental, human, none

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
        Schema::dropIfExists('cc_fields');
    }
};
