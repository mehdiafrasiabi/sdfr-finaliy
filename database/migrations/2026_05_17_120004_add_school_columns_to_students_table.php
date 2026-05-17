<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('school_id')->nullable()->after('id')
                ->constrained('schools')->nullOnDelete();
            $table->foreignId('school_supporter_id')->nullable()->after('school_id')
                ->constrained('admins')->nullOnDelete();
            $table->string('national_code', 10)->nullable()->after('school_supporter_id');
            $table->string('father_mobile')->nullable()->after('national_code');
            $table->string('mother_mobile')->nullable()->after('father_mobile');
            $table->string('grade')->nullable()->after('mother_mobile');
            $table->string('field')->nullable()->after('grade');
            $table->enum('educational_pursuer', ['father', 'mother'])->nullable()->after('field');

            $table->index('national_code');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropForeign(['school_supporter_id']);
            $table->dropIndex(['national_code']);
            $table->dropColumn([
                'school_id',
                'school_supporter_id',
                'national_code',
                'father_mobile',
                'mother_mobile',
                'grade',
                'field',
                'educational_pursuer',
            ]);
        });
    }
};
