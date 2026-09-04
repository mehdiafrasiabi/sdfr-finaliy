<?php

namespace App\Observers;

use App\Models\AdvisingPreSession;
use App\Models\AdvisingPreSessionAssignment;
use App\Models\AdvisingPreSessionExam;
use App\Models\AdvisingPreSessionMisc;
use App\Models\AdvisingPreSessionQa;
use App\Models\AdvisingPreSessionRequestedPart;
use App\Models\ClassSchedule;
use App\Models\DailyReport;
use App\Models\MakeupSession;
use App\Models\PhoneRegistrationLink;
use App\Models\PhoneCall;
use App\Models\PhoneLead;
use App\Models\PhoneLeadAssignment;
use App\Models\StudyPartSession;
use App\Models\TrialWeek;
use App\Services\AdminNotificationService;

class StudentActivityObserver
{
    public function dailyReportCreated(DailyReport $report): void
    {
        if ($report->student) {
            $name = $this->studentName($report->student);
            app(AdminNotificationService::class)->notifyTrialStudentAction(
                $report->student,
                'daily_report_created',
                'گزارش روزانه جدید',
                "{$name} گزارش روزانه ارسال کرد."
            );
        }
    }

    public function studyPartSessionCreated(StudyPartSession $session): void
    {
        if ($session->student) {
            $name = $this->studentName($session->student);
            $part = $session->programPart?->lesson_name ?: 'یک پارت';
            app(AdminNotificationService::class)->notifyTrialStudentAction(
                $session->student,
                'study_part_completed',
                'ثبت مطالعه پارت',
                "{$name} مطالعه {$part} را ثبت کرد."
            );
        }
    }

    public function makeupSessionCreated(MakeupSession $session): void
    {
        if ($session->student) {
            $name = $this->studentName($session->student);
            app(AdminNotificationService::class)->notifyTrialStudentAction(
                $session->student,
                'makeup_session_created',
                'مطالعه جبرانی جدید',
                "{$name} یک مطالعه جبرانی ثبت کرد."
            );
        }
    }

    public function trialWeekUpdated(TrialWeek $trial): void
    {
        if ($trial->wasChanged('assessments_completed_at') && $trial->assessments_completed_at) {
            app(AdminNotificationService::class)->notifyTrialWeek(
                $trial,
                'trial_assessments_completed',
                'آزمون‌های ورودی تکمیل شد',
                $this->trialName($trial) . ' آزمون‌های ورودی را تکمیل کرد.'
            );
        }

        if ($trial->wasChanged('status')) {
            app(AdminNotificationService::class)->notifyTrialWeek(
                $trial,
                'trial_status_changed',
                'مرحله هفته آزمایشی تغییر کرد',
                $this->trialName($trial) . ' اکنون در مرحله «' . $trial->status_label . '» است.'
            );
        }
    }

    public function preSessionUpdated(AdvisingPreSession $preSession): void
    {
        if ($preSession->wasChanged('status') && $preSession->status === AdvisingPreSession::STATUS_COMPLETED) {
            $student = $preSession->student;
            if ($student) {
                app(AdminNotificationService::class)->notifyTrialStudentAction(
                    $student,
                    'pre_session_completed',
                    'پیش‌جلسه تکمیل شد',
                    $this->studentName($student) . ' پیش‌جلسه را نهایی کرد.'
                );
            }
        }
    }

    public function classScheduleUpdated(ClassSchedule $schedule): void
    {
        if ($schedule->wasChanged('is_finalized') && $schedule->is_finalized && $schedule->student) {
            app(AdminNotificationService::class)->notifyTrialStudentAction(
                $schedule->student,
                'class_schedule_finalized',
                'برنامه کلاسی نهایی شد',
                $this->studentName($schedule->student) . ' برنامه کلاسی را نهایی کرد.'
            );
        }
    }

    public function preSessionItemCreated($item): void
    {
        $preSession = $item->preSession;
        $student = $preSession?->student;

        if (!$student) {
            return;
        }

        app(AdminNotificationService::class)->notifyTrialStudentAction(
            $student,
            'pre_session_item_created',
            'اطلاعات پیش‌جلسه اضافه شد',
            $this->studentName($student) . ' در بخش پیش‌جلسه «' . $this->preSessionItemLabel($item) . '» اضافه کرد.'
        );
    }

    public function phoneRegistrationLinkUpdated(PhoneRegistrationLink $link): void
    {
        if ($link->wasChanged('registered_user_id') && $link->registered_user_id) {
            $link->loadMissing('lead');

            if ($link->lead) {
                $link->lead->forceFill([
                    'status' => PhoneLead::STATUS_CLOSED,
                    'last_outcome' => PhoneCall::RESULT_REGISTERED,
                    'next_call_at' => null,
                    'disinterest_status' => null,
                    'disinterest_reason' => null,
                    'disinterest_at' => null,
                ])->save();

                PhoneLeadAssignment::where('phone_lead_id', $link->phone_lead_id)
                    ->where('admin_id', $link->admin_id)
                    ->where('status', PhoneLeadAssignment::STATUS_ACTIVE)
                    ->update(['status' => PhoneLeadAssignment::STATUS_DONE]);
            }

            app(AdminNotificationService::class)->notifyPhoneRegistration($link);
        }
    }

    private function preSessionItemLabel($item): string
    {
        return match (true) {
            $item instanceof AdvisingPreSessionExam => 'امتحان',
            $item instanceof AdvisingPreSessionQa => 'پرسش و پاسخ',
            $item instanceof AdvisingPreSessionAssignment => 'تکلیف',
            $item instanceof AdvisingPreSessionRequestedPart => 'پارت درخواستی',
            $item instanceof AdvisingPreSessionMisc => 'متفرقه',
            default => 'اطلاعات جدید',
        };
    }

    private function studentName($student): string
    {
        return $student->user?->personalInformation?->name
            ?? $student->user?->profile?->full_name
            ?? $student->user?->name
            ?? 'دانش‌آموز';
    }

    private function trialName(TrialWeek $trial): string
    {
        return $trial->user?->personalInformation?->name
            ?? $trial->user?->profile?->full_name
            ?? $trial->user?->name
            ?? 'دانش‌آموز';
    }
}
