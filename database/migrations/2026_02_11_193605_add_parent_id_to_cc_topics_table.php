<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cc_topics', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('cc_chapter_id')->constrained('cc_topics')->cascadeOnDelete();
            $table->boolean('has_subtopics')->default(false)->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cc_topics', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'has_subtopics']);
        });
    }
};
