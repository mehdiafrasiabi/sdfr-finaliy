<?php

namespace App\Livewire\Client\Profile\ExamPlanning;

use App\Models\CcChapter;
use App\Models\CcSubject;
use App\Models\ExamPlanningSetting;
use App\Models\StudentExamSchedule;
use App\Models\WeeklyProgram;
use App\Services\ExamPlanningService;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

class Builder extends Component
{
    use SEOTools;

    public ?StudentExamSchedule $schedule = null;
    public ?ExamPlanningSetting $setting = null;
    public ?string $accessMode = null;

    public string $calendarStart = '';
    public string $calendarEnd = '';
    public string $calendarMinDate = '';
    public string $calendarMaxDate = '';
    public int $calendarYear = 0;
    public array $daySubjectSelections = [];
    public array $allocations = [];
    public array $prioritySubjects = [];
    public array $generalStudyModes = [];
    public string $activeType = 'specialized';
    public int $activeSubject = 0;
    public ?string $selectedExamDayDate = null;
    public ?string $selectedExamDaySelectionKey = null;
    public int $selectedExamDaySubjectId = 0;
    public bool $showExamDayModal = false;
    public bool $showGeneralWholeModal = false;
    public ?int $selectedGeneralWholeSubjectId = null;
    public int $selectedGeneralWholeMinutes = 60;
    public bool $editingCalendar = false;
    public ?int $builtProgramId = null;
    public int $currentCalendarWeek = 0;
    public bool $showFinalizeCalendarModal = false;
    public bool $showResetCalendarModal = false;

    public function mount(ExamPlanningService $service): void
    {
        $access = $service->resolveExamAccess(auth()->user());
        $this->accessMode = $access['mode'];
        $this->setting = $access['setting'];

        if (! $this->accessMode || ! $this->setting) {
            $target = auth()->user()?->student?->is_trial
                ? route('client.profile.trial.guide')
                : route('client.profile.dashboard');

            redirect()->to($target);
            return;
        }

        $this->schedule = $service->prepareScheduleForUser(auth()->user());
        $this->calendarYear = (int) Jalalian::now()->format('Y');
        $this->calendarMinDate = Jalalian::now()->format('Y/m/d');
        $this->calendarMaxDate = $this->endOfCurrentJalaliYear();
        $this->calendarStart = $this->toJalali($this->schedule?->exam_starts_at?->toDateString());
        $this->calendarEnd = $this->toJalali($this->schedule?->exam_ends_at?->toDateString());
        $this->editingCalendar = false;
        $this->syncBuiltProgramState();
        $this->loadAllocationState();
        $this->seo()->setTitle('ساخت برنامه امتحانی');
    }

    public function saveCalendarRange(ExamPlanningService $service): void
    {
        if ($this->programAlreadyBuilt()) {
            $this->dispatch('warning', 'برنامه امتحانی قبلاً ساخته شده و امکان ساخت یا ویرایش مجدد ندارد.');
            return;
        }

        if ($this->rangeLocked()) {
            $this->dispatch('warning', 'بازه امتحانات قبلاً ثبت شده و دیگر قابل تغییر نیست.');
            return;
        }

        $this->resetValidation(['calendarStart', 'calendarEnd']);
        $start = $this->fromJalali($this->calendarStart);
        $end = $this->fromJalali($this->calendarEnd);

        if (! $start) {
            $this->addError('calendarStart', 'تاریخ شروع امتحانات را به صورت شمسی وارد کنید؛ مثل 1405/03/10.');
        }

        if (! $end) {
            $this->addError('calendarEnd', 'تاریخ پایان امتحانات را به صورت شمسی وارد کنید؛ مثل 1405/03/28.');
        }

        if ($this->getErrorBag()->has('calendarStart') || $this->getErrorBag()->has('calendarEnd')) {
            return;
        }

        if (! $this->isInCurrentJalaliYear($this->calendarStart)) {
            $this->addError('calendarStart', "فقط تاریخ‌های سال {$this->calendarYear} مجاز هستند.");
            return;
        }

        if (! $this->isInCurrentJalaliYear($this->calendarEnd)) {
            $this->addError('calendarEnd', "فقط تاریخ‌های سال {$this->calendarYear} مجاز هستند.");
            return;
        }

        if (! $this->isOnOrAfterToday($this->calendarStart)) {
            $this->addError('calendarStart', 'تاریخ شروع نمی‌تواند قبل از امروز باشد.');
            return;
        }

        if (! $this->isOnOrAfterToday($this->calendarEnd)) {
            $this->addError('calendarEnd', 'تاریخ پایان نمی‌تواند قبل از امروز باشد.');
            return;
        }

        if ($end < $start) {
            $this->addError('calendarEnd', 'تاریخ پایان باید بعد از تاریخ شروع باشد.');
            return;
        }

        $dayCount = \Carbon\Carbon::parse($start)->diffInDays(\Carbon\Carbon::parse($end)) + 1;
        if ($dayCount > 40) {
            $this->addError('calendarEnd', 'بازه امتحانات نمی‌تواند بیشتر از 40 روز باشد.');
            return;
        }

        if (! $this->schedule) {
            return;
        }

        $this->schedule = $service->saveStudentCalendar($this->schedule, $start, $end);
        $this->resetValidation(['calendarStart', 'calendarEnd']);
        $this->dispatch('success', 'بازه امتحانات ذخیره شد. حالا روزهای امتحان را کامل کنید.');
        $this->dispatchScrollTop();
    }

