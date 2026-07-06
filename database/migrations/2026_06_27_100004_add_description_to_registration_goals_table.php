<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * توضیحات هدف‌گذاری ثبت‌نام (برای نمایش به مشاوران جذب).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registration_goals', function (Blueprint $table) {
            $table->text('description')->nullable()->after('target_count');
        });
    }

    public function down(): void
    {
        Schema::table('registration_goals', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
