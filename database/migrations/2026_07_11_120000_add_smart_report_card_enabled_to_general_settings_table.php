<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('general_settings')) {
            return;
        }

        Schema::table('general_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('general_settings', 'smart_report_card_enabled')) {
                $table->boolean('smart_report_card_enabled')
                    ->default(false)
                    ->after('advisor_default_capacity');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('general_settings')) {
            return;
        }

        Schema::table('general_settings', function (Blueprint $table) {
            if (Schema::hasColumn('general_settings', 'smart_report_card_enabled')) {
                $table->dropColumn('smart_report_card_enabled');
            }
        });
    }
};
