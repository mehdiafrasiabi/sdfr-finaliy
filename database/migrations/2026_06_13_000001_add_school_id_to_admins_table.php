<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            // مدرسه‌ای که این ادمین «مدیر مدرسه»ی آن است (در صورت داشتن نقش مدیر مدرسه)
            $table->foreignId('school_id')->nullable()->after('id')
                ->constrained('schools')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
        });
    }
};
