<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trial_weeks', function (Blueprint $table) {
            $table->timestamp('dashboard_notice_acknowledged_at')->nullable()->after('program_built_at');
        });
    }

    public function down(): void
    {
        Schema::table('trial_weeks', function (Blueprint $table) {
            $table->dropColumn('dashboard_notice_acknowledged_at');
        });
    }
};
