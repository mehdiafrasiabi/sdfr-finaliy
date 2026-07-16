<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('student_exam_schedules')
            ->whereNotNull('weekly_program_id')
            ->whereNotNull('program_built_at')
            ->whereNotNull('access_expires_at')
            ->orderBy('id')
            ->chunkById(100, function ($schedules) {
                foreach ($schedules as $schedule) {
                    DB::table('trial_weeks')
                        ->where('user_id', $schedule->user_id)
                        ->where('student_id', $schedule->student_id)
                        ->where('status', 'program_built')
                        ->update([
                            'program_built_at' => $schedule->program_built_at,
                            'expires_at' => $schedule->access_expires_at,
                            'updated_at' => now(),
                        ]);
                }
            });
    }

    public function down(): void
    {
        //
    }
};
