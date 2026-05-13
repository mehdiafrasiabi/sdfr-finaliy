<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trial_weeks', function (Blueprint $table) {
            $table->string('trial_decision', 20)->default('accepted')->after('status'); // pending|accepted|declined
            $table->boolean('is_active')->default(true)->after('trial_decision');
        });
    }

    public function down(): void
    {
        Schema::table('trial_weeks', function (Blueprint $table) {
            $table->dropColumn(['trial_decision', 'is_active']);
        });
    }
};
