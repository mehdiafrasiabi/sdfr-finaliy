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
        Schema::create('cc_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('education_level_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // نام پایه مثل: دهم، یازدهم، دوازدهم
            $table->tinyInteger('grade_number'); // 10, 11, 12
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
        Schema::dropIfExists('cc_grades');
    }
};
