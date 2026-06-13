<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * در مدل جدید «ماه ورود»، تخفیف هر ماه فقط با month_index + discount_percentage
 * نگه‌داری می‌شود و ستون‌های تاریخِ ماه دیگر لازم نیستند؛ nullable می‌شوند.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('grade_price_month_discounts')) {
            return;
        }

        Schema::table('grade_price_month_discounts', function (Blueprint $table) {
            $table->date('month_starts_on')->nullable()->change();
            $table->date('month_ends_on')->nullable()->change();
        });
    }

    public function down(): void
    {
        // بازگشت‌پذیری لازم نیست؛ nullable نگه داشته می‌شود.
    }
};
