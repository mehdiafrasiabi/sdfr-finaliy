<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فیلد «پیگیر آموزشی» برای ثبت اینکه تماس با چه فردی از خانواده
     * انجام شده (پدر / مادر / خود دانش‌آموز / سایر).
     */
    public function up(): void
    {
        Schema::table('acquisition_contacts', function (Blueprint $table) {
            if (! Schema::hasColumn('acquisition_contacts', 'educational_follow_up')) {
                $table->string('educational_follow_up', 30)->nullable()->after('attraction_plan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('acquisition_contacts', function (Blueprint $table) {
            if (Schema::hasColumn('acquisition_contacts', 'educational_follow_up')) {
                $table->dropColumn('educational_follow_up');
            }
        });
    }
};
