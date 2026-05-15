<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * بازطراحی جدول `grade_prices` طبق spec جدید کاربر:
     *   - حذف `field` (دیگر رشته جزء قیمت‌گذاری نیست)
     *   - حذف `label`, `months`, `discount_percentage` (نام پلن و تخفیف ثابت لازم نیست)
     *   - افزودن unique(grade) تا هر پایه فقط یک رکورد فعال داشته باشد.
     */
    public function up(): void
    {
        if (! Schema::hasTable('grade_prices')) {
            return;
        }

        Schema::table('grade_prices', function (Blueprint $table) {
            if (Schema::hasColumn('grade_prices', 'field')) {
                $table->dropColumn('field');
            }
            if (Schema::hasColumn('grade_prices', 'label')) {
                $table->dropColumn('label');
            }
            if (Schema::hasColumn('grade_prices', 'months')) {
                $table->dropColumn('months');
            }
            if (Schema::hasColumn('grade_prices', 'discount_percentage')) {
                $table->dropColumn('discount_percentage');
            }
        });

        Schema::table('grade_prices', function (Blueprint $table) {
            $table->unique('grade', 'grade_prices_grade_unique');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('grade_prices')) {
            return;
        }

        Schema::table('grade_prices', function (Blueprint $table) {
            try {
                $table->dropUnique('grade_prices_grade_unique');
            } catch (\Throwable $e) {
                // عبور
            }
        });

        Schema::table('grade_prices', function (Blueprint $table) {
            if (! Schema::hasColumn('grade_prices', 'field')) {
                $table->string('field', 20)->nullable()->after('grade');
            }
            if (! Schema::hasColumn('grade_prices', 'label')) {
                $table->string('label', 100)->nullable()->after('field');
            }
            if (! Schema::hasColumn('grade_prices', 'months')) {
                $table->tinyInteger('months')->default(12)->after('total_amount');
            }
            if (! Schema::hasColumn('grade_prices', 'discount_percentage')) {
                $table->tinyInteger('discount_percentage')->default(0)->after('months');
            }
        });
    }
};
