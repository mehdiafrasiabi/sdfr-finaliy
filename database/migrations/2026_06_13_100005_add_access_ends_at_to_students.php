<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * تاریخ پایان دسترسی دانش‌آموز پرداختی (پایان خرداد سالِ خدمت).
 * پس از این تاریخ، پنل بسته و فقط صفحهٔ تمدید نمایش داده می‌شود.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('students')) {
            return;
        }

        Schema::table('students', function (Blueprint $table) {
            if (! Schema::hasColumn('students', 'access_ends_at')) {
                $table->dateTime('access_ends_at')->nullable()->after('star');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('students')) {
            return;
        }

        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'access_ends_at')) {
                $table->dropColumn('access_ends_at');
            }
        });
    }
};
