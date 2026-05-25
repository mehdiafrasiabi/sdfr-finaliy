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
use Livewire\Component;

class ClassScheduleUpload extends Component
{
    use SEOTools;
    // مودال انتخاب درس
    public bool $showModal = false;
    public ?int $selectedDay = null;
    public ?int $selectedPart = null;
    public ?int $selectedSubjectId = null;
    public bool $showFinalizeModal = false;
    // وضعیت برنامه
    public ?int $classScheduleId = null;
    public bool $isFinalized = false;

    // اطلاعات دانش‌آموز
    public ?string $studentGrade = null;
    public ?string $studentField = null;

    // لیست درس‌ها
    public $subjects = [];

    public function mount()
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->route('client.profile.consultation.sessions');
        }

        // بررسی وجود مشاور (دانش‌آموزان هفته آزمایشی هنوز مشاور ندارند و مجاز هستند)
        if (! $student->advisor_id && ! $student->is_trial) {
            session()->flash('error', 'برای دسترسی به این بخش باید مشاور داشته باشید.');
            return redirect()->route('client.profile.consultation.sessions');
        }

        // دریافت اطلاعات شخصی
        $personalInfo = PersonalInformation::where('user_id', $user->id)->first();
        if ($personalInfo) {
            $this->studentGrade = $personalInfo->grade;
            $this->studentField = $personalInfo->field;
        }

        // بارگذاری برنامه کلاسی موجود
        $schedule = ClassSchedule::where('student_id', $student->id)->latest()->first();
        if ($schedule) {
            $this->classScheduleId = $schedule->id;
            $this->isFinalized = $schedule->is_finalized;
        }

        // بارگذاری درس‌ها بر اساس پایه و رشته
        $this->loadSubjects();
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()->setTitle('برنامه هفتگی');
    }
    protected function loadSubjects(): void
    {
        if (!$this->studentGrade || !$this->studentField) {
            $this->subjects = [];
            return;
        }

        // پیدا کردن cc_grade_id بر اساس grade_number (personal_information grade is '10','11','12')
        $gradeNumber = (int) $this->studentGrade;

        // پیدا کردن cc_field بر اساس slug
        $fieldSlug = CcField::mapFromPersonalInfo($this->studentField);
        $field = $fieldSlug ? CcField::where('slug', $fieldSlug)->first() : null;

        // یافتن تمام پایه‌های مطابق
        $grades = CcGrade::where('grade_number', $gradeNumber)
            ->where('is_active', true)
            ->pluck('id');

        if ($grades->isEmpty()) {
            $this->subjects = [];
            return;
        }

        // یافتن درس‌ها: عمومی + تخصصی مطابق رشته
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
        $schedule = $this->classScheduleId ? ClassSchedule::find($this->classScheduleId) : null;

        // بررسی ترتیب پر شدن: پارت قبلی باید پر شده باشد
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

        // بررسی اینکه قبلاً پر شده باشد (ویرایش)
        if ($schedule) {
            $existingPart = ClassSchedulePart::where('class_schedule_id', $schedule->id)
                ->where('day_of_week', $dayOfWeek)
                ->where('part_order', $partOrder)
                ->first();

            if ($existingPart) {
                $this->selectedSubjectId = $existingPart->cc_subject_id;
            } else {
                $this->selectedSubjectId = null;
            }
        }

        $this->selectedDay = $dayOfWeek;
        $this->selectedPart = $partOrder;
        $this->showModal = true;
    }

    public function savePart(): void
    {

        if (!$this->selectedSubjectId) {
            $this->dispatch('warning', 'لطفاً یک درس انتخاب کنید.');
            return;
        }

        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return;
        }

        // ساخت یا بازیابی برنامه کلاسی
        if (!$this->classScheduleId) {
            $schedule = ClassSchedule::create([
                'student_id' => $student->id,
                'is_finalized' => false,
            ]);
            $this->classScheduleId = $schedule->id;
        }

        $subject = CcSubject::find($this->selectedSubjectId);
        $lessonName = $subject ? $subject->name : '';

        // ذخیره یا آپدیت پارت
        ClassSchedulePart::updateOrCreate(
            [
                'class_schedule_id' => $this->classScheduleId,
                'day_of_week' => $this->selectedDay,
                'part_order' => $this->selectedPart,
            ],
            [
                'cc_subject_id' => $this->selectedSubjectId,
                'lesson_name' => $lessonName,
            ]
        );

        $this->closeModal();
        $this->dispatch('success', 'پارت با موفقیت ذخیره شد.');
    }

    public function deletePart(int $dayOfWeek, int $partOrder): void
    {
        if (!$this->classScheduleId) {
            return;
        }

        // حذف همین پارت
        ClassSchedulePart::where('class_schedule_id', $this->classScheduleId)
            ->where('day_of_week', $dayOfWeek)
            ->where('part_order', $partOrder)
            ->delete();

        // شیفت دادن پارت‌های بعدی به جلو
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

    public function finalizeSchedule(): void
    {
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
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedDay = null;
        $this->selectedPart = null;
        $this->selectedSubjectId = null;
    }

    public function render()
    {
        $user = auth()->user();
        $student = Student::with('user.personalInformation')->where('user_id', $user->id)->first();

        $schedule = $this->classScheduleId ? ClassSchedule::with('parts')->find($this->classScheduleId) : null;

        // ساختن آرایه روزها و پارت‌ها
        $days = [];
        for ($d = 0; $d < 7; $d++) {
            $dayParts = [];
            for ($p = 1; $p <= ClassSchedule::MAX_PARTS_PER_DAY; $p++) {
                $part = $schedule
                    ? $schedule->parts->where('day_of_week', $d)->where('part_order', $p)->first()
                    : null;

                if ($p === 1) {
                    $isUnlocked = true;
                } else {
                    // پارت قبلی باید پر شده باشد
                    $prevPart = $schedule
                        ? $schedule->parts->where('day_of_week', $d)->where('part_order', $p - 1)->first()
                        : null;
                    $isUnlocked = $prevPart !== null;
                }

                $dayParts[] = [
                    'order' => $p,
                    'part' => $part,
                    'is_unlocked' => $isUnlocked,
                    'is_filled' => $part !== null,
                ];
            }

            $filledCount = collect($dayParts)->where('is_filled', true)->count();

            $days[] = [
                'day_of_week' => $d,
                'name' => ClassSchedule::getDayName($d),
                'parts' => $dayParts,
                'filled_count' => $filledCount,
                'is_mandatory' => false,
                'is_complete' => $filledCount > 0,
            ];
        }

        // بررسی آیا ثبت نهایی ممکن است
        $canFinalize = $schedule ? $schedule->canFinalize() : false;

        return view('livewire.client.profile.consultation.class-schedule-upload', [
            'student' => $student,
            'schedule' => $schedule,
            'days' => $days,
            'canFinalize' => $canFinalize,
        ])->layout('layouts.client.app');
    }
}
