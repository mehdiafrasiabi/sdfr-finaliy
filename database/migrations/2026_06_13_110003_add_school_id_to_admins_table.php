<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // بررسی وجود داشتن ستون قبل از ساختن آن
        if (!Schema::hasColumn('admins', 'school_id')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->foreignId('school_id')->nullable()->after('id')
                    ->constrained('schools')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('admins', 'school_id')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            });
        }
    }
};