    public function addExamDay(string $date, string $selectionKey, ExamPlanningService $service): void
    {
        if ($this->programAlreadyBuilt()) {
            $this->dispatch('warning', 'برنامه امتحانی قبلاً ساخته شده و امکان ساخت یا ویرایش مجدد ندارد.');
            return;
        }

        $subjectId = (int) ($this->daySubjectSelections[$selectionKey] ?? 0);
        if ($subjectId <= 0 || ! $this->schedule) {
            $this->addError("daySubjectSelections.{$selectionKey}", 'ابتدا درس این روز را انتخاب کنید.');
            return;
        }

        try {
            $service->addStudentExamDay($this->schedule, $date, $subjectId);
            $this->daySubjectSelections[$selectionKey] = '';
            $this->resetValidation("daySubjectSelections.{$selectionKey}");
            $this->refreshSchedule($service);
            $this->dispatch('success', 'امتحان این روز اضافه شد.');
            $this->dispatchScrollTop();
        } catch (\LogicException $exception) {
            $this->addError("daySubjectSelections.{$selectionKey}", $exception->getMessage());
        }
    }

    public function openExamDayModal(string $date, string $selectionKey): void
    {
        if ($this->programAlreadyBuilt() || ! $this->schedule) {
            return;
        }

        $this->selectedExamDayDate = $date;
        $this->selectedExamDaySelectionKey = $selectionKey;
        $this->selectedExamDaySubjectId = 0;
        $this->showExamDayModal = true;
    }

    public function closeExamDayModal(): void
    {
        $this->showExamDayModal = false;
        $this->selectedExamDayDate = null;
        $this->selectedExamDaySelectionKey = null;
        $this->selectedExamDaySubjectId = 0;
        $this->resetValidation('selectedExamDaySubjectId');
    }

    public function saveExamDayFromModal(ExamPlanningService $service): void
    {
        if ($this->programAlreadyBuilt() || ! $this->schedule || ! $this->selectedExamDayDate || ! $this->selectedExamDaySelectionKey) {
            return;
        }

        $this->validate([
            'selectedExamDaySubjectId' => ['required', 'integer', 'min:1'],
        ], [
            'selectedExamDaySubjectId.required' => 'ابتدا یک درس را انتخاب کنید.',
        ]);

        try {
            $service->addStudentExamDay($this->schedule, $this->selectedExamDayDate, $this->selectedExamDaySubjectId);
            $this->closeExamDayModal();
            $this->refreshSchedule($service);
            $this->dispatch('success', 'امتحان این روز اضافه شد.');
            $this->dispatchScrollTop();
        } catch (\LogicException $exception) {
            $this->addError('selectedExamDaySubjectId', $exception->getMessage());
        }
    }

    public function removeExamDay(int $dayId, ExamPlanningService $service): void
    {
        if ($this->programAlreadyBuilt()) {
            $this->dispatch('warning', 'برنامه امتحانی قبلاً ساخته شده و امکان ساخت یا ویرایش مجدد ندارد.');
            return;
        }

        if (! $this->schedule) {
            return;
        }

        $service->removeStudentExamDay($this->schedule, $dayId);
        $this->closeExamDayModal();
        $this->refreshSchedule($service);
        $this->dispatch('success', 'درس این روز حذف شد.');
        $this->dispatchScrollTop();
    }

