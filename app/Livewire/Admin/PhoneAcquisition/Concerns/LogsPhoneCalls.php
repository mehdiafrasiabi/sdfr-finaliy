<?php

namespace App\Livewire\Admin\PhoneAcquisition\Concerns;

use App\Models\PhoneCall;
use App\Models\PhoneLead;
use App\Models\PhoneLeadAssignment;
use App\Models\PhoneRegistrationLink;
use App\Services\PhoneLeadScheduler;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;

/**
 * منطق مشترک «ثبت تماس» برای پنل مشاور جذب تلفنی (صف و پیگیری‌ها).
 */
trait LogsPhoneCalls
{
    protected const TEST_SMS_MOBILE = '09940682693';

    public ?int $activeLeadId = null;
    public ?int $pendingCallLeadId = null;
    public string $pendingCallLeadLabel = '';
    public bool $showCallConfirmModal = false;
    public bool $pendingCallAllowsEarlyDisinterestCall = false;
    public ?int $activeCallId = null;
    public bool $activeCallAllowsEarlyDisinterestCall = false;
    public ?int $talkSeconds = null;
    public string $callMode = 'success';

    // Form fields
    public array $spokeWith = [];
    public string $spokeWithOther = '';
    public string $leadFullName = '';
    public bool $collectLeadFullName = false;
    public string $disinterestStatus = '';
    public string $disinterestReason = '';
    public string $result = '';
    public string $followUpAt = '';
    public string $summary = '';
    public string $failReason = '';
    public string $callPhase = 'ringing';
    public bool $linkSent = false;
    public ?string $sentLinkUrl = null;
    public int $inviteSendCount = 0;
    public ?int $inviteSendLimit = null;

    public function openCallForm(int $leadId, bool $allowEarlyDisinterestCall = false): void
    {
        $lead = $this->loadLeadForConsultant($leadId);
        if (!$lead) {
            $this->dispatch('warning', 'شماره یافت نشد یا به شما اختصاص ندارد.');
            return;
        }

        if (!$this->ensureLeadCanStartCall($lead, $allowEarlyDisinterestCall)) {
            return;
        }

        $this->resetCallForm();
        $this->activeLeadId = $leadId;
        $this->leadFullName = trim((string) $lead->full_name);
        $this->collectLeadFullName = $this->shouldCollectLeadFullName($lead);
        $this->inviteSendLimit = $this->inviteSendLimitForContext();
        $this->activeCallAllowsEarlyDisinterestCall = $allowEarlyDisinterestCall;
        $this->callPhase    = 'ringing';
        $this->dispatch('phone-call-form-opened');
    }

    public function promptCall(int $leadId, bool $allowEarlyDisinterestCall = false): void
    {
        $lead = $this->loadLeadForConsultant($leadId);
        if (!$lead) {
            $this->dispatch('warning', 'شماره یافت نشد یا به شما اختصاص ندارد.');
            return;
        }

        if (!$this->ensureLeadCanStartCall($lead, $allowEarlyDisinterestCall)) {
            return;
        }

        $this->pendingCallLeadId = $lead->id;
        $this->pendingCallLeadLabel = ($lead->full_name ? $lead->full_name . ' — ' : '') . $lead->mobile;
        $this->pendingCallAllowsEarlyDisinterestCall = $allowEarlyDisinterestCall;
        $this->showCallConfirmModal = true;
    }

    public function cancelCallPrompt(): void
    {
        $this->reset(['pendingCallLeadId', 'pendingCallLeadLabel', 'pendingCallAllowsEarlyDisinterestCall', 'showCallConfirmModal']);
    }

    public function continueCallPrompt(): void
    {
        if (!$this->pendingCallLeadId) {
            $this->cancelCallPrompt();
            return;
        }

        $this->openCallForm($this->pendingCallLeadId, $this->pendingCallAllowsEarlyDisinterestCall);
        $this->cancelCallPrompt();
    }

    public function closeCallForm(): void
    {
        $this->resetCallForm();
    }

    public function phoneCallResultOptions(): array
    {
        $options = [
            PhoneCall::RESULT_FOLLOW_UP => ['title' => 'نیاز پیگیری مجدد', 'desc' => 'با یادآور اجباری در صف پیگیری می‌ماند.'],
            PhoneCall::RESULT_REGISTERED => ['title' => 'ثبت نام', 'desc' => 'لینک ثبت‌نام یکتا ارسال می‌شود و موفقیت بعد از ساخت برنامه حساب می‌شود.'],
            PhoneCall::RESULT_NO_INTEREST => ['title' => 'عدم تمایل', 'desc' => 'نوع و علت عدم تمایل ثبت می‌شود.'],
        ];

        return array_intersect_key($options, array_flip($this->phoneCallResultValues()));
    }

