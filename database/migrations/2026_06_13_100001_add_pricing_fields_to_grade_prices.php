<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * مدل قیمت‌گذاری «ماه ورود و تخفیف» (مطابق فایل اکسل):
 *   - monthly_rate: نرخ ماهانهٔ هر دانش‌آموز برای این پایه (تومان).
 *   - initial_percentage: درصد پیش‌پرداخت (پیش‌فرض ۳۰٪).
 * ستون‌های start_at/end_at به‌عنوان بازهٔ سال خدمت (تیر۱ تا پایان خرداد) استفاده می‌شوند.
 * ستون قدیمی total_amount دیگر در محاسبه استفاده نمی‌شود (سازگاری به‌عقب حفظ می‌شود).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('grade_prices')) {
            return;
        }

        Schema::table('grade_prices', function (Blueprint $table) {
            if (! Schema::hasColumn('grade_prices', 'monthly_rate')) {
                $table->unsignedBigInteger('monthly_rate')->default(0)->after('grade');
            }
            if (! Schema::hasColumn('grade_prices', 'initial_percentage')) {
                $table->unsignedTinyInteger('initial_percentage')->default(30)->after('monthly_rate');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('grade_prices')) {
            return;
        }

        Schema::table('grade_prices', function (Blueprint $table) {
            foreach (['monthly_rate', 'initial_percentage'] as $col) {
                if (Schema::hasColumn('grade_prices', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
