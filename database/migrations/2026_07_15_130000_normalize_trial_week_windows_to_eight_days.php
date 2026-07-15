<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('trial_weeks')
            ->where('status', 'program_built')
            ->whereNotNull('program_built_at')
            ->orderBy('id')
            ->chunkById(100, function ($trials) {
                foreach ($trials as $trial) {
                    $start = Carbon::parse($trial->program_built_at)->startOfDay();
                    $end = $start->copy()->addDays(7);
                    $expiresAt = $end->copy()->endOfDay();
                    $now = now();

                    DB::table('trial_weeks')
                        ->where('id', $trial->id)
                        ->update([
                            'expires_at' => $expiresAt,
                            'updated_at' => $now,
                        ]);

                    if ($trial->student_id && $trial->advising_session_id) {
                        DB::table('weekly_programs')
                            ->where('student_id', $trial->student_id)
                            ->where('advising_session_id', $trial->advising_session_id)
                            ->whereNull('deleted_at')
                            ->whereNotExists(function ($query) {
                                $query->selectRaw('1')
                                    ->from('weekly_program_exam_days')
                                    ->whereColumn('weekly_program_exam_days.weekly_program_id', 'weekly_programs.id');
                            })
                            ->whereDate('end_date', '>', $end->toDateString())
                            ->update([
                                'end_date' => $end->toDateString(),
                                'updated_at' => $now,
                            ]);
                    }

                    if ($trial->student_id) {
                        DB::table('smart_report_cards')
                            ->where('student_id', $trial->student_id)
                            ->whereDate('start_date', '<=', $start->toDateString())
                            ->whereDate('end_date', '>', $end->toDateString())
                            ->update([
                                'end_date' => $end->toDateString(),
                                'updated_at' => $now,
                            ]);
                    }
                }
            });
    }

    public function down(): void
    {
        //
    }
};
