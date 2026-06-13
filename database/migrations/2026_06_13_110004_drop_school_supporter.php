<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['school_supporter_id']);
            $table->dropColumn('school_supporter_id');
        });

        Schema::dropIfExists('school_admin');
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('school_supporter_id')->nullable()->after('school_id')
                ->constrained('admins')->nullOnDelete();
        });

        Schema::create('school_admin', function (Blueprint $table) {
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();
            $table->timestamps();
            $table->primary(['school_id', 'admin_id']);
        });
    }
};
