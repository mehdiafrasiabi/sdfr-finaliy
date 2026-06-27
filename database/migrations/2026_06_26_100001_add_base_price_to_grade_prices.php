<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * «قیمت خام/اصلی سالانه» (قبل از تخفیف) به‌عنوان منبعِ حقیقتِ قیمت‌گذاری.
 *   - base_price: قیمتِ کاملِ سال بدون تخفیف (تومان). مدیر همین را وارد می‌کند.
 *   - نرخ ماهانه = base_price ÷ ۱۲ (در مدل محاسبه می‌شود؛ ستون monthly_rate هم
 *     برای سازگاری به‌عقب هم‌زمان نگه‌داری می‌شود).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('grade_prices')) {
            return;
        }

        Schema::table('grade_prices', function (Blueprint $table) {
            if (! Schema::hasColumn('grade_prices', 'base_price')) {
                $table->unsignedBigInteger('base_price')->nullable()->after('grade');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('grade_prices')) {
            return;
        }

        Schema::table('grade_prices', function (Blueprint $table) {
            if (Schema::hasColumn('grade_prices', 'base_price')) {
                $table->dropColumn('base_price');
            }
        });
    }
};
