<?php

namespace App\Services;

use App\Models\ClassificationProject;
use App\Models\GradePrice;
use App\Models\Installment;
use App\Models\InstallmentPlan;
use App\Models\Payment;
use App\Models\PersonalInformation;
use App\Models\Student;
use App\Models\StudentClassification;
use App\Models\TrialWeek;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;

/**
 * نهایی‌سازی یک پرداختِ موفق پس از بازگشت از درگاه:
 *   - course_full          → ثبت پایان دسترسی + رسمی‌کردن دانش‌آموز.
 *   - installment_initial  → فعال‌سازی طرح اقساطی + ثبت پایان دسترسی.
 *   - installment          → علامت‌زدن آن قسط به‌عنوان پرداخت‌شده.
 * این متد idempotent است (اجرای دوباره مشکلی ایجاد نمی‌کند).
 */
class PurchaseFinalizer
{
    public function finalize(Payment $payment): void
    {
        if ($payment->status !== 'completed') {
            return;
        }

        $purpose = $payment->purpose ?: Payment::PURPOSE_COURSE_FULL;

        match ($purpose) {
            Payment::PURPOSE_INSTALLMENT_INITIAL => $this->finalizeInstallmentInitial($payment),
            Payment::PURPOSE_INSTALLMENT         => $this->finalizeInstallment($payment),
            Payment::PURPOSE_INSTALLMENT_BULK    => $this->finalizeInstallmentBulk($payment),
            default                              => $this->finalizeCourseFull($payment),
        };
    }

    protected function finalizeCourseFull(Payment $payment): void
    {
        DB::transaction(function () use ($payment) {
            $student = Student::firstOrCreate(
                ['user_id' => $payment->user_id],
                ['is_trial' => false],
            );

            $student->update([
                'is_trial'       => false,
                'payment_id'     => $payment->id,
                'access_ends_at' => $this->resolveAccessEnd($payment),
            ]);

            $this->applyTrialClassificationToActiveProject($payment->user_id);
        });

        app(TrialLifecycleSmsService::class)->trySendPurchaseCompleted($payment);
    }

    protected function finalizeInstallmentInitial(Payment $payment): void
    {
        $plan = $payment->installment_plan_id
            ? InstallmentPlan::find($payment->installment_plan_id)
            : null;

        if (! $plan) {
            // اگر به هر دلیل طرح پیدا نشد، حداقل مثل خرید کامل دسترسی بده.
            $this->finalizeCourseFull($payment);
            return;
        }

        DB::transaction(function () use ($payment, $plan) {
            $student = Student::firstOrCreate(
                ['user_id' => $payment->user_id],
                ['is_trial' => false],
            );

            $accessEnd = $plan->access_ends_at ?: $this->resolveAccessEnd($payment);

            $student->update([
                'is_trial'       => false,
                'payment_id'     => $payment->id,
                'access_ends_at' => $accessEnd,
            ]);

            if ($plan->status === InstallmentPlan::STATUS_PENDING) {
                $plan->update([
                    'status'             => InstallmentPlan::STATUS_ACTIVE,
                    'initial_payment_id' => $payment->id,
                    'student_id'         => $student->id,
                    'access_ends_at'     => $accessEnd,
                ]);
            }

            $this->applyTrialClassificationToActiveProject($payment->user_id);
        });

        app(TrialLifecycleSmsService::class)->trySendPurchaseCompleted($payment);
    }

    protected function finalizeInstallment(Payment $payment): void
    {
        $installment = $payment->installment_id
            ? Installment::find($payment->installment_id)
            : null;

        if (! $installment || $installment->isPaid()) {
            return;
        }

        DB::transaction(function () use ($payment, $installment) {
            $installment->update([
                'status'     => Installment::STATUS_PAID,
                'payment_id' => $payment->id,
                'paid_at'    => Carbon::now(),
            ]);

            $installment->plan?->refreshCompletion();
        });
    }

