<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('student_exam_schedules as schedules')
            ->join('weekly_programs as programs', 'programs.id', '=', 'schedules.weekly_program_id')
            ->leftJoin('trial_weeks as trials', function ($join) {
                $join->on('trials.user_id', '=', 'schedules.user_id')
                    ->on('trials.student_id', '=', 'schedules.student_id');
            })
            ->whereNotNull('schedules.weekly_program_id')
            ->whereNotNull('schedules.program_built_at')
            ->whereNull('programs.deleted_at')
            ->select([
                'schedules.id',
                'schedules.student_id',
                'programs.start_date',
                'programs.end_date',
                'trials.acquisition_supporter_id',
            ])
            ->orderBy('schedules.id')
            ->chunk(100, function ($schedules) {
                foreach ($schedules as $schedule) {
                    if (! $schedule->start_date || ! $schedule->end_date) {
                        continue;
                    }

                    $start = Carbon::parse($schedule->start_date)->startOfDay();
                    $end = Carbon::parse($schedule->end_date)->endOfDay();
                    $jStart = Jalalian::fromCarbon($start);
                    $now = now();

                    $keys = [
                        'student_id' => $schedule->student_id,
                        'jalali_year' => (int) $jStart->format('Y'),
                        'jalali_month' => (int) $jStart->format('n'),
                    ];

                    $values = [
                        'admin_id' => $schedule->acquisition_supporter_id,
                        'start_date' => $start->toDateString(),
                        'end_date' => $end->toDateString(),
                        'is_active' => true,
                        'activated_at' => $now,
                        'updated_at' => $now,
                    ];

                    $exists = DB::table('smart_report_cards')->where($keys)->exists();

                    if ($exists) {
                        DB::table('smart_report_cards')->where($keys)->update($values);
                    } else {
                        DB::table('smart_report_cards')->insert(array_merge($keys, $values, [
                            'created_at' => $now,
                        ]));
                    }
                }
            });
    }

    public function down(): void
    {
        //
    }
};
