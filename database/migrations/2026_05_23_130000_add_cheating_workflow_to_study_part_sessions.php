<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('study_part_sessions', function (Blueprint $table) {
            // فلگ تقلب: ثبت بعد از نیم‌ساعت grace
            $table->boolean('is_cheating')->default(false)->after('extra_ended_at');

            // چند دقیقه دیرتر از مهلت مجاز ثبت شده
            $table->unsignedInteger('cheat_minutes')->nullable()->after('is_cheating');

            // علت نوشته‌شده توسط دانش‌آموز
            $table->text('cheat_reason')->nullable()->after('cheat_minutes');

            // وضعیت تایید توسط مشاور
            $table->enum('cheat_status', ['pending', 'approved', 'rejected'])->nullable()->after('cheat_reason');

            // مشاوری که تصمیم گرفته
            $table->foreignId('cheat_decided_by')->nullable()->after('cheat_status')
                ->constrained('admins')->nullOnDelete();

            // زمان تصمیم
            $table->timestamp('cheat_decided_at')->nullable()->after('cheat_decided_by');

            // یادداشت مشاور (اختیاری)
            $table->text('cheat_decision_note')->nullable()->after('cheat_decided_at');

            $table->index(['student_id', 'cheat_status']);
        });
    }

    public function down(): void
    {
        Schema::table('study_part_sessions', function (Blueprint $table) {
            $table->dropIndex(['student_id', 'cheat_status']);
            $table->dropForeign(['cheat_decided_by']);
            $table->dropColumn([
                'is_cheating',
                'cheat_minutes',
                'cheat_reason',
                'cheat_status',
                'cheat_decided_by',
                'cheat_decided_at',
                'cheat_decision_note',
            ]);
        });
    }
};