    public function openFinalizeCalendarModal(): void
    {
        if (! $this->schedule || $this->usingManagerCalendar()) {
            return;
        }

        $activeExamCount = $this->schedule->days()->count();

        if ($activeExamCount === 0) {
            $this->dispatch('warning', 'اول باید امتحان‌هایت را کامل ثبت کنی.');
            return;
        }

        if ($activeExamCount < 3) {
            $this->dispatch('warning', 'برای ثبت نهایی، حداقل باید 3 امتحان ثبت شده باشد.');
            return;
        }

        $this->showFinalizeCalendarModal = true;
    }

    public function closeFinalizeCalendarModal(): void
    {
        $this->showFinalizeCalendarModal = false;
    }

    public function finalizeCalendar(ExamPlanningService $service): void
    {
        if (! $this->schedule) {
            return;
        }

        try {
            $this->schedule = $service->finalizeStudentCalendar($this->schedule);
            $this->showFinalizeCalendarModal = false;
            $this->closeExamDayModal();
            $this->editingCalendar = false;
            $this->refreshSchedule($service);
            $this->dispatch('success', 'تقویم امتحاناتت ثبت نهایی شد. حالا ساعت مطالعه را کامل کن.');
            $this->dispatchScrollTop();
        } catch (\LogicException $exception) {
            $this->showFinalizeCalendarModal = false;
            $this->closeExamDayModal();
            $this->dispatch('warning', $exception->getMessage());
        }
    }

    public function openResetCalendarModal(): void
    {
        if ($this->programAlreadyBuilt()) {
            $this->dispatch('warning', 'بعد از ساخت برنامه دیگر امکان ریست کردن این صفحه وجود ندارد.');
            return;
        }

        if (! $this->schedule || $this->usingManagerCalendar()) {
            return;
        }

        $this->showResetCalendarModal = true;
    }

    public function closeResetCalendarModal(): void
    {
        $this->showResetCalendarModal = false;
    }

    public function resetCalendarBuilder(ExamPlanningService $service): void
    {
        if ($this->programAlreadyBuilt()) {
            $this->dispatch('warning', 'بعد از ساخت برنامه دیگر امکان ریست کردن این صفحه وجود ندارد.');
            return;
        }

        if (! $this->schedule || $this->usingManagerCalendar()) {
            return;
        }

        $this->schedule = $service->resetStudentCalendar($this->schedule);
        $this->showResetCalendarModal = false;
        $this->calendarStart = '';
        $this->calendarEnd = '';
        $this->daySubjectSelections = [];
        $this->generalStudyModes = [];
        $this->showGeneralWholeModal = false;
        $this->selectedGeneralWholeSubjectId = null;
        $this->selectedGeneralWholeMinutes = 60;
        $this->closeExamDayModal();
        $this->currentCalendarWeek = 0;
        $this->showFinalizeCalendarModal = false;
        $this->editingCalendar = false;
        $this->syncBuiltProgramState();
        $this->loadAllocationState();
        $this->resetValidation();
        $this->dispatch('success', 'تقویم امتحانات ریست شد. حالا می‌توانی از اول بازه و امتحان‌ها را ثبت کنی.');
        $this->dispatchScrollTop();
    }

    public function reopenCalendarForEditing(ExamPlanningService $service): void
    {
        if (! $this->schedule || $this->usingManagerCalendar()) {
            return;
        }

        try {
            $this->schedule = $service->reopenStudentCalendar($this->schedule);
            $this->editingCalendar = true;
            $this->refreshSchedule($service);
            $this->dispatch('success', 'حالا می‌توانی تقویم امتحانات را ویرایش کنی.');
            $this->dispatchScrollTop();
        } catch (\LogicException $exception) {
            $this->dispatch('warning', $exception->getMessage());
        }
    }

    public function goToPreviousCalendarWeek(): void
    {
        $this->currentCalendarWeek = max(0, $this->currentCalendarWeek - 1);
    }

    public function goToNextCalendarWeek(): void
    {
        $lastIndex = max(0, count($this->calendarDays()) - 1);
        $this->currentCalendarWeek = min($lastIndex, $this->currentCalendarWeek + 1);
    }

    public function incrementAllocation(string $kind, int $id, int $subjectId, ExamPlanningService $service): void
    {
        $this->updateAllocationMinutes($kind, $id, $subjectId, 30, $service);
    }

    public function decrementAllocation(string $kind, int $id, int $subjectId, ExamPlanningService $service): void
    {
        $this->updateAllocationMinutes($kind, $id, $subjectId, -30, $service);
    }

