<?php

namespace App\Services;

use App\Models\AcquisitionCall;
use App\Models\TrialWeek;
use Carbon\Carbon;

class AcquisitionCallService
{
    /**
     * مرحله بعدی تماس برای پشتیبان جذب چیست؟
     *
     * - اگر تماس اولیه answered نشده → باید initial گرفته شود.
     * - اگر initial answered و ≥ ۲ روز از supporter_assigned_at گذشته و secondary answered نشده → secondary.
     * - اگر هردو answered → side (اختیاری).
     */
    public function nextCallType(TrialWeek $trial): string
    {
        $initial = $trial->acquisitionCalls->firstWhere(fn ($c) => $c->type === AcquisitionCall::TYPE_INITIAL && $c->status === AcquisitionCall::STATUS_ANSWERED);
        if (!$initial) {
            return AcquisitionCall::TYPE_INITIAL;
        }

        $secondaryDue = $trial->supporter_assigned_at && Carbon::parse($trial->supporter_assigned_at)->addDays(2)->isPast();
        $secondary = $trial->acquisitionCalls->firstWhere(fn ($c) => $c->type === AcquisitionCall::TYPE_SECONDARY && $c->status === AcquisitionCall::STATUS_ANSWERED);

        if ($secondaryDue && !$secondary) {
            return AcquisitionCall::TYPE_SECONDARY;
        }

        return AcquisitionCall::TYPE_SIDE;
    }

    public function secondaryCallDue(TrialWeek $trial): bool
    {
        return $this->nextCallType($trial) === AcquisitionCall::TYPE_SECONDARY;
    }

    /**
     * آیا دانش‌آموز ۳ روز هیچ فعالیتی نداشته؟
     */
    public function isStudentInactive(TrialWeek $trial): bool
    {
        $student = $trial->student;
        if (!$student) {
            return false;
        }
        $threshold = Carbon::now()->subDays(3);
        $lastReport = $student->dailyReports()->latest('created_at')->value('created_at');
        $lastStudy = $student->studySessions()->latest('created_at')->value('created_at');
        $latest = collect([$lastReport, $lastStudy])->filter()->max();
        return !$latest || Carbon::parse($latest)->isBefore($threshold);
    }
}
