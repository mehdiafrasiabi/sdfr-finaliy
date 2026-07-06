<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * گسترشِ مستنداتِ تماس برای جریانِ تماسِ تایمردارِ صفحه‌ی جلسات:
 * وضعیتِ برقراری، مدتِ مکالمه، زمانِ پاسخ، علتِ عدم‌پاسخ + افزودنِ ترکیب‌های پاسخگو.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('contact_documentations', function (Blueprint $table) {
            $table->boolean('connected')->default(false)->after('contact_status');
            $table->unsignedSmallInteger('talk_duration_seconds')->nullable()->after('connected');
            $table->timestamp('answered_at')->nullable()->after('talk_duration_seconds');
            $table->string('fail_reason')->nullable()->after('answered_at');
        });

        // افزودنِ «دانش‌آموز + پدر» و «دانش‌آموز + مادر» به گزینه‌های پاسخگو.
        DB::statement("ALTER TABLE contact_documentations MODIFY respondent ENUM('father','mother','student','other','student_father','student_mother') NOT NULL");
    }

    public function down(): void
    {
        Schema::table('contact_documentations', function (Blueprint $table) {
            $table->dropColumn(['connected', 'talk_duration_seconds', 'answered_at', 'fail_reason']);
        });

        DB::statement("ALTER TABLE contact_documentations MODIFY respondent ENUM('father','mother','student','other') NOT NULL");
    }
};
