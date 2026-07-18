<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\StudentExamSchedule;
use App\Models\TrialWeek;
use App\Models\User;
use App\Notifications\ExamProgramEndedSms;
use App\Notifications\ExamProgramStartedSms;
use App\Notifications\PurchaseCompletedSms;
use App\Notifications\TrialEndedSms;
use App\Notifications\TrialStartedSms;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class TrialLifecycleSmsService
{
    public function sendTrialStarted(TrialWeek $trialWeek, string $plan = 'trial'): bool
    {
        $trialWeek->refresh();
        if ($trialWeek->trial_started_sms_sent_at) {
            return false;
        }

        $user = $trialWeek->user;
        $mobile = $this->mobileFor($user);

        if ($plan === 'exam') {
            $notification = new ExamProgramStartedSms($mobile);
        } else {
            $notification = new TrialStartedSms($mobile);
        }
        $user->notify($notification);

        return (bool) TrialWeek::query()
            ->whereKey($trialWeek->id)
            ->whereNull('trial_started_sms_sent_at')
            ->update(['trial_started_sms_sent_at' => now()]);
    }

    public function sendTrialEnded(TrialWeek $trialWeek): bool
    {
        $trialWeek->loadMissing(['user.personalInformation', 'student']);
        if ($trialWeek->trial_ended_sms_sent_at || ! $trialWeek->isExpired()) {
            return false;
        }

        if ($trialWeek->student && ! $trialWeek->student->is_trial) {
            return false;
        }

        if ($this->hasBuiltExamProgram($trialWeek->user)) {
            return false;
        }

        $user = $trialWeek->user;
        $mobile = $this->mobileFor($user);
        $studentName = $this->studentNameFor($user);

        $user->notify(new TrialEndedSms($mobile, $studentName, $this->dashboardUrl()));

        return (bool) TrialWeek::query()
            ->whereKey($trialWeek->id)
            ->whereNull('trial_ended_sms_sent_at')
            ->update(['trial_ended_sms_sent_at' => now()]);
    }

    public function sendExamProgramStarted(StudentExamSchedule $schedule): bool
    {
        $schedule->loadMissing(['user', 'student']);
        if (
            $schedule->exam_program_started_sms_sent_at
            || ! $schedule->program_built_at
            || ! $this->isTrialExamSchedule($schedule)
        ) {
            return false;
        }

        $user = $schedule->user;
        $mobile = $this->mobileFor($user);

        $user->notify(new ExamProgramStartedSms($mobile));

        return (bool) StudentExamSchedule::query()
            ->whereKey($schedule->id)
            ->whereNull('exam_program_started_sms_sent_at')
            ->update(['exam_program_started_sms_sent_at' => now()]);
    }

    public function sendExamProgramEnded(StudentExamSchedule $schedule): bool
    {
        $schedule->loadMissing(['user.personalInformation', 'student']);
        if (
            $schedule->exam_program_ended_sms_sent_at
            || ! $schedule->access_expires_at
            || $schedule->access_expires_at->isFuture()
            || ! $this->isTrialExamSchedule($schedule)
        ) {
            return false;
        }

        $user = $schedule->user;
        $mobile = $this->mobileFor($user);
        $studentName = $this->studentNameFor($user);

        $user->notify(new ExamProgramEndedSms($mobile, $studentName, $this->dashboardUrl()));

        return (bool) StudentExamSchedule::query()
            ->whereKey($schedule->id)
            ->whereNull('exam_program_ended_sms_sent_at')
            ->update(['exam_program_ended_sms_sent_at' => now()]);
    }

    public function sendPurchaseCompleted(Payment $payment): bool
    {
        $payment->loadMissing('user');
        if ($payment->purchase_completed_sms_sent_at || $payment->status !== 'completed') {
            return false;
        }

        $user = $payment->user;
        $mobile = $this->mobileFor($user);

        $user->notify(new PurchaseCompletedSms($mobile));

        return (bool) Payment::query()
            ->whereKey($payment->id)
            ->whereNull('purchase_completed_sms_sent_at')
            ->update(['purchase_completed_sms_sent_at' => now()]);
    }

    public function trySendTrialStarted(TrialWeek $trialWeek, string $plan = 'trial'): bool
    {
        try {
            return $this->sendTrialStarted($trialWeek, $plan);
        } catch (\Throwable $e) {
            Log::critical('Failed to send trial started SMS', [
                'trial_week_id' => $trialWeek->id,
                'user_id' => $trialWeek->user_id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function trySendTrialEnded(TrialWeek $trialWeek): bool
    {
        try {
            return $this->sendTrialEnded($trialWeek);
        } catch (\Throwable $e) {
            Log::critical('Failed to send trial ended SMS', [
                'trial_week_id' => $trialWeek->id,
                'user_id' => $trialWeek->user_id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function trySendExamProgramStarted(StudentExamSchedule $schedule): bool
    {
        try {
            return $this->sendExamProgramStarted($schedule);
        } catch (\Throwable $e) {
            Log::critical('Failed to send exam program started SMS', [
                'student_exam_schedule_id' => $schedule->id,
                'user_id' => $schedule->user_id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function trySendExamProgramEnded(StudentExamSchedule $schedule): bool
    {
        try {
            return $this->sendExamProgramEnded($schedule);
        } catch (\Throwable $e) {
            Log::critical('Failed to send exam program ended SMS', [
                'student_exam_schedule_id' => $schedule->id,
                'user_id' => $schedule->user_id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function trySendPurchaseCompleted(Payment $payment): bool
    {
        try {
            return $this->sendPurchaseCompleted($payment);
        } catch (\Throwable $e) {
            Log::critical('Failed to send purchase completed SMS', [
                'payment_id' => $payment->id,
                'user_id' => $payment->user_id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    protected function mobileFor(?User $user): string
    {
        if (! $user?->mobile) {
            throw new RuntimeException('User mobile is missing.');
        }

        return $user->mobile;
    }

    protected function studentNameFor(User $user): string
    {
        $info = $user->personalInformation;
        $name = trim((string) ($info?->name ?: $user->name));

        return $name !== '' ? $name : 'دانش‌آموز';
    }

    protected function isTrialExamSchedule(StudentExamSchedule $schedule): bool
    {
        $student = $schedule->student;

        return (bool) (
            $student
            && $student->is_trial
            && ! $student->hasActivePaidAccess()
        );
    }

    protected function hasBuiltExamProgram(?User $user): bool
    {
        return (bool) $user?->examSchedules()
            ->whereNotNull('weekly_program_id')
            ->whereNotNull('program_built_at')
            ->exists();
    }

    protected function dashboardUrl(): string
    {
        return config('services.melipayamak.dashboard_url', 'https://sdfr.me/profile/dashboard');
    }
}
