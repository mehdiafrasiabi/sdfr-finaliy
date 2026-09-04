<?php

use App\Models\AdvisingSession;
use App\Models\ClassificationProject;
use App\Models\Payment;
use App\Models\StudentExamSchedule;
use App\Models\StudentClassification;
use App\Models\StudentClassificationSubmission;
use App\Models\TrialWeek;
use App\Models\WeeklyProgram;
use App\Services\TrialWeekService;
use App\Services\TrialLifecycleSmsService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
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

Artisan::command('trial-week:convert-exam-users-to-trial {--user= : Convert only one user id} {--dry-run : Show matching users without changing data}', function () {
    $userId = $this->option('user');
    $dryRun = (bool) $this->option('dry-run');

    $query = StudentExamSchedule::query()
        ->whereHas('setting', fn ($settingQuery) => $settingQuery->availableForExamOnboarding())
        ->whereHas('student', fn ($studentQuery) => $studentQuery->where('is_trial', true))
        ->whereHas('user.trialWeek')
        ->whereNull('converted_to_trial_at')
        ->when($userId, fn ($scheduleQuery) => $scheduleQuery->where('user_id', (int) $userId))
        ->with(['user.trialWeek', 'student']);

    $matched = 0;
    $converted = 0;
    $skippedPaid = 0;
    $skippedMissing = 0;

    $query->orderBy('id')->chunkById(100, function ($schedules) use ($dryRun, &$matched, &$converted, &$skippedPaid, &$skippedMissing) {
        foreach ($schedules as $schedule) {
            $matched++;

            $student = $schedule->student;
            $trial = $schedule->user?->trialWeek;

            if (! $student || ! $trial) {
                $skippedMissing++;
                $this->warn("Skipped schedule #{$schedule->id}: missing student or trial week.");
                continue;
            }

            if ($student->hasActivePaidAccess()) {
                $skippedPaid++;
                $this->warn("Skipped user #{$schedule->user_id}: active paid access exists.");
                continue;
            }

            $this->line(sprintf(
                '%s user #%d, schedule #%d, exam status=%s',
                $dryRun ? 'Would convert' : 'Converting',
                $schedule->user_id,
                $schedule->id,
                $trial->status
            ));

            if ($dryRun) {
                continue;
            }

            $trialId = DB::transaction(function () use ($schedule) {
                $lockedSchedule = StudentExamSchedule::query()
                    ->whereKey($schedule->id)
                    ->lockForUpdate()
                    ->first();

                if (! $lockedSchedule) {
                    return null;
                }

                $student = $lockedSchedule->student()->lockForUpdate()->first();
                $trial = TrialWeek::query()
                    ->where('user_id', $lockedSchedule->user_id)
                    ->where('student_id', $lockedSchedule->student_id)
                    ->latest('id')
                    ->lockForUpdate()
                    ->first();

                if (! $student || ! $trial || $student->hasActivePaidAccess()) {
                    return null;
                }

                if ($lockedSchedule->weekly_program_id) {
                    WeeklyProgram::query()
                        ->whereKey($lockedSchedule->weekly_program_id)
                        ->where('student_id', $student->id)
                        ->update(['is_active' => false]);
                }

                $lockedSchedule->update([
                    'weekly_program_id' => null,
                    'program_built_at' => null,
                    'access_expires_at' => null,
                    'converted_to_trial_at' => now(),
                ]);

                $trialProjectId = ClassificationProject::query()
                    ->where('is_trial', true)
                    ->where('is_active', true)
                    ->value('id');

                if ($trialProjectId) {
                    StudentClassification::query()
                        ->where('user_id', $lockedSchedule->user_id)
                        ->where('classification_project_id', $trialProjectId)
                        ->delete();

                    StudentClassificationSubmission::query()
                        ->where('user_id', $lockedSchedule->user_id)
                        ->where('classification_project_id', $trialProjectId)
                        ->delete();
                }

                $student->forceFill(['is_trial' => true])->save();

                $trial->update([
                    'advising_session_id' => null,
                    'daily_study_hours' => null,
                    'status' => TrialWeek::STATUS_PENDING,
                    'expires_at' => null,
                    'supporter_assigned_at' => null,
                    'classification_locked_at' => null,
                    'pre_session_completed_at' => null,
                    'program_built_at' => null,
                    'dashboard_notice_acknowledged_at' => null,
                    'trial_ended_sms_sent_at' => null,
                    'acq_disinterest_status' => null,
                    'acq_disinterest_reason' => null,
                    'acq_disinterest_at' => null,
                ]);

                return $trial->id;
            });

            if ($trialId) {
                app(TrialWeekService::class)->autoAssignAcquisitionConsultant(
                    TrialWeek::query()->findOrFail($trialId)
                );

                $converted++;
            }
        }
    });

    $this->info("Matched={$matched}, converted={$converted}, skipped_paid={$skippedPaid}, skipped_missing={$skippedMissing}.");
})->purpose('Convert exam-program trial users back to the ordinary one-week trial flow');

Schedule::command('advising-sessions:mark-advisor-absent')->everyFiveMinutes();
Schedule::command('sms:send-lifecycle-notifications')->hourly()->withoutOverlapping();
