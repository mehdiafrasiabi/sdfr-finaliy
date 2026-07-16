<?php

use App\Models\AdvisingSession;
use App\Models\Payment;
use App\Models\StudentExamSchedule;
use App\Models\TrialWeek;
use App\Services\TrialLifecycleSmsService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('advising-sessions:mark-advisor-absent', function () {
    $count = AdvisingSession::markExpiredSessionsAsAdvisorAbsent();
    $this->info("Marked {$count} expired advising session(s) as advisor_absent.");
})->purpose('Auto-close expired advising sessions as advisor absent');

Artisan::command('sms:send-lifecycle-notifications', function () {
    $sms = app(TrialLifecycleSmsService::class);

    $started = 0;
    TrialWeek::query()
        ->whereNull('trial_started_sms_sent_at')
        ->with('user')
        ->chunkById(100, function ($trials) use ($sms, &$started) {
            foreach ($trials as $trial) {
                if ($sms->trySendTrialStarted($trial)) {
                    $started++;
                }
            }
        });

    $examProgramStarted = 0;
    StudentExamSchedule::query()
        ->whereNotNull('weekly_program_id')
        ->whereNotNull('program_built_at')
        ->whereNull('exam_program_started_sms_sent_at')
        ->whereHas('student', fn ($query) => $query->where('is_trial', true))
        ->with(['user', 'student'])
        ->chunkById(100, function ($schedules) use ($sms, &$examProgramStarted) {
            foreach ($schedules as $schedule) {
                if ($sms->trySendExamProgramStarted($schedule)) {
                    $examProgramStarted++;
                }
            }
        });

    $examProgramEnded = 0;
    StudentExamSchedule::query()
        ->whereNotNull('weekly_program_id')
        ->whereNotNull('program_built_at')
        ->whereNotNull('access_expires_at')
        ->where('access_expires_at', '<=', now())
        ->whereNull('exam_program_ended_sms_sent_at')
        ->whereHas('student', fn ($query) => $query->where('is_trial', true))
        ->with(['user.personalInformation', 'student'])
        ->chunkById(100, function ($schedules) use ($sms, &$examProgramEnded) {
            foreach ($schedules as $schedule) {
                if ($sms->trySendExamProgramEnded($schedule)) {
                    $examProgramEnded++;
                }
            }
        });

    $ended = 0;
    TrialWeek::query()
        ->where('status', TrialWeek::STATUS_PROGRAM_BUILT)
        ->whereNotNull('expires_at')
        ->where('expires_at', '<=', now())
        ->whereNull('trial_ended_sms_sent_at')
        ->whereHas('student', fn ($query) => $query->where('is_trial', true))
        ->whereDoesntHave('user.examSchedules', function ($query) {
            $query->whereNotNull('weekly_program_id')
                ->whereNotNull('program_built_at');
        })
        ->with(['user.personalInformation', 'student'])
        ->chunkById(100, function ($trials) use ($sms, &$ended) {
            foreach ($trials as $trial) {
                if ($sms->trySendTrialEnded($trial)) {
                    $ended++;
                }
            }
        });

    $purchases = 0;
    Payment::query()
        ->where('status', 'completed')
        ->whereNull('purchase_completed_sms_sent_at')
        ->where(function ($query) {
            $query->whereNull('purpose')
                ->orWhereIn('purpose', [
                    Payment::PURPOSE_COURSE_FULL,
                    Payment::PURPOSE_INSTALLMENT_INITIAL,
                ]);
        })
        ->with('user')
        ->chunkById(100, function ($payments) use ($sms, &$purchases) {
            foreach ($payments as $payment) {
                if ($sms->trySendPurchaseCompleted($payment)) {
                    $purchases++;
                }
            }
        });

    $this->info("Lifecycle SMS sent: started={$started}, exam_program_started={$examProgramStarted}, exam_program_ended={$examProgramEnded}, ended={$ended}, purchases={$purchases}.");
})->purpose('Send and retry required lifecycle SMS notifications');

Schedule::command('advising-sessions:mark-advisor-absent')->everyFiveMinutes();
Schedule::command('sms:send-lifecycle-notifications')->hourly()->withoutOverlapping();
