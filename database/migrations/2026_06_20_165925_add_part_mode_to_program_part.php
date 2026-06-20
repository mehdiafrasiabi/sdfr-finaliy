<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('program_parts', function (Blueprint $table) {
            // حالت پارت: عادی، کل کتاب، پارت مروری
            $table->string('part_mode', 20)->default('normal')->after('part_type');
            // فصل‌های انتخاب‌شده برای پارت مروری: [{"id":1,"name":"فصل اول"}, ...]
            $table->json('review_chapters')->nullable()->after('cc_topic_id');
        });
    }

    public function down(): void
    {
        Schema::table('program_parts', function (Blueprint $table) {
            $table->dropColumn(['part_mode', 'review_chapters']);
        });
    }
};
