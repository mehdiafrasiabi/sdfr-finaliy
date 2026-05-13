<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('grade_prices', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('grade'); // 9..12
            $table->unsignedBigInteger('base_price'); // total price (تومان)
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedTinyInteger('months_count');
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('grade_price_months', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_price_id')->constrained('grade_prices')->cascadeOnDelete();
            $table->unsignedTinyInteger('month_index'); // 0-based offset from start
            $table->unsignedTinyInteger('jalali_month')->nullable();
            $table->unsignedSmallInteger('jalali_year')->nullable();
            $table->unsignedBigInteger('price');
            $table->timestamps();
            $table->unique(['grade_price_id', 'month_index']);
        });

        Schema::create('grade_price_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_price_id')->constrained('grade_prices')->cascadeOnDelete();
            $table->unsignedTinyInteger('month_index');
            $table->unsignedTinyInteger('percent'); // 0..100
            $table->timestamps();
            $table->unique(['grade_price_id', 'month_index']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_price_discounts');
        Schema::dropIfExists('grade_price_months');
        Schema::dropIfExists('grade_prices');
    }
};
