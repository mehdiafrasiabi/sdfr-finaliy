<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trial_weeks', function (Blueprint $table) {
            if (! Schema::hasColumn('trial_weeks', 'acq_disinterest_status')) {
                $table->string('acq_disinterest_status', 20)->nullable()->after('acq_follow_up');
            }

            if (! Schema::hasColumn('trial_weeks', 'acq_disinterest_reason')) {
                $table->text('acq_disinterest_reason')->nullable()->after('acq_disinterest_status');
            }

            if (! Schema::hasColumn('trial_weeks', 'acq_disinterest_at')) {
                $table->timestamp('acq_disinterest_at')->nullable()->after('acq_disinterest_reason');
            }
        });

        if (! Schema::hasIndex('trial_weeks', ['acq_disinterest_status'])) {
            Schema::table('trial_weeks', function (Blueprint $table) {
                $table->index('acq_disinterest_status');
            });
        }

        Schema::table('trial_acquisition_calls', function (Blueprint $table) {
            if (! Schema::hasColumn('trial_acquisition_calls', 'disinterest_status')) {
                $table->string('disinterest_status', 20)->nullable()->after('call_subject');
            }

            if (! Schema::hasColumn('trial_acquisition_calls', 'disinterest_reason')) {
                $table->text('disinterest_reason')->nullable()->after('disinterest_status');
            }
        });

        if (! Schema::hasIndex('trial_acquisition_calls', ['disinterest_status'])) {
            Schema::table('trial_acquisition_calls', function (Blueprint $table) {
                $table->index('disinterest_status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasIndex('trial_acquisition_calls', ['disinterest_status'])) {
            Schema::table('trial_acquisition_calls', function (Blueprint $table) {
                $table->dropIndex(['disinterest_status']);
            });
        }

        $trialAcquisitionCallColumns = array_values(array_filter([
            Schema::hasColumn('trial_acquisition_calls', 'disinterest_status') ? 'disinterest_status' : null,
            Schema::hasColumn('trial_acquisition_calls', 'disinterest_reason') ? 'disinterest_reason' : null,
        ]));

        if ($trialAcquisitionCallColumns !== []) {
            Schema::table('trial_acquisition_calls', function (Blueprint $table) use ($trialAcquisitionCallColumns) {
                $table->dropColumn($trialAcquisitionCallColumns);
            });
        }

        if (Schema::hasIndex('trial_weeks', ['acq_disinterest_status'])) {
            Schema::table('trial_weeks', function (Blueprint $table) {
                $table->dropIndex(['acq_disinterest_status']);
            });
        }

        $trialWeekColumns = array_values(array_filter([
            Schema::hasColumn('trial_weeks', 'acq_disinterest_status') ? 'acq_disinterest_status' : null,
            Schema::hasColumn('trial_weeks', 'acq_disinterest_reason') ? 'acq_disinterest_reason' : null,
            Schema::hasColumn('trial_weeks', 'acq_disinterest_at') ? 'acq_disinterest_at' : null,
        ]));

        if ($trialWeekColumns !== []) {
            Schema::table('trial_weeks', function (Blueprint $table) use ($trialWeekColumns) {
                $table->dropColumn($trialWeekColumns);
            });
        }
    }
};
