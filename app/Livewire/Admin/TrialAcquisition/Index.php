<?php

namespace App\Livewire\Admin\TrialAcquisition;

use App\Models\TrialAcquisitionCall;
use App\Models\TrialWeek;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filter = '';
    public ?int $callTrialId = null;
    public string $callStage = '';
    public ?int $pendingCallTrialId = null;
    public string $pendingCallStage = '';
    public bool $showCallConfirmModal = false;
    public bool $showPreCallModal = false;

    // Form State
    public ?int $activeTrialId = null;
    public string $activeStage = '';
    public ?int $activeCallId = null;
    public ?int $talkSeconds = null;
    public string $callPhase = 'ringing';
    public $answered = null;

    public array $spokeWith = [];
    public string $spokeWithOther = '';
    public string $welcomeCallSummary = '';
    public $probability = null;
    public string $probabilityNote = '';
    public bool $isDefinitive = false;
    public string $emergencyReason = '';
    public string $failReason = '';
    public string $otherFailReason = '';
    public string $notes = '';

    protected $queryString = [
        'search',
        'callTrialId' => ['except' => null],
        'callStage' => ['except' => ''],
    ];

    public function mount(): void
    {
        if ($this->callTrialId && $this->callStage) {
            $this->promptCall((int)$this->callTrialId, $this->callStage);
            $this->callTrialId = null;
            $this->callStage = '';
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilter(): void
    {
        $this->resetPage();
    }

    public function promptCall(int $trialId, string $stage): void
    {
        if (!$this->isAllowedCallStage($stage)) {
            $this->dispatch('warning', 'مرحله تماس معتبر نیست.');
            return;
        }

        $this->pendingCallTrialId = $trialId;
        $this->pendingCallStage = $stage;

        if ($stage === TrialAcquisitionCall::STAGE_DAY1) {
            $this->showPreCallModal = true;
        } else {
            $this->showCallConfirmModal = true;
        }
    }
    
    public function continueFromPreCall(): void
    {
        $this->showPreCallModal = false;
        $this->showCallConfirmModal = true;
    }

    public function cancelCallPrompt(): void
    {
        $this->reset(['pendingCallTrialId', 'pendingCallStage', 'showCallConfirmModal', 'showPreCallModal']);
    }

    public function continueCallPrompt(): void
    {
        if (!$this->pendingCallTrialId || !$this->pendingCallStage) {
            $this->cancelCallPrompt();
            return;
        }

        $this->openCallForm((int)$this->pendingCallTrialId, $this->pendingCallStage);
        $this->cancelCallPrompt();
    }

    public function openCallForm(int $trialId, string $stage): void
    {
        $this->resetCallForm();
        $this->activeTrialId = $trialId;
        $this->activeStage = $this->isAllowedCallStage($stage) ? $stage : TrialAcquisitionCall::STAGE_DAY1;

        $trial = $this->loadTrial($trialId);
        if ($trial) {
            $this->probability = $trial->acq_probability;
            $this->probabilityNote = $trial->acq_probability_note ?? '';
        }

        $this->callPhase = 'ringing';
        $this->dispatch('trial-call-form-opened');
    }

    public function closeCallForm(): void
    {
        $this->resetCallForm();
    }

    protected function resetCallForm(): void
    {
        $this->reset([
            'activeTrialId', 'activeStage', 'activeCallId', 'talkSeconds',
            'callPhase', 'answered', 'spokeWith', 'spokeWithOther',
            'welcomeCallSummary', 'probability', 'probabilityNote', 'isDefinitive',
            'emergencyReason', 'failReason', 'otherFailReason', 'notes',
        ]);
        $this->resetErrorBag();
    }

    public function markCallAnswered(): void
    {
        $this->answered = true;
        $this->talkSeconds = 0;
        $this->callPhase = 'talking';
        $this->resetErrorBag();
    }

    public function endConversation(int $seconds): void
    {
        $this->talkSeconds = max(0, $seconds);
        $this->callPhase = 'answerForm';
    }

    public function markNoAnswer(): void
    {
        $this->answered = false;
        $this->callPhase = 'noAnswerForm';
        $this->resetErrorBag();
    }

    protected function loadTrial(?int $trialId): ?TrialWeek
    {
        if (!$trialId) return null;
        return $this->applyStudentTypeScope(
            TrialWeek::where('acquisition_supporter_id', Auth::guard('admin')->id())
        )->find($trialId);
    }

    public function logCall(): void
    {
        $trial = $this->loadTrial($this->activeTrialId);
        if (!$trial) {
            $this->dispatch('warning', 'دانش‌آموز یافت نشد.');
            $this->closeCallForm();
            return;
        }

        $rules = ['notes' => ['nullable', 'string', 'max:5000']];
        $messages = [];

        if ($this->answered) {
            $rules['spokeWith'] = ['required', 'array', 'min:1'];
            $messages['spokeWith.required'] = 'حداقل یک گزینه برای "صحبت‌شده" انتخاب کنید.';

            if (in_array('other', $this->spokeWith, true)) {
                $rules['spokeWithOther'] = ['required', 'string', 'max:255'];
                $messages['spokeWithOther.required'] = 'نام شخص دیگر را بنویسید.';
            }

            if ($this->activeStage === TrialAcquisitionCall::STAGE_DAY1) {
                $rules['welcomeCallSummary'] = ['required', 'string', 'min:10', 'max:5000'];
                $messages['welcomeCallSummary.required'] = 'فیلد خلاصه تماس خوش آمد گویی الزامی است.';
            }

            if ($this->requiresProbability($this->activeStage)) {
                $rules['probability'] = ['required', 'integer', 'between:0,100'];
                $rules['probabilityNote'] = ['required', 'string', 'max:2000'];
                $messages['probability.required'] = 'درصد احتمال ثبت‌نام را وارد کنید.';
                $messages['probabilityNote.required'] = 'توضیحات احتمال ثبت‌نام را بنویسید.';
            }

            if ($this->activeStage === 'emergency') {
                 $rules['emergencyReason'] = ['required', 'string', 'max:2000'];
                 $messages['emergencyReason.required'] = 'علت تماس اضطراری را بنویسید.';
            }

        } else {
            $rules['failReason'] = ['required', 'in:no_answer,off,rejected,unavailable,other'];
            $messages['failReason.required'] = 'علت عدم پاسخ را انتخاب کنید.';
            if ($this->failReason === 'other') {
                $rules['otherFailReason'] = ['required', 'string', 'max:100'];
                $messages['otherFailReason.required'] = 'لطفاً علت دیگر را توضیح دهید.';
            }
        }

        $this->validate($rules, $messages);

        if ($this->answered && $this->requiresDefinitiveConfirmation($this->activeStage) && !$this->isDefinitive) {
            $this->addError('isDefinitive', 'برای ثبت نهایی، احتمال ثبت‌نام را به‌صورت قطعی تأیید کنید.');
            return;
        }

        DB::transaction(function () use ($trial) {
            $callData = [
                'trial_week_id' => $trial->id,
                'admin_id' => Auth::guard('admin')->id(),
                'stage' => $this->activeStage,
                'attempt_number' => TrialAcquisitionCall::where('trial_week_id', $trial->id)->where('stage', $this->activeStage)->where('answered', false)->count() + 1,
                'answered' => $this->answered,
                'called_at' => now(),
            ];

            if ($this->answered) {
                $callData += [
                    'talk_duration_seconds' => (int)($this->talkSeconds ?? 0),
                    'spoke_with_people' => array_values(array_filter($this->spokeWith)),
                    'spoke_with_other' => in_array('other', $this->spokeWith, true) ? $this->spokeWithOther : null,
                ];
                if ($this->activeStage === TrialAcquisitionCall::STAGE_DAY1) {
                    $callData['notes'] = $this->welcomeCallSummary;
                } else {
                    $callData['notes'] = $this->notes;
                }
                if ($this->requiresProbability($this->activeStage)) {
                     $trial->acq_probability = (int)$this->probability;
                     $trial->acq_probability_note = $this->probabilityNote;
                }
                if ($this->requiresDefinitiveConfirmation($this->activeStage)) {
                    $trial->acq_confirmed = $this->isDefinitive;
                }
            } else {
                $callData['fail_reason'] = $this->failReason;
                if ($this->failReason === 'other') {
                    $callData['notes'] = $this->otherFailReason;
                }
            }

            TrialAcquisitionCall::create($callData);
            $trial->save();
        });

        $this->dispatch('success', 'تماس با موفقیت ثبت شد.');
        $this->closeCallForm();
    }
    
    protected function applyStudentTypeScope($query)
    {
        return $query->whereDoesntHave('student.examSchedules');
    }

    protected function pageView(): string
    {
        return 'livewire.admin.trial-acquisition.index';
    }

    protected function pageMeta(): array
    {
        return [
            'pageBreadcrumb' => 'جذب یک هفته آزمایشی',
            'pageTitle' => 'دانش‌آموزان جذب آزمایشی من',
            'pageSubtitle' => 'مراحل تماس: روز اول، روز سوم، روز هفتم.',
            'emptyMessage' => 'دانش‌آموز هفته آزمایشی به شما تخصیص نیافته است.',
        ];
    }

    protected function isAllowedCallStage(string $stage): bool
    {
        return $stage === TrialAcquisitionCall::STAGE_EMERGENCY || array_key_exists($stage, TrialAcquisitionCall::STAGE_DUE_DAY);
    }

    public function requiresProbability(string $stage): bool
    {
        return in_array($stage, [TrialAcquisitionCall::STAGE_DAY3, TrialAcquisitionCall::STAGE_DAY7], true);
    }

    public function requiresDefinitiveConfirmation(string $stage): bool
    {
        return $stage === TrialAcquisitionCall::STAGE_DAY7;
    }

    public function stageLabel(string $stage): string
    {
        return TrialAcquisitionCall::STAGE_LABELS[$stage] ?? $stage;
    }

    public function stageMetaFor(TrialWeek $trial, $now): array
    {
        $days = (int) \Carbon\Carbon::parse($trial->created_at)->startOfDay()->diffInDays($now->copy()->startOfDay());
        return [
            TrialAcquisitionCall::STAGE_DAY1 => ['label' => 'روز اول', 'due' => $days >= 0],
            TrialAcquisitionCall::STAGE_DAY3 => ['label' => 'روز سوم', 'due' => $days >= 2],
            TrialAcquisitionCall::STAGE_DAY7 => ['label' => 'روز هفتم', 'due' => $days >= 6],
        ];
    }

    public function render()
    {
        $adminId = Auth::guard('admin')->id();
        $query = $this->applyStudentTypeScope(
            TrialWeek::query()
                ->with(['user.personalInformation', 'trialAcquisitionCalls'])
                ->where('acquisition_supporter_id', $adminId)
        );

        if ($this->search) {
            $query->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$this->search}%")->orWhere('mobile', 'like', "%{$this->search}%"));
        }
        
        $trials = $query->latest()->paginate(12);
        
        $activeTrial = $this->activeTrialId ? $this->loadTrial($this->activeTrialId) : null;

        return view($this->pageView(), [
            'trials'         => $trials,
            'activeTrial'    => $activeTrial,
            'now'            => now(),
            ...$this->pageMeta(),
        ])->layout('layouts.admin.app');
    }
}
