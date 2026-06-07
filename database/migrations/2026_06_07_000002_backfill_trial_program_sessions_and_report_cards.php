<?php

use App\Models\AdvisingSession;
use App\Models\TrialWeek;
use App\Models\WeeklyProgram;
use App\Services\TrialWeekService;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;

/**
 * ترمیم دانش‌آموزان آزمایشی که قبل از اصلاحات، برنامه‌شان ساخته شده بود ولی:
 *  - جلسهٔ آزمایشی «برگزارشده» (held) علامت نخورده بود،
 *  - WeeklyProgram به جلسه پیوند نخورده بود،
 *  - کارنامهٔ هوشمند برایشان ساخته نشده بود.
 * در نتیجه /profile/plan و /profile/studySession و /profile/report و
 * /profile/reportStudentStudy خالی نمایش داده می‌شدند.
 */
return new class extends Migration {

    public function up(): void
    {
        $service = app(TrialWeekService::class);

        TrialWeek::where('status', TrialWeek::STATUS_PROGRAM_BUILT)
            ->chunkById(100, function ($trials) use ($service) {
                foreach ($trials as $trial) {
                    // جدیدترین برنامهٔ فعال دانش‌آموز
                    $program = WeeklyProgram::where('student_id', $trial->student_id)
                        ->where('is_active', true)
                        ->latest('start_date')
                        ->first();

                    if (!$program) {
                        continue;
                    }

                    // جلسه: ابتدا جلسهٔ ثبت‌شده روی هفتهٔ آزمایشی، سپس جلسهٔ پیوندخورده به برنامه،
                    // در نهایت جدیدترین جلسهٔ دانش‌آموز.
                    $session = ($trial->advising_session_id ? AdvisingSession::find($trial->advising_session_id) : null)
                        ?? ($program->advising_session_id ? AdvisingSession::find($program->advising_session_id) : null)
                        ?? AdvisingSession::where('student_id', $trial->student_id)->latest('id')->first();

                    if ($session) {
                        $session->update([
                            'result_status'   => AdvisingSession::RESULT_HELD,
                            'status'          => AdvisingSession::STATUS_COMPLETED,
                            'is_active'       => true,
                            'activation_date' => $session->activation_date
                                ? Carbon::parse($session->activation_date)->min(Carbon::parse($program->start_date))->toDateString()
                                : Carbon::parse($program->start_date)->toDateString(),
                            'session_time'    => $session->session_time ?? Carbon::now()->format('H:i:s'),
                        ]);

                        if (!$program->advising_session_id) {
                            $program->update(['advising_session_id' => $session->id]);
                        }

                        if (!$trial->advising_session_id) {
                            $trial->update(['advising_session_id' => $session->id]);
                        }
                    }

                    // کارنامهٔ هوشمند
                    $service->generateSmartReportCard($trial->fresh(), $program->fresh());
                }
            });
    }

    public function down(): void
    {
        // داده‌ای حذف نمی‌شود (ترمیم یک‌طرفه).
    }
};
