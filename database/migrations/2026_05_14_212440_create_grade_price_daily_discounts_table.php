<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تخفیف‌های روز-خاص (یا بازه‌ای) برای هر `GradePrice`.
     * اگر روز جاری در بازهٔ این رکورد قرار بگیرد، روی قیمتِ پلکانی ماهانه
     * یک «درصد تخفیف اضافه» اعمال می‌شود.
     */
    public function up(): void
    {
        Schema::create('grade_price_daily_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_price_id')->constrained()->cascadeOnDelete();
            $table->string('label', 100)->nullable();
            $table->tinyInteger('discount_percentage'); // 1 - 100
            $table->date('starts_on'); // معمولاً همان روزِ تخفیف
            $table->date('ends_on');   // معمولاً همان روز (تخفیف ۱-روزه) یا بازه‌ای کوتاه
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['grade_price_id', 'starts_on', 'ends_on'], 'gp_dd_grade_dates_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_price_daily_discounts');
    }
};
