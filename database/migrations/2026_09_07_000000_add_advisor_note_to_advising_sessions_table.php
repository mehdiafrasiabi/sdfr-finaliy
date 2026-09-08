<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('advising_sessions', function (Blueprint $table) {
            // یادداشت اختیاری مشاور درباره‌ی وضعیت دانش‌آموز در این جلسه.
            // این یادداشت خصوصی است (فقط برای مشاوران/ادمین‌ها قابل مشاهده است، نه دانش‌آموز)
            // و در جلسه‌ی بعدی به‌عنوان یادداشت جلسه‌ی قبل نمایش داده می‌شود.
            $table->text('advisor_note')->nullable()->after('result_status');
        });
    }

    public function down(): void
    {
        Schema::table('advising_sessions', function (Blueprint $table) {
            $table->dropColumn('advisor_note');
        });
    }
};