    public function togglePriority(int $subjectId, ExamPlanningService $service): void
    {
        if ($this->programAlreadyBuilt()) {
            $this->dispatch('warning', 'برنامه امتحانی قبلاً ساخته شده و امکان ساخت یا ویرایش مجدد ندارد.');
            return;
        }

        if (! $this->schedule || ! $this->scheduleHasSubject($subjectId)) {
            return;
        }

        $enabled = ! ($this->prioritySubjects[$subjectId] ?? false);
        $service->setPrioritySubject($this->schedule, $subjectId, $enabled);
        $this->prioritySubjects[$subjectId] = $enabled;
        $this->refreshSchedule($service);
    }

    public function setGeneralStudyChapterMode(int $subjectId, ExamPlanningService $service): void
    {
        if ($this->programAlreadyBuilt() || ! $this->schedule || ! $this->scheduleHasSubject($subjectId)) {
            return;
        }

        $service->switchSubjectToChapterMode($this->schedule, $subjectId);
        $this->generalStudyModes[$subjectId] = 'chapter';
        $this->selectedGeneralWholeSubjectId = null;
        $this->showGeneralWholeModal = false;
        $this->refreshSchedule($service);
    }

    public function openGeneralWholeModal(int $subjectId): void
    {
        if ($this->programAlreadyBuilt() || ! $this->schedule || ! $this->scheduleHasSubject($subjectId)) {
            return;
        }

        $this->resetValidation('selectedGeneralWholeMinutes');
        $this->selectedGeneralWholeSubjectId = $subjectId;
        $this->selectedGeneralWholeMinutes = max(60, (int) ($this->allocations['subject_' . $subjectId] ?? 60));
        $this->showGeneralWholeModal = true;
    }

    public function closeGeneralWholeModal(): void
    {
        $this->showGeneralWholeModal = false;
        $this->selectedGeneralWholeSubjectId = null;
        $this->selectedGeneralWholeMinutes = 60;
        $this->resetValidation('selectedGeneralWholeMinutes');
    }

    public function saveGeneralWholeMode(ExamPlanningService $service): void
    {
        if (
            $this->programAlreadyBuilt()
            || ! $this->schedule
            || ! $this->selectedGeneralWholeSubjectId
            || ! $this->scheduleHasSubject($this->selectedGeneralWholeSubjectId)
        ) {
            return;
        }

        $this->validate([
            'selectedGeneralWholeMinutes' => ['required', 'integer', 'min:60', 'max:720', 'multiple_of:30'],
        ], [
            'selectedGeneralWholeMinutes.required' => 'میزان ساعت مطالعه را مشخص کنید.',
            'selectedGeneralWholeMinutes.min' => 'حداقل ۱ ساعت انتخاب کنید.',
            'selectedGeneralWholeMinutes.max' => 'حداکثر ۱۲ ساعت انتخاب کنید.',
            'selectedGeneralWholeMinutes.multiple_of' => 'ساعت مطالعه باید مضربی از ۳۰ دقیقه باشد.',
        ]);

        $service->setWholeSubjectAllocation(
            $this->schedule,
            $this->selectedGeneralWholeSubjectId,
            $this->selectedGeneralWholeMinutes
        );

        $this->generalStudyModes[$this->selectedGeneralWholeSubjectId] = 'whole';
        $this->closeGeneralWholeModal();
        $this->refreshSchedule($service);
        $this->dispatch('success', 'ساعت مطالعه کلی ثبت شد.');
        $this->dispatchScrollTop();
    }

    public function incrementGeneralWholeMinutes(): void
    {
        $this->selectedGeneralWholeMinutes = min(720, $this->selectedGeneralWholeMinutes + 30);
    }

    public function decrementGeneralWholeMinutes(): void
    {
        $this->selectedGeneralWholeMinutes = max(60, $this->selectedGeneralWholeMinutes - 30);
    }

