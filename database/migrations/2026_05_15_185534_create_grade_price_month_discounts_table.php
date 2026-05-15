<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * یک رکورد به ازای هر ماهِ پلن قیمت‌گذاری (`grade_price_id` × `month_index`).
     * مدیر می‌تواند برای هر ماه درصد تخفیف اضافی روی قیمت ثابتِ پلکانی آن ماه
     * مشخص کند. این تخفیف ماهیانه، نه روزانه.
     */
    public function up(): void
    {
        Schema::create('grade_price_month_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_price_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('month_index'); // 0..months-1
            $table->date('month_starts_on');
            $table->date('month_ends_on');
            $table->tinyInteger('discount_percentage')->default(0); // 0..100
            $table->timestamps();

            $table->unique(['grade_price_id', 'month_index'], 'gp_md_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_price_month_discounts');
    }
};
