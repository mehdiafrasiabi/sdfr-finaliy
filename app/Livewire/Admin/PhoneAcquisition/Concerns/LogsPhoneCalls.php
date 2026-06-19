<?php

namespace App\Livewire\Admin\PhoneAcquisition\Concerns;

use App\Models\PhoneCall;
use App\Models\PhoneLead;
use App\Models\PhoneLeadAssignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * منطق مشترک «ثبت تماس» برای پنل مشاور جذب تلفنی (صف و پیگیری‌ها).
 */
trait LogsPhoneCalls
{
    /** شماره‌ای که در حال ثبت تماس برای آن هستیم. */
    public ?int $activeLeadId = null;

    /** حالت فرم: success (تماس برقرار شد) یا fail (ناموفق در برقراری تماس). */
    public string $callMode = 'success';

    // شاخهٔ موفق
    public string $spokeWith = '';
    public $willingness = null;
    public string $lowWillingnessReason = '';
    public string $result = '';
    public string $followUpAt = ''; // مقدار میلادی "Y-m-d H:i" که تقویم شمسی پر می‌کند
    public string $summary = '';

    // شاخهٔ ناموفق
    public string $failReason = '';

    public function openCallForm(int $leadId): void
    {
        $this->resetCallForm();
        $this->activeLeadId = $leadId;
        // راه‌اندازی مجدد تقویم شمسی پس از باز شدن مودال
        $this->dispatch('phone-call-form-opened');
    }

    public function closeCallForm(): void
    {
        $this->resetCallForm();
    }

    public function setCallMode(string $mode): void
    {
        $this->callMode = $mode === 'fail' ? 'fail' : 'success';
        $this->resetErrorBag();
    }

    protected function resetCallForm(): void
    {
        $this->activeLeadId        = null;
        $this->callMode            = 'success';
        $this->spokeWith           = '';
        $this->willingness         = null;
        $this->lowWillingnessReason = '';
        $this->result              = '';
        $this->followUpAt          = '';
        $this->summary             = '';
        $this->failReason          = '';
        $this->resetErrorBag();
    }

    public function logCall(): void
    {
        $lead = $this->loadLeadForConsultant($this->activeLeadId);
        if (! $lead) {
            $this->dispatch('warning', 'شماره یافت نشد یا به شما اختصاص ندارد.');
            $this->closeCallForm();
            return;
        }

        if ($lead->isExhausted()) {
            $this->dispatch('warning', 'این شماره دیگر قابل تماس نیست (خاکستری).');
            $this->closeCallForm();
            return;
        }

        if ($this->callMode === 'fail') {
            $this->validate([
                'failReason' => ['required', 'in:no_answer,off,rejected,wrong'],
            ], [
                'failReason.required' => 'علت عدم برقراری تماس را انتخاب کنید.',
            ]);
        } else {
            $this->validate([
                'spokeWith'            => ['required', 'in:father,mother,student,other'],
                'willingness'          => ['required', 'integer', 'between:0,100'],
                'lowWillingnessReason' => ['nullable', 'string', 'max:2000'],
                'result'               => ['required', 'in:registered,follow_up,no_interest'],
                'followUpAt'           => ['required_if:result,follow_up', 'nullable', 'date'],
                'summary'              => ['nullable', 'string', 'max:5000'],
            ], [
                'spokeWith.required'   => 'تعیین کنید با چه شخصی صحبت شده است.',
                'willingness.required' => 'درصد تمایل به همکاری را وارد کنید.',
                'willingness.between'  => 'درصد تمایل باید بین ۰ تا ۱۰۰ باشد.',
                'result.required'      => 'نتیجهٔ تماس را انتخاب کنید.',
                'followUpAt.required_if' => 'برای پیگیری، تاریخ و ساعت را مشخص کنید.',
            ]);

            // اگر تمایل زیر ۵۰٪ است، علت اجباری است.
            if ((int) $this->willingness < 50 && trim($this->lowWillingnessReason) === '') {
                $this->addError('lowWillingnessReason', 'چون تمایل زیر ۵۰٪ است، علت عدم تمایل را بنویسید.');
                return;
            }
        }

        $this->applyOutcome($lead);

        $this->dispatch('success', 'تماس با موفقیت ثبت شد.');
        $this->closeCallForm();
    }

    protected function applyOutcome(PhoneLead $lead): void
    {
        DB::transaction(function () use ($lead) {
            $attempt = $lead->attempts_count + 1;

            PhoneCall::create([
                'phone_lead_id'          => $lead->id,
                'admin_id'               => Auth::guard('admin')->id(),
                'attempt_number'         => $attempt,
                'connected'              => $this->callMode === 'success',
                'fail_reason'            => $this->callMode === 'fail' ? $this->failReason : null,
                'spoke_with'             => $this->callMode === 'success' ? $this->spokeWith : null,
                'willingness'            => $this->callMode === 'success' ? (int) $this->willingness : null,
                'low_willingness_reason' => $this->callMode === 'success' && (int) $this->willingness < 50
                    ? ($this->lowWillingnessReason ?: null) : null,
                'result'                 => $this->callMode === 'success' ? $this->result : null,
                'follow_up_at'           => $this->callMode === 'success' && $this->result === PhoneCall::RESULT_FOLLOW_UP
                    ? $this->followUpAt : null,
                'summary'                => $this->callMode === 'success' ? ($this->summary ?: null) : null,
                'called_at'              => now(),
            ]);

            $lead->attempts_count = $attempt;
            $lead->last_outcome = $this->callMode === 'fail' ? $this->failReason : $this->result;
            $lead->status = PhoneLead::statusAfterOutcome(
                $attempt,
                $this->callMode === 'success',
                $this->callMode === 'fail' ? $this->failReason : null,
                $this->callMode === 'success' ? $this->result : null,
            );

            $lead->save();

            // اگر شماره بسته/مرده شد، اختصاص فعال آن «انجام‌شده» می‌شود.
            if (in_array($lead->status, [PhoneLead::STATUS_CLOSED, PhoneLead::STATUS_DEAD], true)) {
                PhoneLeadAssignment::where('phone_lead_id', $lead->id)
                    ->where('admin_id', Auth::guard('admin')->id())
                    ->where('status', PhoneLeadAssignment::STATUS_ACTIVE)
                    ->update(['status' => PhoneLeadAssignment::STATUS_DONE]);
            }
        });
    }

    /**
     * شماره را فقط اگر به مشاور فعلی به‌صورت فعال اختصاص یافته باشد برمی‌گرداند.
     */
    protected function loadLeadForConsultant(?int $leadId): ?PhoneLead
    {
        if (! $leadId) {
            return null;
        }

        return PhoneLead::whereHas('assignments', function ($q) {
            $q->where('admin_id', Auth::guard('admin')->id())
                ->where('status', PhoneLeadAssignment::STATUS_ACTIVE);
        })->find($leadId);
    }
}
