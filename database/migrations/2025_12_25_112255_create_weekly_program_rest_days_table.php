<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * جدول روزهای استراحت برای برنامه هفتگی
     */

    public function up(): void

    {

        Schema::create('weekly_program_rest_days', function (Blueprint $table) {

            $table->id();

            $table->foreignId('weekly_program_id')->constrained('weekly_programs')->onDelete('cascade');

            $table->tinyInteger('day_index'); // 0-7 برای 8 روز

            $table->timestamps();


            // هر روز فقط یکبار میتونه استراحت باشه

            $table->unique(['weekly_program_id', 'day_index']);

        });

    }


    /**
     * Reverse the migrations.
     */

    public function down(): void

    {

        Schema::dropIfExists('weekly_program_rest_days');

    }
};