    protected function phoneCallResultValues(): array
    {
        return [
            PhoneCall::RESULT_REGISTERED,
            PhoneCall::RESULT_FOLLOW_UP,
            PhoneCall::RESULT_NO_INTEREST,
        ];
    }

    protected function resetCallForm(): void
    {
        $this->reset([
            'activeLeadId', 'activeCallId', 'talkSeconds', 'callMode', 'spokeWith',
            'spokeWithOther', 'leadFullName', 'collectLeadFullName', 'disinterestStatus', 'disinterestReason', 'result',
            'followUpAt', 'summary', 'failReason', 'callPhase', 'linkSent', 'sentLinkUrl',
            'inviteSendCount', 'inviteSendLimit', 'activeCallAllowsEarlyDisinterestCall',
        ]);
        $this->resetErrorBag();
    }

    public function markCallAnswered(): void
    {
        $lead = $this->loadLeadForConsultant($this->activeLeadId);
        if (!$lead || $lead->isExhausted()) {
            $this->dispatch('warning', $lead ? 'این شماره دیگر قابل تماس نیست (خاکستری).' : 'شماره یافت نشد.');
            $this->closeCallForm();
            return;
        }
        if (!$this->ensureLeadCanStartCall($lead, $this->activeCallAllowsEarlyDisinterestCall)) {
            $this->closeCallForm();
            return;
        }
        $this->callMode = 'success';
        $this->callPhase = 'talking';
    }

    public function endConversation(int $seconds): void
    {
        $this->talkSeconds = max(0, $seconds);
        $this->callPhase = 'answerForm';
        $this->dispatch('phone-call-form-opened');
    }

    public function markNoAnswer(): void
    {
        $this->callMode   = 'fail';
        $this->failReason = PhoneCall::FAIL_NO_ANSWER;
        $this->callPhase  = 'noAnswerForm';
        $this->resetErrorBag();
    }
    
    public function cancelCall(): void
    {
        if ($this->callPhase === 'ringing') {
            $this->closeCallForm();
        }
    }

    public function logCall(): void
    {
        $lead = $this->loadLeadForConsultant($this->activeLeadId);
        if (!$lead) {
            $this->dispatch('warning', 'شماره یافت نشد یا به شما اختصاص ندارد.');
            $this->closeCallForm();
            return;
        }

        if (!$this->ensureLeadCanStartCall($lead, $this->activeCallAllowsEarlyDisinterestCall)) {
            $this->closeCallForm();
            return;
        }

        if ($this->callMode === 'fail') {
            if ($lead->isExhausted()) {
                $this->dispatch('warning', 'این شماره دیگر قابل تماس نیست (خاکستری).');
                $this->closeCallForm();
                return;
            }
            $this->validate(['failReason' => ['required', 'in:no_answer,off,rejected,wrong']], ['failReason.required' => 'علت عدم برقراری تماس را انتخاب کنید.']);
        } else {
            if ($this->collectLeadFullName) {
                $this->validate([
                    'leadFullName' => ['required', 'string', 'max:150', 'regex:/^\S+(?:\s+\S+)+$/u'],
                ], [
                    'leadFullName.required' => 'نام و نام خانوادگی مخاطب را وارد کنید.',
                    'leadFullName.max' => 'نام و نام خانوادگی نباید بیشتر از ۱۵۰ کاراکتر باشد.',
                    'leadFullName.regex' => 'نام و نام خانوادگی را به‌صورت کامل وارد کنید.',
                ]);

                $lead->full_name = preg_replace('/\s+/u', ' ', trim($this->leadFullName));
            }

            $this->validate([
                'spokeWith'            => ['required', 'array', 'min:1'],
                'spokeWith.*'          => ['required', 'in:father,mother,student,other'],
                'result'               => ['required', 'in:' . implode(',', $this->phoneCallResultValues())],
            ], [
                'spokeWith.required'   => 'تعیین کنید با چه شخصی صحبت شده است.',
                'result.required'      => 'نتیجهٔ تماس را انتخاب کنید.',
            ]);

            if (in_array('other', $this->spokeWith, true) && trim($this->spokeWithOther) === '') {
                $this->addError('spokeWithOther', 'نام شخص دیگر را بنویسید.');
                return;
            }

            if ($this->result === PhoneCall::RESULT_NO_INTEREST) {
                $this->validate([
                    'disinterestStatus' => ['required', 'in:' . PhoneCall::DISINTEREST_TEMPORARY . ',' . PhoneCall::DISINTEREST_DEFINITIVE],
                    'disinterestReason' => ['required', 'string', 'min:3', 'max:5000'],
                    'followUpAt' => [$this->disinterestStatus === PhoneCall::DISINTEREST_TEMPORARY ? 'required' : 'nullable', 'date', $this->notPastReminderRule()],
                ], [
                    'disinterestStatus.required' => 'نوع عدم تمایل را انتخاب کنید.',
                    'disinterestReason.required' => 'علت عدم تمایل را بنویسید.',
                    'followUpAt.required' => 'برای عدم تمایل موقت، تاریخ و ساعت یادآور الزامی است.',
                    'followUpAt.date' => 'تاریخ و ساعت یادآور معتبر نیست.',
                ]);
            } else {
                $this->validate([
                    'summary' => ['required', 'string', 'min:3', 'max:5000'],
                    'followUpAt' => [$this->result === PhoneCall::RESULT_FOLLOW_UP ? 'required' : 'nullable', 'date', $this->notPastReminderRule()],
                ], [
                    'summary.required' => 'خلاصه گفتگو را بنویسید.',
                    'followUpAt.required' => 'برای یادآور، تاریخ و ساعت را مشخص کنید.',
                    'followUpAt.date' => 'تاریخ و ساعت یادآور معتبر نیست.',
                ]);
            }
        }

        $this->applyOutcome($lead);

        if ($this->callMode === 'success' && $this->result === PhoneCall::RESULT_REGISTERED && !$this->linkSent) {
            app(\App\Services\PhoneRegistrationService::class)->createAndSend($lead, Auth::guard('admin')->id());
            $this->dispatch('success', 'لینک ثبت‌نام ارسال شد و شماره وارد صف ثبت‌نام شد.');
        } else {
            $this->dispatch('success', 'تماس با موفقیت ثبت شد.');
        }

        $this->closeCallForm();
    }