    public function buildProgram(ExamPlanningService $service)
    {
        if (! $this->schedule) {
            return;
        }

        if ($this->programAlreadyBuilt()) {
            session()->put('start_dashboard_tour', true);

            return redirect()->route('client.profile.dashboard');
        }

        $this->resetValidation('buildProgram');

        try {
            $freshSchedule = $this->schedule->fresh([
                'user.student.advisor',
                'user.personalInformation',
                'user.trialWeek',
                'setting',
                'days.subject.grade.educationLevel',
                'allocations.ratable',
            ]);
            $missingSubjects = $this->missingStudySubjects($service->buildCapacityData($freshSchedule));
            $missingChapters = $service->missingStudyChapters($freshSchedule);

            if (! empty($missingSubjects)) {
                $this->addError('buildProgram', 'برای این درس‌ها هنوز ساعت مطالعه ثبت نشده است: ' . implode('، ', $missingSubjects));
                return;
            }

            if (! empty($missingChapters)) {
                $messages = collect($missingChapters)
                    ->map(function (array $item) {
                        return 'برای «' . $item['subject'] . '» فصل‌های «' . implode('»، «', $item['chapters']) . '» هنوز ساعت مطالعه ثبت نشده است.';
                    })
                    ->all();

                $this->addError('buildProgram', implode(' ', $messages));
                return;
            }

            $service->buildProgram($freshSchedule);

            session()->put('start_dashboard_tour', true);
            $this->closeExamDayModal();

            return redirect()->route('client.profile.dashboard');
        } catch (\LogicException $exception) {
            $this->addError('buildProgram', $exception->getMessage());
        }
    }

    public function render(ExamPlanningService $service): View
    {
        $curriculum = $service->curriculumForUser(auth()->user());
        $calendarDays = $this->calendarDays();
        $this->normalizeCurrentCalendarWeek($calendarDays);
        $subjectOptions = $service->subjectOptionsForUser(auth()->user());
        $subjectOptionsByDay = $this->buildSubjectOptionsByDay($calendarDays, $subjectOptions);
        $capacityData = $this->schedule ? $service->buildCapacityData($this->schedule) : ['subjects' => [], 'segments' => []];
        $curriculum = $this->filterCurriculumToScheduledSubjects($curriculum);
        $this->normalizeActiveStudyTab($curriculum);
        $subjectMeta = $this->buildSubjectMeta($curriculum, $capacityData);
        $missingStudySubjects = $this->missingStudySubjects($capacityData);
        $missingStudyChapters = $this->schedule ? $service->missingStudyChapters($this->schedule) : [];

        return view('livewire.client.profile.exam-planning.builder', [
            'calendarDays' => $calendarDays,
            'visibleCalendarWeek' => $calendarDays[$this->currentCalendarWeek] ?? [],
            'calendarWeekCount' => count($calendarDays),
            'currentCalendarWeek' => $this->currentCalendarWeek,
            'subjectOptions' => $subjectOptions,
            'subjectOptionsByDay' => $subjectOptionsByDay,
            'curriculum' => $curriculum,
            'subjectMeta' => $subjectMeta,
            'usingManagerCalendar' => $this->usingManagerCalendar(),
            'calendarReady' => $this->calendarReady(),
            'needsCalendarInput' => $this->needsCalendarInput(),
            'calendarDraftReady' => $this->calendarDraftReady(),
            'calendarFinalized' => $this->calendarFinalized(),
            'editingCalendar' => $this->editingCalendar,
            'programAlreadyBuilt' => $this->programAlreadyBuilt(),
            'builtProgramRoute' => $this->builtProgramId
                ? route('client.profile.consultation.weekly-program', $this->builtProgramId)
                : null,
            'activeExamRows' => $this->schedule?->days?->sortBy('exam_date') ?? collect(),
            'segmentWarnings' => collect($capacityData['segments'])
                ->filter(fn (array $segment) => $segment['entered_minutes'] > $segment['capacity_minutes'])
                ->values(),
            'missingStudySubjects' => $missingStudySubjects,
            'missingStudyChapters' => $missingStudyChapters,
            'canBuildProgram' => empty($missingStudySubjects) && empty($missingStudyChapters) && collect($capacityData['segments'])
                ->filter(fn (array $segment) => $segment['entered_minutes'] > $segment['capacity_minutes'])
                ->isEmpty(),
            'showFinalizeCalendarModal' => $this->showFinalizeCalendarModal,
        ])->layout('layouts.client.app');
    }

