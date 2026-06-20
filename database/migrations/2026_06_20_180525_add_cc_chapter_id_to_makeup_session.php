<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // جلسات اضافه بر سازمان بر اساس «فصل» می‌شوند (نه مبحث)
        Schema::table('makeup_sessions', function (Blueprint $table) {
            $table->foreignId('cc_chapter_id')->nullable()->after('cc_topic_id')->constrained('cc_chapters')->nullOnDelete();
        });

        // مبحث دیگر اجباری نیست — حذف موقت FK، تغییر به nullable، افزودن دوبارهٔ FK
        Schema::table('makeup_sessions', function (Blueprint $table) {
            $table->dropForeign(['cc_topic_id']);
        });
        DB::statement('ALTER TABLE makeup_sessions MODIFY cc_topic_id BIGINT UNSIGNED NULL');
        Schema::table('makeup_sessions', function (Blueprint $table) {
            $table->foreign('cc_topic_id')->references('id')->on('cc_topics')->nullOnDelete();
        });

        // پر کردن فصل برای رکوردهای موجود از روی مبحثشان
        DB::statement('
            UPDATE makeup_sessions ms
            JOIN cc_topics t ON t.id = ms.cc_topic_id
            SET ms.cc_chapter_id = t.cc_chapter_id
            WHERE ms.cc_chapter_id IS NULL
        ');
    }

    public function down(): void
    {
        Schema::table('makeup_sessions', function (Blueprint $table) {
            $table->dropForeign(['cc_chapter_id']);
            $table->dropColumn('cc_chapter_id');
        });
    }
};
