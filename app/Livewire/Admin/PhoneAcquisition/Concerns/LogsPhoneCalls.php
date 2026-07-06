<?php

namespace App\Livewire\Admin\PhoneAcquisition\Concerns;

use App\Models\PhoneCall;
use App\Models\PhoneLead;
use App\Models\PhoneLeadAssignment;
use App\Services\PhoneLeadScheduler;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * منطق مشترک «ثبت تماس» برای پنل مشاور جذب تلفنی (صف و پیگیری‌ها).
 */
trait LogsPhoneCalls
{
    /** شماره‌ای که در حال ثبت تماس برای آن هستیم. */
    public ?int $activeLeadId = null;

    /** رکورد تماسِ «پاسخ‌داده‌شده» که در حال تکمیل آن هستیم (شاخهٔ موفق). */
    public ?int $activeCallId = null;

    /** مدت مکالمه (ثانیه) که از سمت کلاینت می‌آید. */
    public ?int $talkSeconds = null;

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

    /** فاز مودال تماس (سمت سرور، قطعی): ringing | talking | answerForm | noAnswerForm */
    public string $callPhase = 'ringing';

    /** آیا لینک ثبت‌نام برای این تماس ارسال شده است؟ (جلوگیری از ارسال دوباره) */
    public bool $linkSent = false;
    public ?string $sentLinkUrl = null;

    public function openCallForm(int $leadId): void
    {
        $this->resetCallForm();
        $this->activeLeadId = $leadId;
        $this->callPhase    = 'ringing';
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
        $this->activeCallId        = null;
        $this->talkSeconds         = null;
        $this->callMode            = 'success';
        $this->spokeWith           = '';
        $this->willingness         = null;
        $this->lowWillingnessReason = '';
        $this->result              = '';
        $this->followUpAt          = '';
        $this->summary             = '';
        $this->failReason          = '';
        $this->callPhase           = 'ringing';
        $this->linkSent            = false;
        $this->sentLinkUrl         = null;
        $this->resetErrorBag();
    }

    /**
     * «پاسخ کاربر» — تماس برقرار شد. رکورد تماس همین‌جا ذخیره می‌شود تا
     * حتی اگر فرم تکمیل نشود، گم نشود. سپس تایمر مکالمه در کلاینت شروع می‌شود.
     */
    public function markCallAnswered(): void
    {
        if ($this->activeCallId) {
            return; // قبلاً ثبت شده — جلوگیری از ثبت دوباره با کلیک مکرر
        }

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

        $this->callMode = 'success';

        $call = DB::transaction(function () use ($lead) {
            $attempt = $lead->attempts_count + 1;

            $call = PhoneCall::create([
                'phone_lead_id'  => $lead->id,
                'admin_id'       => Auth::guard('admin')->id(),
                'attempt_number' => $attempt,
                'connected'      => true,
                'answered_at'    => now(),
                'called_at'      => now(),
            ]);

            $lead->attempts_count = $attempt;
            $lead->save();

            return $call;
        });

        $this->activeCallId = $call->id;
        $this->callPhase    = 'talking';
    }

    /**
     * «اتمام مکالمه» — مدت مکالمه (ثانیه) از کلاینت ذخیره می‌شود و به فرم پاسخ می‌رویم.
     */
    public function endConversation(int $seconds): void
    {
        $this->talkSeconds = max(0, $seconds);

        if ($this->activeCallId) {
            PhoneCall::where('id', $this->activeCallId)
                ->update(['talk_duration_seconds' => $this->talkSeconds]);
        }

        $this->callPhase = 'answerForm';
        // فرم پاسخ تازه رندر می‌شود؛ تقویم شمسی باید دوباره مقداردهی شود.
        $this->dispatch('phone-call-form-opened');
    }

    /**
     * «عدم پاسخ» — به فرم عدم‌پاسخ می‌رویم؛ ثبت نهایی هنگام logCall انجام می‌شود.
     */
    public function markNoAnswer(): void
    {
        $this->callMode   = 'fail';
        $this->failReason = PhoneCall::FAIL_NO_ANSWER;
        $this->callPhase  = 'noAnswerForm';
        $this->resetErrorBag();
    }

    /**
     * لغو تماس — فقط در فاز زنگ‌خوردن (قبل از پاسخ/عدم‌پاسخ) مجاز است.
     */
    public function cancelCall(): void
    {
        if ($this->callPhase === 'ringing') {
            $this->closeCallForm();
        }
    }

