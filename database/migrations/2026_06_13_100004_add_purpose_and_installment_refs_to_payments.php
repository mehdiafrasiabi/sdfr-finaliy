<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * تفکیک هدفِ پرداخت تا در callback درگاه بتوان نوع آن را تشخیص داد:
 *   course_full        = خرید کامل دوره
 *   installment_initial = پیش‌پرداخت طرح اقساطی
 *   installment        = یک قسط از طرح اقساطی
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('payments')) {
            return;
        }

        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'purpose')) {
                $table->string('purpose', 30)->default('course_full')->after('status');
            }
            if (! Schema::hasColumn('payments', 'installment_plan_id')) {
                $table->unsignedBigInteger('installment_plan_id')->nullable()->after('purpose');
                $table->index('installment_plan_id');
            }
            if (! Schema::hasColumn('payments', 'installment_id')) {
                $table->unsignedBigInteger('installment_id')->nullable()->after('installment_plan_id');
                $table->index('installment_id');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('payments')) {
            return;
        }

        Schema::table('payments', function (Blueprint $table) {
            foreach (['installment_id', 'installment_plan_id', 'purpose'] as $col) {
                if (Schema::hasColumn('payments', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
