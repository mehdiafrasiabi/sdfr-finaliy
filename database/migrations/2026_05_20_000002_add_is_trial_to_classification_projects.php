<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('classification_projects', function (Blueprint $table) {
            $table->boolean('is_trial')->default(false)->after('is_active');
        });

        // Ensure the always-on trial classification project exists.
        $exists = DB::table('classification_projects')->where('is_trial', true)->exists();
        if (!$exists) {
            DB::table('classification_projects')->insert([
                'name'        => 'طبقه‌بندی آزمایشی (دوره ۱ هفته)',
                'description' => 'طبقه‌بندی ویژه‌ی کاربران دوره‌ی ۱ هفته آزمایشی — همیشه فعال و بدون محدودیت زمانی.',
                'start_at'    => now()->subYear(),
                'end_at'      => now()->addYears(10),
                'is_active'   => true,
                'is_trial'    => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('classification_projects', function (Blueprint $table) {
            $table->dropColumn('is_trial');
        });
    }
};