    public function sendInvite(string $planType): void
    {
        if ($this->inviteSendLimit !== null && $this->inviteSendCount >= $this->inviteSendLimit) {
            $this->dispatch('warning', 'در هر تماس حداکثر دو بار امکان ارسال لینک وجود دارد.');
            return;
        }

        if (! in_array($planType, [PhoneRegistrationLink::PLAN_TRIAL, PhoneRegistrationLink::PLAN_EXAM], true)) {
            $this->dispatch('warning', 'در جذب تلفنی فقط لینک ثبت‌نام هفته آزمایشی یا بازه امتحانات ارسال می‌شود.');
            return;
        }

        $lead = $this->loadLeadForConsultant($this->activeLeadId);
        if (!$lead) {
            $this->dispatch('warning', 'شماره یافت نشد یا به شما اختصاص ندارد.');
            return;
        }

        if (!$this->ensureLeadCanStartCall($lead, $this->activeCallAllowsEarlyDisinterestCall)) {
            return;
        }

        $adminId = Auth::guard('admin')->id();
        if (!$adminId) {
            $this->dispatch('warning', 'مشاور شناسایی نشد.');
            return;
        }

        try {
            $link = app(\App\Services\PhoneRegistrationService::class)
                ->createAndSend($lead, $adminId, $planType);

            $this->linkSent = true;
            $this->sentLinkUrl = $link->url;
            $this->inviteSendCount++;

            $label = $planType === PhoneRegistrationLink::PLAN_EXAM ? 'بازه امتحانات' : 'هفته آزمایشی';
            $this->dispatch('success', "لینک ثبت‌نام {$label} با موفقیت ارسال شد.");
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send invitation SMS.', [
                'lead_id' => $lead->id,
                'admin_id' => $adminId,
                'error' => $e->getMessage(),
            ]);
            $this->dispatch('error', 'خطا در ارسال پیامک. لطفاً با پشتیبانی تماس بگیرید.');
        }
    }

    protected function applyOutcome(PhoneLead $lead): void
    {
        DB::transaction(function () use ($lead) {
            if ($this->callMode === 'success') {
                $isNoInterest = $this->result === PhoneCall::RESULT_NO_INTEREST;
                $isTemporaryDisinterest = $isNoInterest && $this->disinterestStatus === PhoneCall::DISINTEREST_TEMPORARY;
                $storedResult = $this->result === PhoneCall::RESULT_FOLLOW_UP
                    ? $this->followUpResultForContext()
                    : $this->result;
                $followUpAt = ($this->result === PhoneCall::RESULT_FOLLOW_UP || $isTemporaryDisinterest) && $this->followUpAt
                    ? $this->parseReminderAt($this->followUpAt)
                    : null;

                PhoneCall::create([
                    'phone_lead_id' => $lead->id,
                    'admin_id' => Auth::guard('admin')->id(),
                    'attempt_number' => $lead->attempts_count + 1,
                    'connected' => true,
                    'answered_at' => now(),
                    'talk_duration_seconds' => $this->talkSeconds,
                    'spoke_with_people' => array_values(array_filter($this->spokeWith)),
                    'spoke_with_other' => in_array('other', $this->spokeWith, true) ? $this->spokeWithOther : null,
                    'spoke_with' => array_values(array_filter($this->spokeWith))[0] ?? null,
                    'disinterest_status' => $isNoInterest ? $this->disinterestStatus : null,
                    'disinterest_reason' => $isNoInterest ? $this->disinterestReason : null,
                    'result' => $storedResult,
                    'follow_up_at' => $followUpAt,
                    'summary' => $isNoInterest ? null : $this->summary,
                    'called_at' => now(),
                ]);

                $lead->attempts_count++;
                $lead->last_outcome = $storedResult;
                $lead->grey_reason = null;
                $lead->status = $isTemporaryDisinterest
                    ? PhoneLead::STATUS_ACTIVE
                    : PhoneLead::statusAfterOutcome($lead->attempts_count, true, null, $storedResult);
                $lead->next_call_at = $followUpAt;
                $lead->disinterest_status = $isNoInterest ? $this->disinterestStatus : null;
                $lead->disinterest_reason = $isNoInterest ? $this->disinterestReason : null;
                $lead->disinterest_at = $isNoInterest ? now() : null;
            } else {
                PhoneCall::create([
                    'phone_lead_id' => $lead->id,
                    'admin_id' => Auth::guard('admin')->id(),
                    'attempt_number' => $lead->attempts_count + 1,
                    'connected' => false,
                    'fail_reason' => $this->failReason,
                    'called_at' => now(),
                ]);
                
                $schedule = app(PhoneLeadScheduler::class)->afterFail($lead, $this->failReason);
                $lead->attempts_count++;
                $lead->last_outcome = $schedule['last_outcome'] ?? $this->failReason;
                $lead->status = $schedule['status'];
                $lead->grey_reason = $schedule['grey_reason'];
                $lead->next_call_at = $schedule['next_call_at'];
                $lead->disinterest_status = $schedule['disinterest_status'] ?? $lead->disinterest_status;
                $lead->disinterest_reason = $schedule['disinterest_reason'] ?? $lead->disinterest_reason;
                $lead->disinterest_at = $schedule['disinterest_at'] ?? $lead->disinterest_at;
            }
            $lead->save();

            if (in_array($lead->status, [PhoneLead::STATUS_CLOSED, PhoneLead::STATUS_DEAD])) {
                PhoneLeadAssignment::where('phone_lead_id', $lead->id)
                    ->where('admin_id', Auth::guard('admin')->id())
                    ->where('status', PhoneLeadAssignment::STATUS_ACTIVE)
                    ->update(['status' => PhoneLeadAssignment::STATUS_DONE]);
            }
        });
    }

    protected function loadLeadForConsultant(?int $leadId): ?PhoneLead
    {
        if (!$leadId) return null;
        return PhoneLead::whereHas('assignments', fn ($q) =>
            $q->where('admin_id', Auth::guard('admin')->id())
              ->where('status', PhoneLeadAssignment::STATUS_ACTIVE)
        )->find($leadId);
    }

    protected function ensureLeadCanStartCall(PhoneLead $lead, bool $allowEarlyDisinterestCall = false): bool
    {
        if ($lead->canStartPhoneAcquisitionCall($allowEarlyDisinterestCall)) {
            return true;
        }

        $this->dispatch('warning', $lead->disinterest_call_lock_message ?: 'این شماره در حال حاضر قابل تماس نیست.');

        return false;
    }

    protected function notPastReminderRule(): \Closure
    {
        return function (string $attribute, mixed $value, \Closure $fail): void {
            if (! $value) {
                return;
            }

            try {
                $reminderAt = $this->parseReminderAt($value);
            } catch (\Throwable) {
                return;
            }

            if ($reminderAt->lt(now()->startOfDay())) {
                $fail('تاریخ یادآور نمی‌تواند قبل از امروز باشد.');
            }
        };
    }

    protected function followUpResultForContext(): string
    {
        return PhoneCall::RESULT_FOLLOW_UP;
    }

    protected function shouldCollectLeadFullName(PhoneLead $lead): bool
    {
        return false;
    }

    protected function inviteSendLimitForContext(): ?int
    {
        return null;
    }

    protected function parseReminderAt(string $value): Carbon
    {
        $value = strtr(trim($value), [
            '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
            '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
            '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
            '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
        ]);

        if (preg_match('/^(1[234]\d{2})[\/-](\d{1,2})[\/-](\d{1,2})\s+(\d{1,2}):(\d{2})(?::(\d{2}))?$/', $value, $parts)) {
            $hasSeconds = isset($parts[6]) && $parts[6] !== '';
            $normalized = sprintf(
                $hasSeconds ? '%04d/%02d/%02d %02d:%02d:%02d' : '%04d/%02d/%02d %02d:%02d',
                ...array_map('intval', array_slice($parts, 1))
            );

            return Jalalian::fromFormat($hasSeconds ? 'Y/m/d H:i:s' : 'Y/m/d H:i', $normalized)->toCarbon();
        }

        return Carbon::parse($value);
    }
}
