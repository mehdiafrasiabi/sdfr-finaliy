<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ظرفیتِ سراسریِ پیش‌فرضِ هر مشاور تحصیلی (تعداد دانش‌آموزی که هر مشاور می‌تواند بپذیرد).
 * در صورت تعیینِ ظرفیتِ اختصاصی روی خودِ مشاور، آن مقدار اولویت دارد.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->unsignedSmallInteger('advisor_default_capacity')->default(50)->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn('advisor_default_capacity');
        });
    }
};
