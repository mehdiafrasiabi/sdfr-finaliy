<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('makeup_sessions', function (Blueprint $table) {
            // نوع پارت (تست/ویدیویی/تشریحی)
            $table->enum('part_type', ['test', 'descriptive', 'video'])
                ->default('descriptive')
                ->after('cc_topic_id');

            // زمان شروع و پایان
            $table->timestamp('started_at')->nullable()->after('duration_seconds');
            $table->timestamp('ended_at')->nullable()->after('started_at');
        });
    }

    public function down(): void
    {
        Schema::table('makeup_sessions', function (Blueprint $table) {
            $table->dropColumn(['part_type', 'started_at', 'ended_at']);
        });
    }
};
