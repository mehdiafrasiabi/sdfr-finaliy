<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trial_acquisition_calls', function (Blueprint $table) {
            $table->unsignedInteger('talk_duration_seconds')->nullable()->after('called_at');
            $table->string('fail_reason', 20)->nullable()->after('emergency_reason');
            $table->json('spoke_with_people')->nullable()->after('spoke_with');
            $table->text('spoke_with_other')->nullable()->after('spoke_with_people');
            $table->string('call_subject', 50)->nullable()->after('spoke_with_other');
        });
    }

    public function down(): void
    {
        Schema::table('trial_acquisition_calls', function (Blueprint $table) {
            $table->dropColumn([
                'talk_duration_seconds',
                'fail_reason',
                'spoke_with_people',
                'spoke_with_other',
                'call_subject',
            ]);
        });
    }
};
