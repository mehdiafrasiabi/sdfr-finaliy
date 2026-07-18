<?php

namespace App\Livewire\Admin\PhoneAcquisition\Concerns;

use App\Models\PhoneCall;
use App\Models\PhoneLead;
use App\Models\PhoneLeadAssignment;
use App\Models\PhoneRegistrationLink;
use App\Services\PhoneLeadScheduler;
use App\Notifications\SendStudentPlanSms;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\AnonymousNotifiable;

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
    public ?int $activeCallId = null;
    public ?int $talkSeconds = null;
    public string $callMode = 'success';

    // Form fields
    public array $spokeWith = [];
    public string $spokeWithOther = '';
    public $willingness = null;
    public string $lowWillingnessReason = '';
    public string $result = '';
    public string $followUpAt = '';
    public string $summary = '';
    public string $failReason = '';
    public string $callPhase = 'ringing';
    public bool $linkSent = false;
    public ?string $sentLinkUrl = null;

    public function openCallForm(int $leadId): void
    {
        $this->resetCallForm();
        $this->activeLeadId = $leadId;
        $this->callPhase    = 'ringing';
        $this->dispatch('phone-call-form-opened');
    }

    public function promptCall(int $leadId): void
    {
        $lead = $this->loadLeadForConsultant($leadId);
        if (!$lead) {
            $this->dispatch('warning', 'شماره یافت نشد یا به شما اختصاص ندارد.');
            return;
        }

        $this->pendingCallLeadId = $lead->id;
        $this->pendingCallLeadLabel = ($lead->full_name ? $lead->full_name . ' — ' : '') . $lead->mobile;
        $this->showCallConfirmModal = true;
    }

    public function cancelCallPrompt(): void
    {
        $this->reset(['pendingCallLeadId', 'pendingCallLeadLabel', 'showCallConfirmModal']);
    }

    public function continueCallPrompt(): void
    {
        if (!$this->pendingCallLeadId) {
            $this->cancelCallPrompt();
            return;
        }

        $this->openCallForm($this->pendingCallLeadId);
        $this->cancelCallPrompt();
    }

    public function closeCallForm(): void
    {
        $this->resetCallForm();
    }

    protected function resetCallForm(): void
    {
        $this->reset([
            'activeLeadId', 'activeCallId', 'talkSeconds', 'callMode', 'spokeWith',
            'spokeWithOther', 'willingness', 'lowWillingnessReason', 'result',
            'followUpAt', 'summary', 'failReason', 'callPhase', 'linkSent', 'sentLinkUrl'
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

        if ($this->callMode === 'fail') {
            if ($lead->isExhausted()) {
                $this->dispatch('warning', 'این شماره دیگر قابل تماس نیست (خاکستری).');
                $this->closeCallForm();
                return;
            }
            $this->validate(['failReason' => ['required', 'in:no_answer,off,rejected,wrong']], ['failReason.required' => 'علت عدم برقراری تماس را انتخاب کنید.']);
        } else {
            $this->validate([
                'spokeWith'            => ['required', 'array', 'min:1'],
                'spokeWith.*'          => ['required', 'in:father,mother,student,other'],
                'willingness'          => ['required', 'integer', 'between:0,100'],
                'lowWillingnessReason' => ['nullable', 'string', 'max:2000'],
                'result'               => ['required', 'in:' . PhoneCall::RESULT_REGISTRATION_FOLLOW_UP . ',' . PhoneCall::RESULT_FOLLOW_UP . ',' . PhoneCall::RESULT_NO_INTEREST],
                'followUpAt'           => ['required_if:result,' . PhoneCall::RESULT_REGISTRATION_FOLLOW_UP . ',' . PhoneCall::RESULT_FOLLOW_UP, 'nullable', 'date'],
                'summary'              => ['nullable', 'string', 'max:5000'],
            ], [
                'spokeWith.required'   => 'تعیین کنید با چه شخصی صحبت شده است.',
                'willingness.required' => 'درصد تمایل به همکاری را وارد کنید.',
                'result.required'      => 'نتیجهٔ تماس را انتخاب کنید.',
                'followUpAt.required_if' => 'برای پیگیری، تاریخ و ساعت را مشخص کنید.',
            ]);

            if ((int) $this->willingness < 50 && trim($this->lowWillingnessReason) === '') {
                $this->addError('lowWillingnessReason', 'چون تمایل زیر ۵۰٪ است، علت عدم تمایل را بنویسید.');
                return;
            }
            if (in_array('other', $this->spokeWith, true) && trim($this->spokeWithOther) === '') {
                $this->addError('spokeWithOther', 'نام شخص دیگر را بنویسید.');
                return;
            }
        }

        $this->applyOutcome($lead);

        if ($this->callMode === 'success' && $this->result === PhoneCall::RESULT_REGISTERED && !$this->linkSent) {
            app(\App\Services\PhoneRegistrationService::class)->createAndSend($lead, Auth::guard('admin')->id());
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
                $willingness = (int) $this->willingness;
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
                    'willingness' => $willingness,
                    'low_willingness_reason' => $willingness < 50 ? $this->lowWillingnessReason : null,
                    'result' => $this->result,
                    'follow_up_at' => in_array($this->result, [PhoneCall::RESULT_FOLLOW_UP, PhoneCall::RESULT_REGISTRATION_FOLLOW_UP]) ? $this->followUpAt : null,
                    'summary' => $this->summary ?: null,
                    'called_at' => now(),
                ]);

                $lead->attempts_count++;
                $lead->last_outcome = $this->result;
                $lead->grey_reason = null;
                $lead->status = PhoneLead::statusAfterOutcome($lead->attempts_count, true, null, $this->result);
                $lead->next_call_at = in_array($this->result, [PhoneCall::RESULT_FOLLOW_UP, PhoneCall::RESULT_REGISTRATION_FOLLOW_UP]) && $this->followUpAt
                    ? Carbon::parse($this->followUpAt)
                    : null;
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
                $lead->last_outcome = $this->failReason;
                $lead->status = $schedule['status'];
                $lead->grey_reason = $schedule['grey_reason'];
                $lead->next_call_at = $schedule['next_call_at'];
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
}
