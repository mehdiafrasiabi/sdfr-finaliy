<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            // محتوای تفسیر هر تست به‌صورت JSON — کاملاً داینامیک و قابل ویرایش در پنل مدیر.
            // ساختار: { version, engine: 'facet'|'modality', thresholds, facets{}, flags{}, overall{}, modalities{} }
            // چون روی همان ردیف تست ذخیره می‌شود، با حذف/غیرفعال‌شدن تست، تفسیرش هم خود‌به‌خود کنار می‌رود.
            $table->json('interpretation')->nullable()->after('expected_question_count');
        });
    }

    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropColumn('interpretation');
        });
    }
};
