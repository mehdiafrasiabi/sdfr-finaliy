<?php

namespace App\Services;

use App\Models\AdminNotification;
use App\Models\PhoneRegistrationLink;
use App\Models\Student;
use App\Models\TrialWeek;

class AdminNotificationService
{
    public function notifyAdmin(
        ?int $adminId,
        string $type,
        string $title,
        ?string $body = null,
        ?int $studentId = null,
        ?int $phoneLeadId = null,
        ?string $url = null
    ): ?AdminNotification {
        if (!$adminId) {
            return null;
        }

        return AdminNotification::create([
            'admin_id' => $adminId,
            'student_id' => $studentId,
            'phone_lead_id' => $phoneLeadId,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'url' => $url,
        ]);
    }

    public function notifyTrialStudentAction(Student $student, string $type, string $title, ?string $body = null): ?AdminNotification
    {
        $trial = $student->trialWeek()->first();

        if (!$trial?->acquisition_supporter_id) {
            return null;
        }

        return $this->notifyAdmin(
            adminId: (int) $trial->acquisition_supporter_id,
            type: $type,
            title: $title,
            body: $body,
            studentId: (int) $student->id,
            url: route('admin.trial-acquisition.monitor', $trial->id)
        );
    }

    public function notifyTrialWeek(TrialWeek $trial, string $type, string $title, ?string $body = null): ?AdminNotification
    {
        if (!$trial->acquisition_supporter_id) {
            return null;
        }

        return $this->notifyAdmin(
            adminId: (int) $trial->acquisition_supporter_id,
            type: $type,
            title: $title,
            body: $body,
            studentId: $trial->student_id ? (int) $trial->student_id : null,
            url: route('admin.trial-acquisition.monitor', $trial->id)
        );
    }

    public function notifyPhoneRegistration(PhoneRegistrationLink $link): ?AdminNotification
    {
        $leadName = $link->lead?->full_name ?: 'دانش‌آموز';

        return $this->notifyAdmin(
            adminId: (int) $link->admin_id,
            type: 'phone_registration',
            title: 'ثبت‌نام از لینک جذب تلفنی',
            body: "{$leadName} از لینک اختصاصی شما ثبت‌نام کرد.",
            phoneLeadId: $link->phone_lead_id ? (int) $link->phone_lead_id : null,
            url: route('admin.phone-acquisition.my-leads')
        );
    }
}
