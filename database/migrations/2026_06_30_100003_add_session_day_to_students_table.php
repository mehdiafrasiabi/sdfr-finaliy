<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * روزِ ثابتِ هفتگیِ جلسه‌ی مشاوره‌ی دانش‌آموز (۰=شنبه .. ۶=جمعه).
 * هنگام تاییدِ انتخابِ مشاور توسط مدیر آموزشی مقداردهی می‌شود و مبنای
 * چیدمانِ «دانش‌آموزانِ هر روز» در صفحه‌ی جلساتِ مشاور است.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedTinyInteger('session_day')->nullable()->after('advisor_id');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('session_day');
        });
    }
};
