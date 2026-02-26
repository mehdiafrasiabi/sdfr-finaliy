<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('daily_report_details', function (Blueprint $table) {
            $table->text('missed_parts_reason')->nullable()->after('description');
            // Change rating to support 1-10 scale from session_feedbacks
            $table->decimal('rating', 4, 1)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('daily_report_details', function (Blueprint $table) {
            $table->dropColumn('missed_parts_reason');
            $table->tinyInteger('rating')->default(3)->change();
        });
    }
};