    private function updateAllocationMinutes(
        string $kind,
        int $id,
        int $subjectId,
        int $diffMinutes,
        ExamPlanningService $service
    ): void {
        if ($this->programAlreadyBuilt()) {
            $this->dispatch('warning', 'برنامه امتحانی قبلاً ساخته شده و امکان ساخت یا ویرایش مجدد ندارد.');
            return;
        }

        if (! $this->schedule || ! $this->scheduleHasSubject($subjectId)) {
            return;
        }

        $key = $this->allocationKey($kind, $id);
        $current = (int) ($this->allocations[$key] ?? 0);
        $next = $current + $diffMinutes;

        if ($kind === 'chapter') {
            $next = max(30, $next);
        } else {
            $next = max(0, $next);
        }

        $ratableType = $kind === 'chapter' ? CcChapter::class : CcSubject::class;
        $priority = (bool) ($kind === 'subject' && ($this->prioritySubjects[$subjectId] ?? false));

        try {
            if ($next === 0 && ! $priority) {
                $service->removeAllocation($this->schedule, $ratableType, $id);
                unset($this->allocations[$key]);
            } else {
                $service->upsertAllocation($this->schedule, $ratableType, $id, $subjectId, $next, $priority);
                $this->allocations[$key] = $next;
            }
            $this->resetValidation("allocations.{$key}");
        } catch (\LogicException $exception) {
            $this->addError("allocations.{$key}", $exception->getMessage());
            $this->refreshSchedule($service);
            return;
        }

        $this->refreshSchedule($service);
    }

    private function refreshSchedule(ExamPlanningService $service): void
    {
        if (! $this->schedule) {
            return;
        }

        $this->schedule = $service->prepareScheduleForUser(auth()->user());
        $this->calendarStart = $this->toJalali($this->schedule?->exam_starts_at?->toDateString());
        $this->calendarEnd = $this->toJalali($this->schedule?->exam_ends_at?->toDateString());
        $this->syncBuiltProgramState();
        $this->loadAllocationState();
        $this->normalizeCurrentCalendarWeek($this->calendarDays());
    }

    public function programAlreadyBuilt(): bool
    {
        return (bool) $this->builtProgramId;
    }

    private function syncBuiltProgramState(): void
    {
        $this->builtProgramId = null;

        if (! $this->schedule?->weekly_program_id) {
            return;
        }

        $this->builtProgramId = WeeklyProgram::query()
            ->whereKey($this->schedule->weekly_program_id)
            ->value('id');
    }

    private function loadAllocationState(): void
    {
        $this->allocations = [];
        $this->prioritySubjects = [];

        if (! $this->schedule) {
            return;
        }

        $this->schedule->loadMissing('allocations');

        foreach ($this->schedule->allocations as $allocation) {
            $key = $allocation->ratable_type === CcChapter::class
                ? $this->allocationKey('chapter', (int) $allocation->ratable_id)
                : $this->allocationKey('subject', (int) $allocation->ratable_id);

            if ((int) $allocation->planned_minutes > 0) {
                $this->allocations[$key] = (int) $allocation->planned_minutes;
            }

            if ($allocation->is_priority_subject && $allocation->cc_subject_id) {
                $this->prioritySubjects[(int) $allocation->cc_subject_id] = true;
            }

            if ($allocation->ratable_type === CcChapter::class && (int) $allocation->planned_minutes > 0 && $allocation->cc_subject_id) {
                $subjectId = (int) $allocation->cc_subject_id;

                if (! isset($this->generalStudyModes[$subjectId])) {
                    $this->generalStudyModes[$subjectId] = 'chapter';
                }
            }

            if ($allocation->ratable_type === CcSubject::class && (int) $allocation->planned_minutes > 0 && $allocation->cc_subject_id) {
                $this->generalStudyModes[(int) $allocation->cc_subject_id] = 'whole';
            }
        }
    }

    private function buildSubjectMeta(array $curriculum, array $capacityData): array
    {
        $meta = [];

        $general = collect($curriculum['general_subjects']);
        $specialized = collect($curriculum['specialized_subjects']);
        $subjects = $general->concat($specialized);

        foreach ($subjects as $subject) {
            $subjectId = (int) $subject['id'];
            $data = $capacityData['subjects'][$subjectId] ?? null;
            $entered = (int) ($data['subject_entered_minutes'] ?? 0);
            $capacity = (int) ($data['capacity_minutes'] ?? 0);
            $progress = $capacity > 0 ? min(100, round(($entered / $capacity) * 100)) : 0;

            $meta[$subjectId] = [
                'is_scheduled' => $data !== null,
                'exam_date_label' => $data ? jdate($data['exam_date'])->format('Y/m/d') : null,
                'capacity_label' => $this->formatMinutes($capacity),
                'entered_minutes' => $entered,
                'entered_label' => $this->formatMinutes($entered),
                'has_study_time' => $entered > 0,
                'remaining_label' => $this->formatMinutes((int) ($data['remaining_minutes'] ?? 0)),
                'progress' => $progress,
                'day_count' => (int) ($data['day_count'] ?? 0),
                'can_add' => (int) ($data['remaining_minutes'] ?? 0) >= 30,
            ];
        }

        return $meta;
    }

