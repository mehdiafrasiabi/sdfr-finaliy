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

    // ───── وضعیت فرم تماس ─────
    public ?int $activeTrialId = null;
    public string $activeStage = '';        // day1 | day3 | day7 | emergency
    public $answered = null;                // null تا انتخاب نشده، سپس true/false

    public string $spokeWith = '';
    public string $followUp = '';           // father | mother (روز اول)
    public array $checklist = [];           // کلیدهای کارهای انجام‌شده
    public $probability = null;             // درصد احتمال ثبت‌نام
    public string $probabilityNote = '';
    public bool $isDefinitive = false;      // تأیید قطعی (روز هفتم)
    public string $emergencyReason = '';
    public bool $wantsReminder = false;
    public string $reminderAt = '';         // میلادی "Y-m-d H:i" از تقویم شمسی
    public string $notes = '';

    protected $queryString = ['search'];

    /** آیتم‌های چک‌لیست هر مرحله. */
    public static function checklistItems(string $stage): array
    {
        return match ($stage) {
            TrialAcquisitionCall::STAGE_DAY1 => [
                'welcome_student' => 'خوش‌آمدگویی و توضیحات به دانش‌آموز',
                'welcome_parents' => 'خوش‌آمدگویی و توضیحات به والدین',
            ],
            TrialAcquisitionCall::STAGE_DAY3 => [
                'checkup_student'  => 'حال‌واحوال و پیگیری دانش‌آموز',
                'report_followup'  => 'گزارش به پیگیر آموزشی',
            ],
            TrialAcquisitionCall::STAGE_DAY7 => [
                'well_done_student' => 'خسته‌نباشید، بازخورد و توضیح ادامهٔ مسیر',
                'report_parents'    => 'ارائهٔ گزارش به اولیا',
                'financial_talk'    => 'صحبت مالی',
            ],
            default => [],
        };
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilter(): void
    {
        $this->resetPage();
    }

    public function openCallForm(int $trialId, string $stage): void
    {
        $this->resetCallForm();
        $this->activeTrialId = $trialId;
        $this->activeStage = in_array($stage, ['day1', 'day3', 'day7', 'emergency'], true) ? $stage : 'day1';

        // مقادیر قبلی را به‌عنوان پیش‌فرض بیاور
        $trial = $this->loadTrial($trialId);
        if ($trial) {
            $this->followUp = $trial->acq_follow_up ?? '';
            $this->probability = $trial->acq_probability;
            $this->probabilityNote = $trial->acq_probability_note ?? '';
        }

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
            'activeTrialId', 'activeStage', 'answered', 'spokeWith', 'followUp',
            'checklist', 'probability', 'probabilityNote', 'isDefinitive',
            'emergencyReason', 'wantsReminder', 'reminderAt', 'notes',
        ]);
        $this->resetErrorBag();
    }

    public function setAnswered(bool $value): void
    {
        $this->answered = $value;
        $this->resetErrorBag();
    }

    public function updatedWantsReminder($value): void
    {
        if ($value) {
            // پس از نمایش تقویم، آن را در سمت کلاینت مقداردهی کن
            $this->dispatch('trial-call-form-opened');
        } else {
            $this->reminderAt = '';
        }
    }

    protected function loadTrial(?int $trialId): ?TrialWeek
    {
        if (! $trialId) {
            return null;
        }
        return TrialWeek::where('acquisition_supporter_id', Auth::guard('admin')->id())->find($trialId);
    }

    protected function hasSuccessfulCall(TrialWeek $trial): bool
    {
        return $trial->trialAcquisitionCalls
            ->contains(fn (TrialAcquisitionCall $call) => (bool) $call->answered);
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

        // ───── اعتبارسنجی ─────
        $rules = ['notes' => ['nullable', 'string', 'max:5000']];
        $messages = [];

        if ($this->wantsReminder) {
            $rules['reminderAt'] = ['required', 'date'];
            $messages['reminderAt.required'] = 'برای یادآور، تاریخ و ساعت را مشخص کنید.';
        }

        if ($this->answered && $isEmergency) {
            $rules['emergencyReason'] = ['required', 'string', 'max:2000'];
            $messages['emergencyReason.required'] = 'علت تماس اضطراری را بنویسید.';
        }

        if ($this->answered && ! $isEmergency) {
            $rules['spokeWith'] = ['required', 'in:father,mother,student,other'];
            $messages['spokeWith.required'] = 'تعیین کنید با چه شخصی صحبت شده است.';

            if ($this->activeStage === TrialAcquisitionCall::STAGE_DAY1) {
                $rules['followUp'] = ['required', 'in:father,mother'];
                $messages['followUp.required'] = 'پیگیر آموزشی (پدر/مادر) را انتخاب کنید.';
            }

            if (in_array($this->activeStage, [TrialAcquisitionCall::STAGE_DAY3, TrialAcquisitionCall::STAGE_DAY7], true)) {
                $rules['probability'] = ['required', 'integer', 'between:0,100'];
                $rules['probabilityNote'] = ['required', 'string', 'max:2000'];
                $messages['probability.required'] = 'درصد احتمال ثبت‌نام را وارد کنید.';
                $messages['probabilityNote.required'] = 'توضیحات احتمال ثبت‌نام را بنویسید.';
            }
        }

        $this->validate($rules, $messages);

        if ($this->answered && $this->activeStage === TrialAcquisitionCall::STAGE_DAY7 && ! $this->isDefinitive) {
            $this->addError('isDefinitive', 'برای ثبت نهایی، احتمال ثبت‌نام را به‌صورت قطعی تأیید کنید.');
            return;
        }

        DB::transaction(function () use ($trial, $isEmergency) {
            $attempt = TrialAcquisitionCall::where('trial_week_id', $trial->id)
                ->where('stage', $this->activeStage)
                ->count() + 1;

            TrialAcquisitionCall::create([
                'trial_week_id'            => $trial->id,
                'admin_id'                 => Auth::guard('admin')->id(),
                'stage'                    => $this->activeStage,
                'attempt_number'           => $attempt,
                'answered'                 => $this->answered,
                'spoke_with'               => $this->answered && ! $isEmergency ? ($this->spokeWith ?: null) : null,
                'educational_follow_up'    => $this->answered && $this->activeStage === TrialAcquisitionCall::STAGE_DAY1 ? ($this->followUp ?: null) : null,
                'checklist'                => $this->answered && ! $isEmergency ? array_keys(array_filter($this->checklist)) : null,
                'registration_probability' => $this->answered && ! $isEmergency && $this->probability !== null && $this->probability !== '' ? (int) $this->probability : null,
                'probability_note'         => $this->answered && ! $isEmergency ? ($this->probabilityNote ?: null) : null,
                'is_definitive'            => $this->answered && $this->activeStage === TrialAcquisitionCall::STAGE_DAY7 ? $this->isDefinitive : false,
                'emergency_reason'         => $this->answered && $isEmergency ? ($this->emergencyReason ?: null) : null,
                'reminder_at'              => $this->wantsReminder ? $this->reminderAt : null,
                'notes'                    => $this->notes ?: null,
                'called_at'                => now(),
            ]);

            // ───── به‌روزرسانی فیلدهای denormalized روی trial_week ─────
            if ($this->answered && $this->activeStage === TrialAcquisitionCall::STAGE_DAY1 && $this->followUp) {
                $trial->acq_follow_up = $this->followUp;
            }
            if ($this->answered && ! $isEmergency && $this->probability !== null && $this->probability !== '') {
                $trial->acq_probability = (int) $this->probability;
                $trial->acq_probability_note = $this->probabilityNote ?: null;
            }
            if ($this->answered && $this->activeStage === TrialAcquisitionCall::STAGE_DAY7 && $this->isDefinitive) {
                $trial->acq_confirmed = true;
            }
            if ($this->wantsReminder) {
                $trial->acq_reminder_at = $this->reminderAt;
            } elseif ($this->answered) {
                // تماس برقرار شد و یادآوری جدیدی نیست → یادآور قبلی پاک می‌شود
                $trial->acq_reminder_at = null;
            }
            $trial->save();
        });

        $this->dispatch('success', 'تماس با موفقیت ثبت شد.');
        $this->closeCallForm();
    }

    public function render()
    {
        $adminId = Auth::guard('admin')->id();

        $trials = TrialWeek::query()
            ->with([
                'user.personalInformation',
                'trialAcquisitionCalls',
                'student.examSchedules' => fn ($query) => $query
                    ->whereNotNull('weekly_program_id')
                    ->whereNotNull('program_built_at')
                    ->latest('program_built_at'),
            ])
            ->where('acquisition_supporter_id', $adminId)
            ->when($this->search, fn ($q) => $q->whereHas('user', fn ($u) =>
                $u->where('name', 'like', "%{$this->search}%")
                    ->orWhere('mobile', 'like', "%{$this->search}%")
            ))
            ->when($this->filter === 'reminders', fn ($q) => $q->whereNotNull('acq_reminder_at'))
            ->when($this->filter === 'confirmed', fn ($q) => $q->where('acq_confirmed', true))
            ->when($this->filter === 'not_called', fn ($q) => $q->whereDoesntHave('trialAcquisitionCalls', fn ($c) => $c->where('answered', true)))
            ->latest()
            ->paginate(12);

        $activeTrial = $this->activeTrialId
            ? TrialWeek::with([
                'user.personalInformation',
                'trialAcquisitionCalls',
                'student.examSchedules' => fn ($query) => $query
                    ->whereNotNull('weekly_program_id')
                    ->whereNotNull('program_built_at')
                    ->latest('program_built_at'),
            ])->find($this->activeTrialId)
            : null;

        return view('livewire.admin.trial-acquisition.index', [
            'trials'         => $trials,
            'activeTrial'    => $activeTrial,
            'checklistItems' => $this->activeStage ? self::checklistItems($this->activeStage) : [],
            'now'            => now(),
            'hasActiveTrialCall' => $activeTrial ? $this->hasSuccessfulCall($activeTrial) : false,
        ])->layout('layouts.admin.app');
    }
}
