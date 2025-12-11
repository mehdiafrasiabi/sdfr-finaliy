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

        Schema::create('classification_projects', function (Blueprint $table) {

            $table->id();

            $table->string('name'); // نام پروژه

            $table->text('description')->nullable(); // توضیحات پروژه

            $table->dateTime('start_at'); // تاریخ شروع

            $table->dateTime('end_at'); // تاریخ پایان

            $table->boolean('is_active')->default(true);

            $table->timestamps();

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classification_projects');
    }
};