    private function filterCurriculumToScheduledSubjects(array $curriculum): array
    {
        $scheduledSubjectIds = $this->schedule?->days
            ? $this->schedule->days
                ->pluck('cc_subject_id')
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values()
                ->all()
            : [];

        if (empty($scheduledSubjectIds)) {
            return $curriculum;
        }

        $filterSubjects = fn (array $subjects): array => collect($subjects)
            ->filter(fn (array $subject) => in_array((int) $subject['id'], $scheduledSubjectIds, true))
            ->values()
            ->all();

        $curriculum['general_subjects'] = $filterSubjects($curriculum['general_subjects'] ?? []);
        $curriculum['specialized_subjects'] = $filterSubjects($curriculum['specialized_subjects'] ?? []);

        return $curriculum;
    }

    private function normalizeActiveStudyTab(array $curriculum): void
    {
        $specializedCount = count($curriculum['specialized_subjects'] ?? []);
        $generalCount = count($curriculum['general_subjects'] ?? []);
        $activeCount = $this->activeType === 'general' ? $generalCount : $specializedCount;

        if ($activeCount === 0) {
            if ($this->activeType === 'general' && $specializedCount > 0) {
                $this->activeType = 'specialized';
                $activeCount = $specializedCount;
            } elseif ($this->activeType === 'specialized' && $generalCount > 0) {
                $this->activeType = 'general';
                $activeCount = $generalCount;
            }
        }

        $this->activeSubject = $activeCount > 0
            ? max(0, min($this->activeSubject, $activeCount - 1))
            : 0;
    }

    private function buildSubjectOptionsByDay(array $calendarDays, array $subjectOptions): array
    {
        $registeredSubjectIds = $this->schedule?->days
            ? $this->schedule->days->pluck('cc_subject_id')->map(fn ($id) => (int) $id)->all()
            : [];

        $temporarySelections = collect($this->daySubjectSelections)
            ->mapWithKeys(fn ($value, $key) => [$key => (int) $value])
            ->filter(fn (int $value) => $value > 0)
            ->all();

        $optionsByDay = [];

        foreach ($calendarDays as $week) {
            foreach ($week as $day) {
                if (! $day) {
                    continue;
                }

                $selectionKey = $day['selection_key'];
                $currentSelection = (int) ($temporarySelections[$selectionKey] ?? 0);
                $blockedIds = collect($registeredSubjectIds)
                    ->merge(
                        collect($temporarySelections)
                            ->except($selectionKey)
                            ->values()
                    )
                    ->filter(fn ($id) => (int) $id > 0)
                    ->map(fn ($id) => (int) $id)
                    ->unique()
                    ->all();

                $optionsByDay[$selectionKey] = array_values(array_filter(
                    $subjectOptions,
                    fn (array $subject) => ! in_array((int) $subject['id'], $blockedIds, true)
                        || (int) $subject['id'] === $currentSelection
                ));
            }
        }

        return $optionsByDay;
    }

    private function scheduleHasSubject(int $subjectId): bool
    {
        if (! $this->schedule) {
            return false;
        }

        $this->schedule->loadMissing('days');

        return $this->schedule->days
            ->contains(fn ($day) => (int) $day->cc_subject_id === (int) $subjectId);
    }

