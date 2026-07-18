<?php

namespace App\Livewire\Admin\TrialAcquisition;

use App\Models\TrialAcquisitionCall;
use App\Models\TrialWeek;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * جریان یکپارچهٔ «مشاور جذب یک هفته آزمایشی».
 * نمایش دانش‌آموزان تخصیص‌یافته + ثبت تماس مرحله‌ای (روز۱/روز۳/روز۷) + اضطراری + یادآور.
 */
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filter = ''; // '' | reminders | not_called | confirmed
    public ?int $callTrialId = null;
    public string $callStage = '';
    public string $subject = '';
    public ?int $pendingCallTrialId = null;
    public string $pendingCallStage = '';
    public string $pendingCallSubject = '';
    public bool $showCallConfirmModal = false;

    // ───── وضعیت فرم تماس ─────
    public ?int $activeTrialId = null;
    public string $activeStage = '';        // day1 | day3 | day7 | emergency
    public ?int $activeCallId = null;
    public ?int $talkSeconds = null;
    public string $callPhase = 'ringing';   // ringing | talking | answerForm | noAnswerForm
    public $answered = null;                // null تا انتخاب نشده، سپس true/false

    public array $spokeWith = [];
    public string $spokeWithOther = '';
    public string $callSubject = '';
    public $probability = null;             // درصد احتمال ثبت‌نام
    public string $probabilityNote = '';
    public bool $isDefinitive = false;      // تأیید قطعی (روز هفتم)
    public string $emergencyReason = '';
    public string $failReason = '';
    public string $notes = '';

    protected $queryString = [
        'search',
        'callTrialId' => ['except' => null],
        'callStage' => ['except' => ''],
        'subject' => ['except' => ''],
    ];

    public function mount(): void
    {
        if ($this->callTrialId && $this->callStage) {
            $this->openCallForm((int) $this->callTrialId, $this->callStage, $this->subject);
            $this->callTrialId = null;
            $this->callStage = '';
            $this->subject = '';
        }
    }

    /** گزینه‌های موضوع تماس روز اول. */
    public static function callSubjectOptions(): array
    {
        return [
            'parent_welcome' => 'توضیحات و خوش‌آمدگویی والدین',
            'student_welcome' => 'توضیحات و خوش‌آمدگویی دانش‌آموز',
        ];
    }

    public static function callSubjectInstructions(): array
    {
        return [
            'parent_welcome' => 'در این تماس، روند هفته آزمایشی، نقش والدین، زمان‌بندی پیگیری‌ها و مسیر گزارش‌دهی را برای والدین توضیح بده.',
            'student_welcome' => 'در این تماس، به دانش‌آموز خوش‌آمد بگو، مسیر هفته آزمایشی، انتظارات روزانه و نحوه ارتباط با تیم را توضیح بده.',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilter(): void
    {
        $this->resetPage();
    }

    public function promptCall(int $trialId, string $stage, string $subject = ''): void
    {
        if (! $this->isAllowedCallStage($stage)) {
            $this->dispatch('warning', 'مرحله تماس معتبر نیست.');
            return;
        }

        if ($this->requiresCallSubject($stage) && ! array_key_exists($subject, self::callSubjectOptions())) {
            $this->dispatch('warning', 'موضوع تماس روز اول معتبر نیست.');
            return;
        }

        $belongsToSupporter = $this->applyStudentTypeScope(
            TrialWeek::where('acquisition_supporter_id', Auth::guard('admin')->id())
                ->whereHas('student', fn ($query) => $query->where('is_trial', true))
        )
            ->whereKey($trialId)
            ->exists();

        if (! $belongsToSupporter) {
            $this->dispatch('warning', 'دانش‌آموز یافت نشد یا به شما تخصیص ندارد.');
            return;
        }

        $this->pendingCallTrialId = $trialId;
        $this->pendingCallStage = $stage;
        $this->pendingCallSubject = $subject;
        $this->showCallConfirmModal = true;
    }

    public function cancelCallPrompt(): void
    {
        $this->reset(['pendingCallTrialId', 'pendingCallStage', 'pendingCallSubject', 'showCallConfirmModal']);
    }

    public function continueCallPrompt(): void
    {
        if (! $this->pendingCallTrialId || ! $this->pendingCallStage) {
            $this->cancelCallPrompt();
            return;
        }

        $this->openCallForm((int) $this->pendingCallTrialId, $this->pendingCallStage, $this->pendingCallSubject);
        $this->cancelCallPrompt();
    }

    public function openCallForm(int $trialId, string $stage, string $subject = ''): void
    {
        $this->resetCallForm();
        $this->activeTrialId = $trialId;
        $this->activeStage = $this->isAllowedCallStage($stage) ? $stage : TrialAcquisitionCall::STAGE_DAY1;
        $this->callSubject = $this->requiresCallSubject($this->activeStage) && array_key_exists($subject, self::callSubjectOptions())
            ? $subject
            : '';

        // مقادیر قبلی را به‌عنوان پیش‌فرض بیاور
        $trial = $this->loadTrial($trialId);
        if ($trial) {
            $this->probability = $trial->acq_probability;
            $this->probabilityNote = $trial->acq_probability_note ?? '';
        }

        $this->callPhase = 'ringing';
        $this->dispatch('trial-call-form-opened');
    }

    public function openEmergency(int $trialId): void
    {
        $this->openCallForm($trialId, TrialAcquisitionCall::STAGE_EMERGENCY);
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
            'callSubject', 'probability', 'probabilityNote', 'isDefinitive',
            'emergencyReason', 'failReason', 'notes',
        ]);
        $this->resetErrorBag();
    }

    public function markCallAnswered(): void
    {
        $trial = $this->loadTrial($this->activeTrialId);
        if (! $trial) {
            $this->dispatch('warning', 'دانش‌آموز یافت نشد یا به شما تخصیص ندارد.');
            $this->closeCallForm();
            return;
        }

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
        $this->failReason = $this->failReason ?: TrialAcquisitionCall::FAIL_NO_ANSWER;
        $this->resetErrorBag();
    }

    public function cancelRing(): void
    {
        if ($this->callPhase === 'ringing') {
            $this->closeCallForm();
        }
    }

    protected function loadTrial(?int $trialId): ?TrialWeek
    {
        if (! $trialId) {
            return null;
        }

        return $this->applyStudentTypeScope(
            TrialWeek::where('acquisition_supporter_id', Auth::guard('admin')->id())
                ->whereHas('student', fn ($query) => $query->where('is_trial', true))
        )->find($trialId);
    }

    protected function hasSuccessfulCall(TrialWeek $trial): bool
    {
        return $trial->trialAcquisitionCalls
            ->contains(fn (TrialAcquisitionCall $call) => (bool) $call->answered && $call->stage !== TrialAcquisitionCall::STAGE_EXTRA);
    }

    public function logCall(): void
    {
        $trial = $this->loadTrial($this->activeTrialId);
        if (! $trial) {
            $this->dispatch('warning', 'دانش‌آموز یافت نشد یا به شما تخصیص ندارد.');
            $this->closeCallForm();
            return;
        }

        if ($this->answered === null) {
            $this->dispatch('warning', 'ابتدا وضعیت تماس (پاسخ داد / پاسخ نداد) را انتخاب کنید.');
            return;
        }

        $isEmergency = $this->activeStage === TrialAcquisitionCall::STAGE_EMERGENCY;

        $rules = ['notes' => ['nullable', 'string', 'max:5000']];
        $messages = [];

        if ($this->answered) {
            if ($isEmergency) {
                $rules['emergencyReason'] = ['required', 'string', 'max:2000'];
                $messages['emergencyReason.required'] = 'علت تماس اضطراری را بنویسید.';
            } else {
                $rules['spokeWith'] = ['required', 'array', 'min:1'];
                $rules['spokeWith.*'] = ['required', 'in:father,mother,student,other'];
                $messages['spokeWith.required'] = 'حداقل یک گزینه برای صحبت‌شده‌ها انتخاب کنید.';

                if (in_array('other', $this->spokeWith, true)) {
                    $rules['spokeWithOther'] = ['required', 'string', 'max:255'];
                    $messages['spokeWithOther.required'] = 'نام شخص دیگر را بنویسید.';
                }

                if ($this->requiresCallSubject($this->activeStage)) {
                    $rules['callSubject'] = ['required', 'in:parent_welcome,student_welcome'];
                    $messages['callSubject.required'] = 'موضوع تماس را انتخاب کنید.';
                }

                if ($this->requiresProbability($this->activeStage)) {
                    $rules['probability'] = ['required', 'integer', 'between:0,100'];
                    $rules['probabilityNote'] = ['required', 'string', 'max:2000'];
                    $messages['probability.required'] = 'درصد احتمال ثبت‌نام را وارد کنید.';
                    $messages['probabilityNote.required'] = 'توضیحات احتمال ثبت‌نام را بنویسید.';
                }
            }
        } else {
            $rules['failReason'] = ['required', 'in:no_answer,off,rejected'];
            $messages['failReason.required'] = 'علت عدم پاسخ را انتخاب کنید.';
        }

        $this->validate($rules, $messages);

        if ($this->answered && $this->requiresDefinitiveConfirmation($this->activeStage) && ! $this->isDefinitive) {
            $this->addError('isDefinitive', 'برای ثبت نهایی، احتمال ثبت‌نام را به‌صورت قطعی تأیید کنید.');
            return;
        }

        DB::transaction(function () use ($trial, $isEmergency) {
            $attempt = TrialAcquisitionCall::where('trial_week_id', $trial->id)
                ->where('stage', $this->activeStage)
                ->count() + 1;

            TrialAcquisitionCall::create([
                'trial_week_id'         => $trial->id,
                'admin_id'              => Auth::guard('admin')->id(),
                'stage'                 => $this->activeStage,
                'attempt_number'        => $attempt,
                'answered'              => $this->answered,
                'talk_duration_seconds' => $this->answered ? (int) ($this->talkSeconds ?? 0) : null,
                'fail_reason'           => $this->answered ? null : $this->failReason,
                'spoke_with_people'     => $this->answered && ! $isEmergency ? array_values(array_filter($this->spokeWith)) : null,
                'spoke_with_other'      => $this->answered && ! $isEmergency && in_array('other', $this->spokeWith, true) ? ($this->spokeWithOther ?: null) : null,
                'spoke_with'            => $this->answered && ! $isEmergency ? (array_values(array_filter($this->spokeWith))[0] ?? null) : null,
                'call_subject'          => $this->answered && $this->requiresCallSubject($this->activeStage) ? ($this->callSubject ?: null) : null,
                'registration_probability' => $this->answered && ! $isEmergency && $this->probability !== null && $this->probability !== '' ? (int) $this->probability : null,
                'probability_note'      => $this->answered && ! $isEmergency ? ($this->probabilityNote ?: null) : null,
                'is_definitive'         => $this->answered && $this->requiresDefinitiveConfirmation($this->activeStage) ? $this->isDefinitive : false,
                'emergency_reason'      => $this->answered && $isEmergency ? ($this->emergencyReason ?: null) : null,
                'notes'                 => $this->answered ? ($this->notes ?: null) : null,
                'called_at'             => now(),
            ]);

            // ───── به‌روزرسانی فیلدهای denormalized روی trial_week ─────
            if ($this->answered && ! $isEmergency && $this->probability !== null && $this->probability !== '') {
                $trial->acq_probability = (int) $this->probability;
                $trial->acq_probability_note = $this->probabilityNote ?: null;
            }
            if ($this->answered && $this->requiresDefinitiveConfirmation($this->activeStage) && $this->isDefinitive) {
                $trial->acq_confirmed = true;
            }
            $trial->save();
        });

        $this->dispatch('success', 'تماس با موفقیت ثبت شد.');
        $this->closeCallForm();
    }

    protected function builtExamProgramConstraint($query): void
    {
        $query->whereNotNull('weekly_program_id')
            ->whereNotNull('program_built_at');
    }

    protected function applyStudentTypeScope($query)
    {
        return $query->whereDoesntHave(
            'student.examSchedules',
            fn ($scheduleQuery) => $this->builtExamProgramConstraint($scheduleQuery)
        );
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
            'pageSubtitle' => 'فقط دانش‌آموزان هفته آزمایشی ۸ روزه. مراحل تماس بر اساس روزهای هفتهٔ آزمایشی: روز اول، روز سوم، روز هفتم.',
            'emptyMessage' => 'دانش‌آموز هفته آزمایشی ۸ روزه‌ای به شما تخصیص نیافته است.',
        ];
    }

    protected function isAllowedCallStage(string $stage): bool
    {
        return $stage === TrialAcquisitionCall::STAGE_EMERGENCY
            || array_key_exists($stage, TrialAcquisitionCall::STAGE_DUE_DAY);
    }

    public function requiresCallSubject(string $stage): bool
    {
        return $stage === TrialAcquisitionCall::STAGE_DAY1;
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

        $trials = $this->applyStudentTypeScope(
            TrialWeek::query()
                ->with([
                    'user.personalInformation',
                    'trialAcquisitionCalls',
                    'student.examSchedules' => fn ($query) => $query
                        ->with(['setting', 'days', 'weeklyProgram'])
                        ->whereNotNull('weekly_program_id')
                        ->whereNotNull('program_built_at')
                        ->latest('program_built_at'),
                ])
                ->where('acquisition_supporter_id', $adminId)
                ->whereHas('student', fn ($query) => $query->where('is_trial', true))
        )
            ->when($this->search, fn ($q) => $q->whereHas('user', fn ($u) =>
                $u->where('name', 'like', "%{$this->search}%")
                    ->orWhere('mobile', 'like', "%{$this->search}%")
            ))
            ->when($this->filter === 'reminders', fn ($q) => $q->whereNotNull('acq_reminder_at'))
            ->when($this->filter === 'confirmed', fn ($q) => $q->where('acq_confirmed', true))
            ->when($this->filter === 'not_called', fn ($q) => $q->whereDoesntHave('trialAcquisitionCalls', fn ($c) => $c
                ->where('answered', true)
                ->where('stage', '!=', TrialAcquisitionCall::STAGE_EXTRA)
            ))
            ->latest()
            ->paginate(12);

        $activeTrial = $this->activeTrialId
            ? $this->applyStudentTypeScope(
                TrialWeek::with([
                    'user.personalInformation',
                    'trialAcquisitionCalls',
                    'student.examSchedules' => fn ($query) => $query
                        ->with(['setting', 'days', 'weeklyProgram'])
                        ->whereNotNull('weekly_program_id')
                        ->whereNotNull('program_built_at')
                        ->latest('program_built_at'),
                ])->where('acquisition_supporter_id', $adminId)
                    ->whereHas('student', fn ($query) => $query->where('is_trial', true))
            )->find($this->activeTrialId)
            : null;

        return view($this->pageView(), [
            'trials'         => $trials,
            'activeTrial'    => $activeTrial,
            'now'            => now(),
            'callSubjectInstructions' => self::callSubjectInstructions(),
            'hasActiveTrialCall' => $activeTrial ? $this->hasSuccessfulCall($activeTrial) : false,
            ...$this->pageMeta(),
        ])->layout('layouts.admin.app');
    }
}