    /**
     * ارسال دستی «لینک یکتای ثبت‌نام» در حین تماس.
     */
    public function sendRegistrationLink(): void
    {
        $lead = $this->loadLeadForConsultant($this->activeLeadId);
        if (! $lead) {
            $this->dispatch('warning', 'شماره یافت نشد یا به شما اختصاص ندارد.');
            return;
        }

        $link = app(\App\Services\PhoneRegistrationService::class)
            ->createAndSend($lead, Auth::guard('admin')->id());

        $this->linkSent    = true;
        $this->sentLinkUrl = $link->url;
        $this->dispatch('success', 'لینک ثبت‌نام برای دانش‌آموز ارسال شد.');
    }

    public function logCall(): void
    {
        $lead = $this->loadLeadForConsultant($this->activeLeadId);
        if (! $lead) {
            $this->dispatch('warning', 'شماره یافت نشد یا به شما اختصاص ندارد.');
            $this->closeCallForm();
            return;
        }

        if ($this->callMode === 'fail') {
            // در شاخهٔ ناموفق رکورد جدید ساخته می‌شود؛ شمارهٔ خاکستری نباید تماس بخورد.
            if ($lead->isExhausted()) {
                $this->dispatch('warning', 'این شماره دیگر قابل تماس نیست (خاکستری).');
                $this->closeCallForm();
                return;
            }

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

        // نتیجهٔ «ثبت‌نام» → ساخت و ارسال لینک یکتای ثبت‌نام برای ردیابی تبدیل/پاداش.
        if ($this->callMode === 'success' && $this->result === PhoneCall::RESULT_REGISTERED && ! $this->linkSent) {
            // اگر در حین تماس لینک ارسال نشده بود، حالا ارسال کن.
            $link = app(\App\Services\PhoneRegistrationService::class)
                ->createAndSend($lead, Auth::guard('admin')->id());

            $this->dispatch('success', 'ثبت‌نام ثبت شد و لینک یکتای ثبت‌نام ارسال شد.');
        } else {
            $this->dispatch('success', 'تماس با موفقیت ثبت شد.');
        }

        $this->closeCallForm();
    }

    protected function applyOutcome(PhoneLead $lead): void
    {
        DB::transaction(function () use ($lead) {
            if ($this->callMode === 'success') {
                // تکمیلِ رکوردِ تماسِ پاسخ‌داده‌شده که در markCallAnswered ساخته شده است.
                $willingness = (int) $this->willingness;

                $call = $this->activeCallId ? PhoneCall::find($this->activeCallId) : null;

                $payload = [
                    'connected'              => true,
                    'spoke_with'             => $this->spokeWith,
                    'willingness'            => $willingness,
                    'low_willingness_reason' => $willingness < 50 ? ($this->lowWillingnessReason ?: null) : null,
                    'result'                 => $this->result,
                    'follow_up_at'           => $this->result === PhoneCall::RESULT_FOLLOW_UP ? $this->followUpAt : null,
                    'summary'                => $this->summary ?: null,
                ];

                if ($call) {
                    $call->update($payload);
                } else {
                    // حالت پشتیبان (اگر رکورد پاسخ‌گویی به هر دلیل وجود نداشت).
                    $attempt = $lead->attempts_count + 1;
                    PhoneCall::create($payload + [
                        'phone_lead_id'  => $lead->id,
                        'admin_id'       => Auth::guard('admin')->id(),
                        'attempt_number' => $attempt,
                        'answered_at'    => now(),
                        'called_at'      => now(),
                    ]);
                    $lead->attempts_count = $attempt;
                }

                $lead->last_outcome = $this->result;
                $lead->grey_reason  = null;
                $lead->status       = PhoneLead::statusAfterOutcome($lead->attempts_count, true, null, $this->result);
                // پیگیری مجدد موفق → زمان تماس بعدی؛ ثبت‌نام/عدم‌تمایل → بدون تماس بعدی.
                $lead->next_call_at = $this->result === PhoneCall::RESULT_FOLLOW_UP && $this->followUpAt
                    ? Carbon::parse($this->followUpAt)
                    : null;
            } else {
                // شاخهٔ ناموفق — رکورد تماس جدید.
                $attempt = $lead->attempts_count + 1;

                PhoneCall::create([
                    'phone_lead_id'  => $lead->id,
                    'admin_id'       => Auth::guard('admin')->id(),
                    'attempt_number' => $attempt,
                    'connected'      => false,
                    'fail_reason'    => $this->failReason,
                    'called_at'      => now(),
                ]);

                // منطق چرخهٔ حیات (same-day / next-day / خاکستری) بر اساس علت و تاریخچه.
                $schedule = app(PhoneLeadScheduler::class)->afterFail($lead, $this->failReason);

                $lead->attempts_count = $attempt;
                $lead->last_outcome   = $this->failReason;
                $lead->status         = $schedule['status'];
                $lead->grey_reason    = $schedule['grey_reason'];
                $lead->next_call_at   = $schedule['next_call_at'];
            }

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
