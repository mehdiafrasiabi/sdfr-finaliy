<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trial_weeks', function (Blueprint $table) {
            $table->timestamp('profile_acknowledged_at')->nullable()->after('assessments_completed_at');
        });
    }

    public function down(): void
    {
        Schema::table('trial_weeks', function (Blueprint $table) {
            $table->dropColumn('profile_acknowledged_at');
        });
    }
};
