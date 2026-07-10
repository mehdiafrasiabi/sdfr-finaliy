<?php

namespace App\Livewire\Client\Profile\Consultation;

use App\Models\ClassSchedule;
use App\Models\ClassSchedulePart;
use App\Models\CcSubject;
use App\Models\CcGrade;
use App\Models\CcField;
use App\Models\PersonalInformation;
use App\Models\Student;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ClassScheduleUpload extends Component
{
    use SEOTools;

    private const MAX_ATTENDS_SCHOOL_CHANGES = 2;

    public bool $showModal = false;
    public ?int $selectedDay = null;
    public ?int $selectedPart = null;

    /** @var array<int> آی‌دی درس‌های انتخاب‌شده — به ترتیب کلیک */
    public array $selectedSubjectIds = [];

    public bool $showFinalizeModal = false;

    public ?int $classScheduleId = null;
    public bool $isFinalized = false;

    public ?string $studentGrade = null;
    public ?string $studentField = null;
    public bool $attendsSchool = true;
    public bool $isGraduate = false;
    public int $attendsSchoolChangeCount = 0;
    public ?string $returnTo = null;

    public $subjects = [];

    public function mount()
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->route('client.profile.consultation.sessions');
        }

        if (! $student->advisor_id && ! $student->is_trial) {
            session()->flash('error', 'برای دسترسی به این بخش باید مشاور داشته باشید.');
            return redirect()->route('client.profile.consultation.sessions');
        }

        $personalInfo = PersonalInformation::where('user_id', $user->id)->first();
        if ($personalInfo) {
            $this->studentGrade = $personalInfo->grade;
            $this->studentField = $personalInfo->field;
            $this->attendsSchool = (bool) ($personalInfo->attends_school ?? true);
            $this->isGraduate = (bool) ($personalInfo->is_graduate ?? false);
            $this->attendsSchoolChangeCount = (int) ($personalInfo->attends_school_change_count ?? 0);
        }

        $this->returnTo = request()->query('return_to');

        $schedule = ClassSchedule::where('student_id', $student->id)->latest()->first();
        if ($schedule) {
            $this->classScheduleId = $schedule->id;
            $this->isFinalized = $schedule->is_finalized;
        }

        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()->setTitle('برنامه هفتگی');
    }

    public function getRemainingAttendsSchoolChangesProperty(): int
    {
        return max(0, self::MAX_ATTENDS_SCHOOL_CHANGES - $this->attendsSchoolChangeCount);
    }

    public function getAttendsSchoolSwitchLockedProperty(): bool
    {
        return $this->isGraduate || $this->remainingAttendsSchoolChanges <= 0;
    }

    public function getShouldShowScheduleEditorProperty(): bool
    {
        return ! $this->isGraduate && $this->attendsSchool;
    }

    private function ensureScheduleEditorAvailable(): bool
    {
        if ($this->shouldShowScheduleEditor) {
            return true;
        }

        $this->dispatch('warning', 'تا وقتی وضعیت شما روی «مدرسه نمی‌روم» باشد، امکان ثبت برنامه کلاسی وجود ندارد.');
        return false;
    }

    public function updatedAttendsSchool($value): void
    {
        $newStatus = (bool) $value;
        $user = Auth::user();
        $personalInfo = $user?->personalInformation;

        if (! $personalInfo) {
            $this->attendsSchool = true;
            $this->dispatch('warning', 'اطلاعات تحصیلی شما پیدا نشد.');
            return;
        }

        $currentStatus = (bool) ($personalInfo->attends_school ?? true);

        if ($newStatus === $currentStatus) {
            return;
        }

        if ($this->attendsSchoolSwitchLocked) {
            $this->attendsSchool = $currentStatus;
            $this->dispatch('warning', $this->isGraduate
                ? 'برای دانش‌آموز فارغ‌التحصیل امکان تغییر این وضعیت وجود ندارد.'
                : 'امکان تغییر وضعیت مدرسه فقط تا ۲ بار وجود دارد و حالا قفل شده است.');
            return;
        }

        $personalInfo->update([
            'attends_school' => $newStatus,
            'attends_school_change_count' => $personalInfo->attends_school_change_count + 1,
        ]);

        if ($trial = $user?->trialWeek) {
            $trial->update([
                'attends_school' => $newStatus && ! $trial->isGraduate(),
            ]);
        }

        $this->attendsSchool = $newStatus;
        $this->attendsSchoolChangeCount = (int) $personalInfo->fresh()->attends_school_change_count;

        $this->dispatch('success', $newStatus
            ? 'وضعیت شما به «مدرسه می‌روم» تغییر کرد.'
            : 'وضعیت شما به «مدرسه نمی‌روم» تغییر کرد.');
    }

    protected function loadSubjects(): void
    {
        if (!$this->studentGrade || !$this->studentField) {
            $this->subjects = [];
            return;
        }

        $gradeNumber = (int) $this->studentGrade;
        $fieldSlug = CcField::mapFromPersonalInfo($this->studentField);
        $field = $fieldSlug ? CcField::where('slug', $fieldSlug)->first() : null;

        $grades = CcGrade::where('grade_number', $gradeNumber)
            ->where('is_active', true)
            ->pluck('id');

        if ($grades->isEmpty()) {
            $this->subjects = [];
            return;
        }

        $query = CcSubject::whereIn('cc_grade_id', $grades);

        if ($field) {
            $query->where(function ($q) use ($field) {
                $q->whereNull('cc_field_id')
                    ->orWhere('cc_field_id', $field->id);
            });
        }

        $this->subjects = $query->orderBy('order')->get();
    }

    public function openPartModal(int $dayOfWeek, int $partOrder): void
    {
        if (! $this->ensureScheduleEditorAvailable()) {
            return;
        }

        $schedule = $this->classScheduleId ? ClassSchedule::find($this->classScheduleId) : null;

        if ($partOrder > 1 && $schedule) {
            $previousPart = ClassSchedulePart::where('class_schedule_id', $schedule->id)
                ->where('day_of_week', $dayOfWeek)
                ->where('part_order', $partOrder - 1)
                ->first();

            if (!$previousPart) {
                $this->dispatch('warning', 'ابتدا باید پارت ' . ($partOrder - 1) . ' را پر کنید.');
                return;
            }
        }

        // ریست انتخاب‌ها قبل از set کردن
        $this->selectedSubjectIds = [];

        // اگر این پارت قبلاً پر شده، درس فعلی‌اش به‌عنوان انتخاب اولیه
        if ($schedule) {
            $existingPart = ClassSchedulePart::where('class_schedule_id', $schedule->id)
                ->where('day_of_week', $dayOfWeek)
                ->where('part_order', $partOrder)
                ->first();

            if ($existingPart) {
                $this->selectedSubjectIds = [$existingPart->cc_subject_id];
            }
        }

        $this->selectedDay = $dayOfWeek;
        $this->selectedPart = $partOrder;

        $this->loadSubjects();
        $this->showModal = true;
    }

    /**
     * تغییر وضعیت انتخاب یک درس (toggle)
     */
    public function toggleSubject(int $subjectId): void
    {
        if (in_array($subjectId, $this->selectedSubjectIds, true)) {
            // اگر انتخاب بود، حذفش کن (و ایندکس‌ها رو reset کن)
            $this->selectedSubjectIds = array_values(array_diff($this->selectedSubjectIds, [$subjectId]));
            return;
        }

        // محدودیت: تعداد انتخاب نمی‌تواند از اسلات‌های باقی‌مانده بیشتر شود
        $availableSlots = ClassSchedule::MAX_PARTS_PER_DAY - ($this->selectedPart ?? 1) + 1;
        if (count($this->selectedSubjectIds) >= $availableSlots) {
            $this->dispatch('warning', 'حداکثر ' . $availableSlots . ' درس می‌توانید برای این روز انتخاب کنید.');
            return;
        }

        $this->selectedSubjectIds[] = $subjectId;
    }

    public function savePart(): void
    {
        if (! $this->ensureScheduleEditorAvailable()) {
            return;
        }

        if ($this->selectedDay === null || $this->selectedPart === null) {
            $this->dispatch('warning', 'خطا: اطلاعات پارت مشخص نیست. دوباره امتحان کنید.');
            return;
        }

        if (empty($this->selectedSubjectIds)) {
            $this->dispatch('warning', 'لطفاً حداقل یک درس انتخاب کنید.');
            return;
        }

        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return;
        }

        if (!$this->classScheduleId) {
            $schedule = ClassSchedule::create([
                'student_id' => $student->id,
                'is_finalized' => false,
            ]);
            $this->classScheduleId = $schedule->id;
        }

        $savedCount = 0;

        // هر درس انتخاب‌شده به‌ترتیب در یک پارت متوالی ذخیره می‌شود
        foreach ($this->selectedSubjectIds as $i => $subjectId) {
            $partOrder = $this->selectedPart + $i;

            if ($partOrder > ClassSchedule::MAX_PARTS_PER_DAY) {
                break;
            }

            $subject = CcSubject::find($subjectId);
            if (!$subject) continue;

            ClassSchedulePart::updateOrCreate(
                [
                    'class_schedule_id' => $this->classScheduleId,
                    'day_of_week'       => $this->selectedDay,
                    'part_order'        => $partOrder,
                ],
                [
                    'cc_subject_id' => $subjectId,
                    'lesson_name'   => $subject->name,
                ]
            );

            $savedCount++;
        }

        $this->closeModal();
        $this->dispatch('close-part-modal');
        $this->dispatch(
            'success',
            $savedCount === 1
                ? 'پارت با موفقیت ذخیره شد.'
                : $savedCount . ' پارت با موفقیت ذخیره شدند.'
        );
    }

    public function deletePart(int $dayOfWeek, int $partOrder): void
    {
        if (! $this->ensureScheduleEditorAvailable()) {
            return;
        }

        if (!$this->classScheduleId) {
            return;
        }

        ClassSchedulePart::where('class_schedule_id', $this->classScheduleId)
            ->where('day_of_week', $dayOfWeek)
            ->where('part_order', $partOrder)
            ->delete();

        $nextParts = ClassSchedulePart::where('class_schedule_id', $this->classScheduleId)
            ->where('day_of_week', $dayOfWeek)
            ->where('part_order', '>', $partOrder)
            ->orderBy('part_order')
            ->get();

        foreach ($nextParts as $part) {
            $part->update(['part_order' => $part->part_order - 1]);
        }

        $this->dispatch('success', 'پارت حذف شد.');
    }

    public function deleteAllDayParts(int $dayOfWeek): void
    {
        if (! $this->ensureScheduleEditorAvailable()) {
            return;
        }

        if (!$this->classScheduleId) {
            return;
        }

        ClassSchedulePart::where('class_schedule_id', $this->classScheduleId)
            ->where('day_of_week', $dayOfWeek)
            ->delete();

        $this->dispatch('success', 'تمامی پارت‌های این روز حذف شدند.');
    }

    public function openFinalizeModal(): void
    {
        if (! $this->ensureScheduleEditorAvailable()) {
            return;
        }

        if (!$this->classScheduleId) {
            $this->dispatch('warning', 'ابتدا باید حداقل یک پارت ثبت کنید.');
            return;
        }

        $schedule = ClassSchedule::find($this->classScheduleId);
        if (!$schedule || !$schedule->canFinalize()) {
            $this->dispatch('warning', 'برای ثبت نهایی باید حداقل یک پارت ثبت شده باشد.');
            return;
        }

        $this->showFinalizeModal = true;
    }

    public function closeFinalizeModal(): void
    {
        $this->showFinalizeModal = false;
    }

    public function finalizeSchedule(\App\Services\TrialWeekService $trialService): void
    {
        if (! $this->ensureScheduleEditorAvailable()) {
            return;
        }

        if (!$this->classScheduleId) {
            $this->dispatch('warning', 'ابتدا باید حداقل یک پارت ثبت کنید.');
            return;
        }

        $schedule = ClassSchedule::find($this->classScheduleId);
        if (!$schedule || !$schedule->canFinalize()) {
            $this->dispatch('warning', 'برای ثبت نهایی باید حداقل یک پارت ثبت شده باشد.');
            return;
        }

        $wasFinalized = (bool) $schedule->is_finalized;
        $schedule->update([
            'is_finalized' => true,
            'finalized_at' => now(),
        ]);

        $this->isFinalized = true;
        $this->showFinalizeModal = false;

        $this->dispatch('success', $wasFinalized
            ? 'تغییرات برنامه کلاسی با موفقیت به‌روزرسانی و نهایی شد.'
            : 'برنامه کلاسی با موفقیت نهایی شد.');

        $trial = \Illuminate\Support\Facades\Auth::user()?->trialWeek;
        if ($trial) {
            if ($trial->status === \App\Models\TrialWeek::STATUS_CLASSIFICATION_DONE
                && $trial->advisingSession?->preSession?->status === 'completed') {
                $trialService->completePreSession($trial);
            }
            $this->redirect(route('client.profile.trial.guide'), navigate: true);
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedDay = null;
        $this->selectedPart = null;
        $this->selectedSubjectIds = [];
        $this->subjects = [];
    }

    public function render()
    {
        $user = auth()->user();
        $student = Student::with('user.personalInformation')->where('user_id', $user->id)->first();

        $schedule = $this->classScheduleId
            ? ClassSchedule::with('parts')->find($this->classScheduleId)
            : null;

        $days = [];
        for ($d = 0; $d < 7; $d++) {
            $dayParts = [];
            for ($p = 1; $p <= ClassSchedule::MAX_PARTS_PER_DAY; $p++) {
                $part = $schedule
                    ? $schedule->parts->where('day_of_week', $d)->where('part_order', $p)->first()
                    : null;

                $isUnlocked = $p === 1
                    ? true
                    : ($schedule
                        ? $schedule->parts->where('day_of_week', $d)->where('part_order', $p - 1)->first() !== null
                        : false);

                $dayParts[] = [
                    'order'      => $p,
                    'part'       => $part,
                    'is_unlocked'=> $isUnlocked,
                    'is_filled'  => $part !== null,
                ];
            }

            $filledCount = collect($dayParts)->where('is_filled', true)->count();

            $days[] = [
                'day_of_week'  => $d,
                'name'         => ClassSchedule::getDayName($d),
                'parts'        => $dayParts,
                'filled_count' => $filledCount,
                'is_mandatory' => false,
                'is_complete'  => $filledCount > 0,
            ];
        }

        $canFinalize = $schedule ? $schedule->canFinalize() : false;

        return view('livewire.client.profile.consultation.class-schedule-upload', [
            'student'     => $student,
            'schedule'    => $schedule,
            'days'        => $days,
            'canFinalize' => $canFinalize,
            'backUrl'     => $this->returnTo && str_starts_with($this->returnTo, url('/'))
                ? $this->returnTo
                : null,
        ])->layout('layouts.client.app');
    }
}
