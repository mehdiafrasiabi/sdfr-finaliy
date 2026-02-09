<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('makeup_sessions', function (Blueprint $table) {
            // برای ذخیره state تایمر
            $table->timestamp('paused_at')->nullable()->after('ended_at');
            $table->integer('remaining_seconds')->nullable()->after('paused_at');
        });
    }

    public function down(): void
    {
        Schema::table('makeup_sessions', function (Blueprint $table) {
            $table->dropColumn(['paused_at', 'remaining_seconds']);
        });
    }
};
