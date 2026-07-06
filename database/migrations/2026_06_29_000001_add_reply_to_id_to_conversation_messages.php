<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * افزودن قابلیت «ریپلای» (پاسخ به یک پیام).
 * reply_to_id به همان جدول conversation_messages اشاره می‌کند.
 * در صورت حذف پیامِ مرجع، این ستون null می‌شود تا پیامِ پاسخ باقی بماند.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conversation_messages', function (Blueprint $table) {
            $table->foreignId('reply_to_id')
                ->nullable()
                ->after('image_path')
                ->constrained('conversation_messages')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('conversation_messages', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reply_to_id');
        });
    }
};