    protected function finalizeInstallmentBulk(Payment $payment): void
    {
        $installmentIds = $payment->installment_ids ?? [];
        if (empty($installmentIds)) {
            return;
        }

        $installments = Installment::whereIn('id', $installmentIds)->where('status', 'pending')->get();
        $plan = null;

        DB::transaction(function () use ($payment, $installments, &$plan) {
            foreach ($installments as $installment) {
                $installment->update([
                    'status'     => Installment::STATUS_PAID,
                    'payment_id' => $payment->id,
                    'paid_at'    => Carbon::now(),
                ]);
                if (!$plan) {
                    $plan = $installment->plan;
                }
            }
        });

        // After the transaction, refresh the plan's completion status once.
        $plan?->refreshCompletion();
    }

    /**
     * پس از خریدِ دانش‌آموز، طبقه‌بندیِ او در پروژهٔ آزمایشیِ همیشه‌فعال (is_trial=true) را
     * روی «آخرین پروژهٔ طبقه‌بندیِ فعالِ» غیرآزمایشی اعمال (overwrite) می‌کند تا مجبور به
     * پر کردن دوبارهٔ طبقه‌بندی نشود (چون فاصلهٔ زمانی—معمولاً حدود یک هفته—تأثیر زیادی
     * روی صحتِ رتبه‌ها ندارد). idempotent است: اجرای دوباره فقط همان مقادیر را بازنویسی می‌کند.
     */
    protected function applyTrialClassificationToActiveProject(int $userId): void
    {
        $trialProject = ClassificationProject::where('is_trial', true)
            ->where('is_active', true)
            ->first();

        if (! $trialProject) {
            return;
        }

        $trialRatings = StudentClassification::where('user_id', $userId)
            ->where('classification_project_id', $trialProject->id)
            ->get(['ratable_type', 'ratable_id', 'rating']);

        if ($trialRatings->isEmpty()) {
            return;
        }

        $targetProject = ClassificationProject::where('is_trial', false)
            ->where('is_active', true)
            ->where('start_at', '<=', now())
            ->where('end_at', '>=', now())
            ->latest()
            ->first();

        if (! $targetProject || $targetProject->id === $trialProject->id) {
            return;
        }

        foreach ($trialRatings as $ratingRow) {
            StudentClassification::updateOrCreate(
                [
                    'user_id'                   => $userId,
                    'classification_project_id' => $targetProject->id,
                    'ratable_type'               => $ratingRow->ratable_type,
                    'ratable_id'                 => $ratingRow->ratable_id,
                ],
                ['rating' => $ratingRow->rating]
            );
        }
    }

    /**
     * پایان دسترسی برای خرید کامل: از قیمتِ فعالِ پایهٔ دانش‌آموز؛ در غیر این صورت
     * پایان خرداد سالِ خدمتِ جاری.
     */
    protected function resolveAccessEnd(Payment $payment): Carbon
    {
        $pi = PersonalInformation::where('user_id', $payment->user_id)->first();
        if ($pi && $pi->grade) {
            $grade = $pi->is_graduate ? TrialWeek::GRADE_GRADUATE : (int) $pi->grade;
            $price = GradePrice::activeFor($grade) ?? GradePrice::where('is_active', true)->first();
            if ($price && $price->accessEndsAt()) {
                return $price->accessEndsAt();
            }
        }

        return $this->khordadEndFor(Carbon::now());
    }

    /** پایان خرداد سالِ خدمتی که تاریخ داده‌شده در آن قرار دارد. */
    protected function khordadEndFor(Carbon $at): Carbon
    {
        $j = Jalalian::fromCarbon($at);
        $jMonth = (int) $j->getMonth();
        $jYear  = (int) $j->getYear();
        // اگر در نیمهٔ دومِ سال خدمت (تیر..اسفند) هستیم، خردادِ سالِ بعد؛ وگرنه همین سال.
        $khordadYear = $jMonth >= 4 ? $jYear + 1 : $jYear;
        return Jalalian::fromFormat('Y/m/d', sprintf('%d/03/31', $khordadYear))
            ->toCarbon()
            ->endOfDay();
    }
}
