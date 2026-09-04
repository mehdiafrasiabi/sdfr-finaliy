<?php

namespace App\Livewire\Admin\TrialAcquisition;

use App\Models\TrialAcquisitionCall;
use App\Models\TrialWeek;
use Carbon\Carbon;
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
    public string $callSummary = '';
    public string $reminderAt = '';
    public bool $hasDisinterest = false;
    public string $disinterestStatus = '';
    public string $disinterestReason = '';
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

        if (! $this->loadTrial($trialId)) {
            $this->dispatch('warning', 'دانش‌آموز یافت نشد یا به شما تخصیص ندارد.');
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
            'welcomeCallSummary', 'callSummary', 'reminderAt', 'hasDisinterest',
            'disinterestStatus', 'disinterestReason', 'probability', 'probabilityNote', 'isDefinitive',
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
        $this->dispatch('trial-call-form-opened');
    }

    public function markNoAnswer(): void
    {
        $this->answered = false;
        $this->callPhase = 'noAnswerForm';
        $this->resetErrorBag();
        $this->dispatch('trial-call-form-opened');
    }

    protected function loadTrial(?int $trialId): ?TrialWeek
    {
        if (!$trialId) return null;
        return $this->applyStudentTypeScope(
            TrialWeek::where('acquisition_supporter_id', Auth::guard('admin')->id())
                ->visibleForAcquisition()
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

            $rules['reminderAt'] = ['nullable', 'date', $this->notPastReminderRule()];
            $messages['reminderAt.date'] = 'تاریخ یادآور معتبر نیست.';

            if ($this->hasDisinterest) {
                $rules['disinterestStatus'] = ['required', 'in:' . TrialWeek::ACQ_DISINTEREST_TEMPORARY . ',' . TrialWeek::ACQ_DISINTEREST_DEFINITIVE];
                $rules['disinterestReason'] = ['required', 'string', 'min:3', 'max:5000'];
                $messages['disinterestStatus.required'] = 'نوع عدم تمایل را انتخاب کنید.';
                $messages['disinterestReason.required'] = 'علت عدم تمایل را بنویسید.';

                if ($this->disinterestStatus === TrialWeek::ACQ_DISINTEREST_TEMPORARY) {
                    $rules['reminderAt'] = ['required', 'date', $this->notPastReminderRule()];
                    $messages['reminderAt.required'] = 'برای عدم تمایل موقت، تاریخ و ساعت یادآور الزامی است.';
                }
            } else {
                $rules['callSummary'] = ['required', 'string', 'min:3', 'max:5000'];
                $messages['callSummary.required'] = 'خلاصه گفتگو را بنویسید.';
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

        DB::transaction(function () use ($trial) {
            $reminderAt = $this->reminderAt ? Carbon::parse($this->reminderAt) : null;

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
                    'reminder_at' => $reminderAt,
                ];

                if ($this->hasDisinterest) {
                    $callData['notes'] = $this->disinterestReason;
                    $callData['disinterest_status'] = $this->disinterestStatus;
                    $callData['disinterest_reason'] = $this->disinterestReason;
                    $trial->acq_disinterest_status = $this->disinterestStatus;
                    $trial->acq_disinterest_reason = $this->disinterestReason;
                    $trial->acq_disinterest_at = now();
                } else {
                    $callData['notes'] = $this->callSummary;
                    $trial->acq_disinterest_status = null;
                    $trial->acq_disinterest_reason = null;
                    $trial->acq_disinterest_at = null;
                }

                $trial->acq_reminder_at = $reminderAt;
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

    protected function notPastReminderRule(): \Closure
    {
        return function (string $attribute, mixed $value, \Closure $fail): void {
            if (! $value) {
                return;
            }

            try {
                $reminderAt = Carbon::parse($value);
            } catch (\Throwable) {
                return;
            }

            if ($reminderAt->lt(now()->startOfMinute())) {
                $fail('تاریخ و ساعت یادآور نمی‌تواند قبل از زمان فعلی باشد.');
            }
        };
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

    public function monitorRouteName(): string
    {
        return 'admin.trial-acquisition.monitor';
    }

    public function updatedHasDisinterest(bool $value): void
    {
        if (! $value) {
            $this->reset(['disinterestStatus', 'disinterestReason']);
        }
    }

    public function trialStudentFullName(TrialWeek $trial): string
    {
        $info = $trial->user?->personalInformation;
        $personalName = trim(implode(' ', array_filter([
            $info?->name,
            $info?->name_full,
        ], fn ($value) => filled($value))));

        if ($personalName !== '') {
            return $personalName;
        }

        $profileName = trim((string) ($trial->user?->profile?->full_name ?? ''));

        return $profileName !== '' ? $profileName : ($trial->user?->name ?: '—');
    }

    public function examProgramStage(?TrialWeek $trial): ?array
    {
        $schedule = $trial?->student?->examSchedules?->sortByDesc(fn ($item) => $item->program_built_at?->timestamp ?? 0)->first();

        if (! $schedule) {
            return null;
        }

        if (! $schedule->exam_starts_at || ! $schedule->exam_ends_at) {
            return [
                'label' => 'در انتظار انتخاب بازه امتحانات',
                'color' => 'warning',
            ];
        }

        if ($schedule->days->isEmpty()) {
            return [
                'label' => 'در انتظار اضافه کردن دروس امتحانات',
                'color' => 'info',
            ];
        }

        if (! $schedule->program_built_at || ! $schedule->weekly_program_id) {
            return [
                'label' => 'در انتظار ساخت برنامه',
                'color' => 'primary',
            ];
        }

        return [
            'label' => 'برنامه ساخته شده',
            'color' => 'success',
        ];
    }

    protected function isAllowedCallStage(string $stage): bool
    {
        return $stage === TrialAcquisitionCall::STAGE_EMERGENCY
            || $stage === TrialAcquisitionCall::STAGE_REGISTRATION_FOLLOW_UP
            || array_key_exists($stage, TrialAcquisitionCall::STAGE_DUE_DAY);
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
                ->visibleForAcquisition()
                ->with([
                    'user.personalInformation',
                    'user.profile',
                    'trialAcquisitionCalls',
                    'student.examSchedules' => fn ($scheduleQuery) => $scheduleQuery
                        ->with(['days', 'weeklyProgram'])
                        ->latest('program_built_at')
                        ->latest('updated_at'),
                ])
                ->where('acquisition_supporter_id', $adminId)
        );

        if ($this->search) {
            $query->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$this->search}%")->orWhere('mobile', 'like', "%{$this->search}%"));
        }

        $query->when($this->filter === 'not_called', fn ($q) => $q->whereDoesntHave('trialAcquisitionCalls'))
            ->when($this->filter === 'reminders', fn ($q) => $q->whereNotNull('acq_reminder_at'))
            ->when($this->filter === 'confirmed', fn ($q) => $q->where('acq_confirmed', true));
        
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
