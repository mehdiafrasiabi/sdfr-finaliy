<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('smart_report_cards', function (Blueprint $table) {
            // کارنامهٔ دانش‌آموزان آزمایشی ممکن است ادمین/پشتیبان نداشته باشد.
            $table->unsignedBigInteger('admin_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('smart_report_cards', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_id')->nullable(false)->change();
        });
    }
};
