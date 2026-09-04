<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('phone_leads', function (Blueprint $table) {
            if (! Schema::hasColumn('phone_leads', 'disinterest_status')) {
                $table->string('disinterest_status', 20)->nullable()->after('last_outcome');
            }

            if (! Schema::hasColumn('phone_leads', 'disinterest_reason')) {
                $table->text('disinterest_reason')->nullable()->after('disinterest_status');
            }

            if (! Schema::hasColumn('phone_leads', 'disinterest_at')) {
                $table->timestamp('disinterest_at')->nullable()->after('disinterest_reason');
            }
        });

        if (! Schema::hasIndex('phone_leads', ['disinterest_status'])) {
            Schema::table('phone_leads', function (Blueprint $table) {
                $table->index('disinterest_status');
            });
        }

        Schema::table('phone_calls', function (Blueprint $table) {
            if (! Schema::hasColumn('phone_calls', 'disinterest_status')) {
                $table->string('disinterest_status', 20)->nullable()->after('spoke_with_other');
            }

            if (! Schema::hasColumn('phone_calls', 'disinterest_reason')) {
                $table->text('disinterest_reason')->nullable()->after('disinterest_status');
            }
        });

        if (! Schema::hasIndex('phone_calls', ['disinterest_status'])) {
            Schema::table('phone_calls', function (Blueprint $table) {
                $table->index('disinterest_status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasIndex('phone_calls', ['disinterest_status'])) {
            Schema::table('phone_calls', function (Blueprint $table) {
                $table->dropIndex(['disinterest_status']);
            });
        }

        $phoneCallColumns = array_values(array_filter([
            Schema::hasColumn('phone_calls', 'disinterest_status') ? 'disinterest_status' : null,
            Schema::hasColumn('phone_calls', 'disinterest_reason') ? 'disinterest_reason' : null,
        ]));

        if ($phoneCallColumns !== []) {
            Schema::table('phone_calls', function (Blueprint $table) use ($phoneCallColumns) {
                $table->dropColumn($phoneCallColumns);
            });
        }

        if (Schema::hasIndex('phone_leads', ['disinterest_status'])) {
            Schema::table('phone_leads', function (Blueprint $table) {
                $table->dropIndex(['disinterest_status']);
            });
        }

        $phoneLeadColumns = array_values(array_filter([
            Schema::hasColumn('phone_leads', 'disinterest_status') ? 'disinterest_status' : null,
            Schema::hasColumn('phone_leads', 'disinterest_reason') ? 'disinterest_reason' : null,
            Schema::hasColumn('phone_leads', 'disinterest_at') ? 'disinterest_at' : null,
        ]));

        if ($phoneLeadColumns !== []) {
            Schema::table('phone_leads', function (Blueprint $table) use ($phoneLeadColumns) {
                $table->dropColumn($phoneLeadColumns);
            });
        }
    }
};
