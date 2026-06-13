<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * کلید «بستن موقت پنل دانش‌آموز» + پیام نمایشی.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('general_settings')) {
            return;
        }

        Schema::table('general_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('general_settings', 'student_panel_closed')) {
                $table->boolean('student_panel_closed')->default(false);
            }
            if (! Schema::hasColumn('general_settings', 'student_panel_closed_message')) {
                $table->text('student_panel_closed_message')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('general_settings')) {
            return;
        }

        Schema::table('general_settings', function (Blueprint $table) {
            foreach (['student_panel_closed', 'student_panel_closed_message'] as $col) {
                if (Schema::hasColumn('general_settings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
