<?php

namespace App\Livewire\Admin\Student\Consultation;

use App\Models\Student;
use App\Models\AdvisingSession;
use App\Models\WeeklyProgram;
use App\Models\ProgramPart;
use App\Models\Lesson;
use App\Models\AdvisingPreSession;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class WeeklyProgramUpload extends Component
{
    use WithPagination;

    public $studentId;
    public $sessionId;
    public $weeklyProgramId;

    // اطلاعات برنامه
    public $start_date;
    public $advisor_name;
    public $supporter_name;

    // آرایه‌ی پارت‌ها برای ۷ روز
    public $parts = [];

    // روز انتخاب‌شده برای اضافه کردن پارت
    public $selectedDay = 0;

    // Modal
    public bool $showPartModal = false;
    public ?int $editingPartId = null;

    public array $partForm = [
        'lesson_id' => '',
        'lesson_name' => '',
        'description' => '',
        'duration_minutes' => 60,
        'test_count' => null,
        'part_type' => 'descriptive',
        'lesson_type' => 'specialized',
        'grade' => '10',
    ];

    protected function messages()
    {
        return [
            'start_date.required' => 'تاریخ شروع برنامه الزامی است.',
            'start_date.date' => 'فرمت تاریخ صحیح نیست.',
            'partForm.lesson_name.required' => 'نام درس الزامی است.',
            'partForm.duration_minutes.required' => 'مدت زمان الزامی است.',
            'partForm.duration_minutes.min' => 'مدت زمان باید حداقل ۱ دقیقه باشد.',
            'partForm.part_type.required' => 'نوع پارت الزامی است.',
            'partForm.lesson_type.required' => 'نوع درس الزامی است.',
        ];
    }

    public function mount(Student $student, AdvisingSession $session = null)
    {
        $this->studentId = $student->id;
        $this->sessionId = $session?->id;

        // بارگذاری برنامه‌ی موجود (در صورت وجود)
        $existingProgram = WeeklyProgram::where('student_id', $this->studentId)
            ->when($this->sessionId, fn($q) => $q->where('advising_session_id', $this->sessionId))
            ->latest()
            ->first();

        if ($existingProgram) {
            $this->weeklyProgramId = $existingProgram->id;
            $this->start_date = $existingProgram->start_date->format('Y-m-d');
            $this->advisor_name = $existingProgram->advisor_name;
            $this->supporter_name = $existingProgram->supporter_name;

            $this->loadExistingParts();
        } else {
            // تاریخ پیش‌فرض: فردا
            $this->start_date = Carbon::tomorrow()->format('Y-m-d');

            // نام مشاور از ادمین فعلی
            $admin = auth()->user();
            $this->advisor_name = $admin->name ?? '';
        }

        // اطمینان از وجود آرایه برای هر ۷ روز
        for ($i = 0; $i < 7; $i++) {
            if (!isset($this->parts[$i])) {
                $this->parts[$i] = [];
            }
        }
    }

    protected function loadExistingParts(): void
    {
        if (!$this->weeklyProgramId) {
            return;
        }

        $program = WeeklyProgram::find($this->weeklyProgramId);
        if (!$program) {
            return;
        }

        $this->parts = [];

        for ($i = 0; $i < 7; $i++) {
            $this->parts[$i] = $program->parts()
                ->where('day_of_week', $i)
                ->orderBy('part_order')
                ->get()
                ->toArray();
        }
    }

    public function openPartModal(int $dayIndex): void
    {
        $this->selectedDay = $dayIndex;
        $this->resetPartForm();
        $this->showPartModal = true;
    }

    public function editPart(int $partId): void
    {
        $part = ProgramPart::find($partId);

        if (!$part) {
            return;
        }

        $this->editingPartId = $partId;
        $this->selectedDay = $part->day_of_week;

        $this->partForm = [
            'lesson_id' => $part->lesson_id,
            'lesson_name' => $part->lesson_name,
            'description' => $part->description,
            'duration_minutes' => $part->duration_minutes,
            'test_count' => $part->test_count,
            'part_type' => $part->part_type,
            'lesson_type' => $part->lesson_type,
            'grade' => $part->grade,
        ];

        $this->showPartModal = true;
    }

    public function resetPartForm(): void
    {
        $this->editingPartId = null;
        $this->partForm = [
            'lesson_id' => '',
            'lesson_name' => '',
            'description' => '',
            'duration_minutes' => 60,
            'test_count' => null,
            'part_type' => 'descriptive',
            'lesson_type' => 'specialized',
            'grade' => '10',
        ];
    }

    public function closePartModal(): void
    {
        $this->showPartModal = false;
        $this->resetPartForm();
    }

    public function selectLesson($lessonId): void
    {
        $lesson = Lesson::find($lessonId);

        if (!$lesson) {
            return;
        }

        $this->partForm['lesson_id'] = $lesson->id;
        $this->partForm['lesson_name'] = $lesson->name;
        $this->partForm['lesson_type'] = $lesson->type;
        $this->partForm['grade'] = $lesson->grade;
    }

    public function savePart(): void
    {
        $this->validate([
            'partForm.lesson_name' => 'required|string|max:255',
            'partForm.duration_minutes' => 'required|integer|min:1',
            'partForm.part_type' => 'required|in:test,descriptive,video',
            'partForm.lesson_type' => 'required|in:general,specialized',
        ], $this->messages());

        // اول خود برنامه را ذخیره/آپدیت کن
        $this->saveProgram();

        $partDate = Carbon::parse($this->start_date)->addDays($this->selectedDay);

        if ($this->editingPartId) {
            // ویرایش پارت موجود
            $part = ProgramPart::find($this->editingPartId);

            if (!$part) {
                return;
            }

            $part->update([
                'lesson_id' => $this->partForm['lesson_id'] ?: null,
                'lesson_name' => $this->partForm['lesson_name'],
                'description' => $this->partForm['description'],
                'duration_minutes' => $this->partForm['duration_minutes'],
                'test_count' => $this->partForm['test_count'],
                'part_type' => $this->partForm['part_type'],
                'lesson_type' => $this->partForm['lesson_type'],
                'grade' => $this->partForm['grade'],
            ]);
        } else {
            // چک حداکثر ۱۰ پارت
            $existingCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
                ->where('day_of_week', $this->selectedDay)
                ->count();

            if ($existingCount >= 10) {
                $this->dispatch('warning', 'حداکثر ۱۰ پارت برای هر روز مجاز است.');
                return;
            }

            ProgramPart::create([
                'weekly_program_id' => $this->weeklyProgramId,
                'lesson_id' => $this->partForm['lesson_id'] ?: null,
                'lesson_name' => $this->partForm['lesson_name'],
                'part_date' => $partDate,
                'day_of_week' => $this->selectedDay,
                'part_order' => $existingCount + 1,
                'description' => $this->partForm['description'],
                'duration_minutes' => $this->partForm['duration_minutes'],
                'test_count' => $this->partForm['test_count'],
                'part_type' => $this->partForm['part_type'],
                'lesson_type' => $this->partForm['lesson_type'],
                'grade' => $this->partForm['grade'],
            ]);
        }

        $this->loadExistingParts();
        $this->closePartModal();

        $this->dispatch('success', 'پارت با موفقیت ذخیره شد.');
    }

    public function deletePart(int $partId): void
    {
        $part = ProgramPart::find($partId);

        if (!$part) {
            return;
        }

        $part->delete();

        $this->loadExistingParts();
        $this->dispatch('success', 'پارت حذف شد.');
    }

    public function saveProgram(): void
    {
        $this->validate([
            'start_date' => 'required|date',
        ], $this->messages());

        $startDate = Carbon::parse($this->start_date);
        $endDate = $startDate->copy()->addDays(6);

        if ($this->weeklyProgramId) {
            $program = WeeklyProgram::find($this->weeklyProgramId);

            if (!$program) {
                return;
            }

            $program->update([
                'start_date' => $startDate,
                'end_date' => $endDate,
                'advisor_name' => $this->advisor_name,
                'supporter_name' => $this->supporter_name,
            ]);
        } else {
            $program = WeeklyProgram::create([
                'student_id' => $this->studentId,
                'advisor_id' => auth()->id(),
                'advising_session_id' => $this->sessionId,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'advisor_name' => $this->advisor_name,
                'supporter_name' => $this->supporter_name,
                'is_active' => true,
            ]);

            $this->weeklyProgramId = $program->id;
        }
    }

    public function finalSave(): void
    {
        $this->saveProgram();
        $this->dispatch('success', 'برنامه هفتگی با موفقیت ذخیره شد.');
    }

    public function render()
    {
        $student = Student::with(['user.personalInformation', 'advisor'])->find($this->studentId);
        $lessons = Lesson::active()->get();
        $weeklyProgram = $this->weeklyProgramId
            ? WeeklyProgram::with('parts')->find($this->weeklyProgramId)
            : null;

        // محاسبه روزهای هفته
        $weekDays = [];
        $dayNames = ['شنبه', '۱شنبه', '۲شنبه', '۳شنبه', '۴شنبه', '۵شنبه', 'جمعه'];
        $startDate = $this->start_date
            ? Carbon::parse($this->start_date)
            : Carbon::tomorrow();

        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dayParts = $weeklyProgram
                ? $weeklyProgram->parts()->where('day_of_week', $i)->orderBy('part_order')->get()
                : collect();

            $weekDays[] = [
                'index' => $i,
                'name' => $dayNames[$i],
                'date' => $date,
                'jalali_date' => jdate($date)->format('m/d'),
                'parts' => $dayParts,
                'total_hours' => round($dayParts->sum('duration_minutes') / 60, 1),
                'total_tests' => $dayParts->sum('test_count') ?? 0,
            ];
        }

        $preSessions = AdvisingPreSession::where('student_id', $this->studentId)
            ->with(['advisingSession', 'exams', 'assignments', 'qas', 'miscellaneous'])
            ->latest()
            ->get();

        return view('livewire.admin.student.consultation.weekly-program-upload', [
            'student' => $student,
            'lessons' => $lessons,
            'weeklyProgram' => $weeklyProgram,
            'weekDays' => $weekDays,
            'preSessions' => $preSessions,
        ])->layout('layouts.admin.app');
    }
}
