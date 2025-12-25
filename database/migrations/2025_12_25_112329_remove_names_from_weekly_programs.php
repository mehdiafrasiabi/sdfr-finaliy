<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * حذف فیلدهای advisor_name و supporter_name چون از رابطه با student قابل دسترسی هستند
     */

    public function up(): void

    {

        Schema::table('weekly_programs', function (Blueprint $table) {

            $table->dropColumn(['advisor_name', 'supporter_name']);

        });

    }


    /**
     * Reverse the migrations.
     */

    public function down(): void

    {

        Schema::table('weekly_programs', function (Blueprint $table) {

            $table->string('advisor_name')->nullable()->after('end_date');

            $table->string('supporter_name')->nullable()->after('advisor_name');

        });

    }
};
