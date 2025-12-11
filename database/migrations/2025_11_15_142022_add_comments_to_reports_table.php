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
        Schema::table('reports', function (Blueprint $table) {
            $table->text('advisor_comment')->nullable()->after('status');
            $table->timestamp('advisor_commented_at')->nullable()->after('advisor_comment');
            $table->text('student_reply')->nullable()->after('advisor_commented_at');
            $table->timestamp('student_replied_at')->nullable()->after('student_reply');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn([
                'advisor_comment',
                'advisor_commented_at',
                'student_reply',
                'student_replied_at',
            ]);
        });
    }
};