    private function missingStudySubjects(array $capacityData): array
    {
        if (! $this->schedule?->days) {
            return [];
        }

        return $this->schedule->days
            ->sortBy('exam_date')
            ->filter(function ($day) use ($capacityData) {
                $subjectId = (int) $day->cc_subject_id;

                return (int) ($capacityData['subjects'][$subjectId]['subject_entered_minutes'] ?? 0) <= 0;
            })
            ->map(fn ($day) => $day->subject?->name)
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function calendarDays(): array
    {
        if (! $this->schedule?->exam_starts_at || ! $this->schedule?->exam_ends_at) {
            return [];
        }

        $start = $this->schedule->exam_starts_at->toDateString();
        $end = $this->schedule->exam_ends_at->toDateString();

        if (! $start || ! $end || $end < $start) {
            return [];
        }

        $rows = [];
        $week = [];
        $current = \Carbon\Carbon::parse($start);
        $finish = \Carbon\Carbon::parse($end);
        $dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];

        while ($current->lte($finish)) {
            $jalali = jdate($current);
            $weekday = $jalali->getDayOfWeek();

            if (empty($week)) {
                for ($i = 0; $i < $weekday; $i++) {
                    $week[] = null;
                }
            }

            $week[] = [
                'date' => $current->toDateString(),
                'selection_key' => 'day_' . $current->format('Y_m_d'),
                'jalali' => $jalali->format('Y/m/d'),
                'day_name' => $dayNames[$weekday] ?? '',
                'subjects' => $this->schedule?->days
                    ? $this->schedule->days
                        ->filter(fn ($day) => $day->exam_date?->toDateString() === $current->toDateString())
                        ->values()
                    : collect(),
            ];

            if (count($week) === 7) {
                $rows[] = $week;
                $week = [];
            }

            $current->addDay();
        }

        if (! empty($week)) {
            while (count($week) < 7) {
                $week[] = null;
            }
            $rows[] = $week;
        }

        return $rows;
    }

    private function usingManagerCalendar(): bool
    {
        return $this->schedule?->source_type === StudentExamSchedule::SOURCE_MANAGER;
    }

    private function normalizeCurrentCalendarWeek(array $calendarDays): void
    {
        if (empty($calendarDays)) {
            $this->currentCalendarWeek = 0;
            return;
        }

        $this->currentCalendarWeek = max(0, min($this->currentCalendarWeek, count($calendarDays) - 1));
    }

    private function needsCalendarInput(): bool
    {
        return ! $this->calendarFinalized();
    }

    private function rangeLocked(): bool
    {
        return (bool) ($this->schedule?->exam_starts_at && $this->schedule?->exam_ends_at);
    }

    private function calendarReady(): bool
    {
        return $this->calendarFinalized();
    }

    private function calendarDraftReady(): bool
    {
        return (bool) ($this->schedule && $this->schedule->hasCalendar());
    }

    private function calendarFinalized(): bool
    {
        if (! $this->schedule || ! $this->schedule->hasCalendar()) {
            return false;
        }

        if ($this->usingManagerCalendar()) {
            return true;
        }

        return (bool) $this->schedule->submitted_at;
    }

    private function allocationKey(string $kind, int $id): string
    {
        return $kind . '_' . $id;
    }

    private function formatMinutes(int $minutes): string
    {
        if ($minutes <= 0) {
            return '0 ساعت';
        }

        $hours = intdiv($minutes, 60);
        $remaining = $minutes % 60;

        if ($hours > 0 && $remaining > 0) {
            return "{$hours} ساعت و {$remaining} دقیقه";
        }

        if ($hours > 0) {
            return "{$hours} ساعت";
        }

        return "{$remaining} دقیقه";
    }

    private function fromJalali(?string $value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        $normalized = strtr($value, [
            '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
            '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
            '-' => '/',
        ]);

        try {
            return Jalalian::fromFormat('Y/m/d', $normalized)->toCarbon()->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }

    private function toJalali(?string $value): string
    {
        return $value ? jdate($value)->format('Y/m/d') : '';
    }

    private function isInCurrentJalaliYear(?string $value): bool
    {
        $value = trim((string) $value);
        if ($value === '') {
            return false;
        }

        try {
            return (int) Jalalian::fromFormat('Y/m/d', $value)->format('Y') === $this->calendarYear;
        } catch (\Throwable) {
            return false;
        }
    }

    private function endOfCurrentJalaliYear(): string
    {
        $nextYearStart = Jalalian::fromFormat('Y/m/d', sprintf('%04d/01/01', $this->calendarYear + 1))
            ->toCarbon()
            ->startOfDay();

        return jdate($nextYearStart->copy()->subDay())->format('Y/m/d');
    }

    private function isOnOrAfterToday(?string $value): bool
    {
        $value = trim((string) $value);
        if ($value === '') {
            return false;
        }

        try {
            return Jalalian::fromFormat('Y/m/d', $value)->toCarbon()->startOfDay()
                ->greaterThanOrEqualTo(Jalalian::now()->toCarbon()->startOfDay());
        } catch (\Throwable) {
            return false;
        }
    }

    private function dispatchScrollTop(): void
    {
        $this->dispatch('exam-planning-scroll-top');
    }
}
