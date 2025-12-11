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

        Schema::table('advising_sessions', function (Blueprint $table) {

            // محل برگزاری: حضوری یا آنلاین
            $table->enum('location_type', ['in_person', 'online'])->default('online')->after('skyroom_link');
            // ساعت برگزاری جلسه
            $table->time('session_time')->nullable()->after('activation_date');
            // نتیجه جلسه مشاوره
            $table->enum('result_status', ['held', 'advisor_absent', 'student_absent'])->nullable()->after('status');

            // فعال یا غیرفعال - خودکار فعال می‌شود
            $table->boolean('is_active')->default(false)->after('result_status');
        });
    }

    public function down(): void
    {
        Schema::table('advising_sessions', function (Blueprint $table) {
            $table->dropColumn(['location_type', 'session_time', 'result_status', 'is_active']);
        });
    }
};
