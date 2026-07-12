<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trial_weeks', function (Blueprint $table) {
            $table->timestamp('trial_started_sms_sent_at')->nullable()->after('program_built_at');
            $table->timestamp('trial_ended_sms_sent_at')->nullable()->after('trial_started_sms_sent_at');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->timestamp('purchase_completed_sms_sent_at')->nullable()->after('status');
        });

        DB::table('trial_weeks')->update([
            'trial_started_sms_sent_at' => DB::raw('created_at'),
        ]);

        DB::table('trial_weeks')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->update(['trial_ended_sms_sent_at' => now()]);

        DB::table('payments')
            ->where('status', 'completed')
            ->update(['purchase_completed_sms_sent_at' => DB::raw('updated_at')]);
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('purchase_completed_sms_sent_at');
        });

        Schema::table('trial_weeks', function (Blueprint $table) {
            $table->dropColumn(['trial_started_sms_sent_at', 'trial_ended_sms_sent_at']);
        });
    }
};
