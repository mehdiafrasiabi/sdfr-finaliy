<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->foreignId('cc_chapter_id')
                ->nullable()
                ->after('subject_id')
                ->constrained('cc_chapters')
                ->nullOnDelete();
        });

        DB::statement(<<<'SQL'
            UPDATE questions q
            JOIN cc_topics t ON t.id = q.cc_topic_id
            SET q.cc_chapter_id = t.cc_chapter_id
            WHERE q.cc_topic_id IS NOT NULL
              AND q.cc_chapter_id IS NULL
        SQL);
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['cc_chapter_id']);
            $table->dropColumn('cc_chapter_id');
        });
    }
};
