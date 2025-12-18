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
use App\Models\EducationLevel;
use App\Models\CcGrade;
use App\Models\CcField;
use App\Models\CcSubject;
use App\Models\CcChapter;
use App\Models\CcTopic;

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
        'education_level_id' => '',
        'cc_grade_id' => '',
        'cc_field_id' => '',
        'cc_subject_id' => '',
        'cc_chapter_id' => '',
        'cc_topic_id' => '',
        'lesson_name' => '',
        'description' => '',
        'duration_minutes' => 60,
        'test_count' => null,
        'part_type' => 'descriptive',
        'lesson_type' => 'specialized',
        'grade' => '',
    ];

    public $grades = [];
    public $fields = [];
    public $subjects = [];
    public $chapters = [];
    public $topics = [];

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
            'education_level_id' => '',

            'cc_grade_id' => '',

            'cc_field_id' => '',

            'cc_subject_id' => '',

            'cc_chapter_id' => '',

            'cc_topic_id' => '',

            'lesson_name' => '',

            'description' => '',

            'duration_minutes' => 60,

            'test_count' => null,

            'part_type' => 'descriptive',

            'lesson_type' => 'specialized',

            'grade' => '',

        ];
        $this->grades = [];
        $this->fields = [];
        $this->subjects = [];
        $this->chapters = [];
        $this->topics = [];
    }

    public function updatedPartFormEducationLevelId($value): void
    {
        $this->partForm['cc_grade_id'] = '';
        $this->partForm['cc_field_id'] = '';
        $this->partForm['cc_subject_id'] = '';
        $this->partForm['cc_chapter_id'] = '';
        $this->partForm['cc_topic_id'] = '';
        $this->subjects = [];
        $this->chapters = [];
        $this->topics = [];

        if ($value) {
            $this->grades = CcGrade::where('education_level_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();
        } else {
            $this->grades = [];
        }
        $this->fields = CcField::active()->ordered()->get();
    }

    public function updatedPartFormCcGradeId($value): void
    {
        $this->partForm['cc_subject_id'] = '';
        $this->partForm['cc_chapter_id'] = '';
        $this->partForm['cc_topic_id'] = '';
        $this->chapters = [];
        $this->topics = [];
        if ($value) {
            $grade = CcGrade::find($value);
            if ($grade) {
                $this->partForm['grade'] = $grade->grade_number;
            }
            // بررسی رشته‌های مربوط به این پایه
            $fieldId = $this->partForm['cc_field_id'] ?: null;
            $this->subjects = CcSubject::where('cc_grade_id', $value)
                ->when($fieldId, fn($q) => $q->where('cc_field_id', $fieldId))
                ->when(!$fieldId, fn($q) => $q->whereNull('cc_field_id'))
                ->orderBy('order')
                ->get();
        } else {
            $this->subjects = [];
        }
    }

    public function updatedPartFormCcFieldId($value): void
    {
        $this->partForm['cc_subject_id'] = '';
        $this->partForm['cc_chapter_id'] = '';
        $this->partForm['cc_topic_id'] = '';
        $this->chapters = [];
        $this->topics = [];
        if ($this->partForm['cc_grade_id']) {
            $this->subjects = CcSubject::where('cc_grade_id', $this->partForm['cc_grade_id'])
                ->when($value, fn($q) => $q->where('cc_field_id', $value))
                ->when(!$value, fn($q) => $q->whereNull('cc_field_id'))
                ->orderBy('order')
                ->get();
        }
    }

    public function updatedPartFormCcSubjectId($value): void
    {
        $this->partForm['cc_chapter_id'] = '';
        $this->partForm['cc_topic_id'] = '';
        $this->topics = [];

        if ($value) {
            $subject = CcSubject::find($value);
            if ($subject) {
                $this->partForm['lesson_name'] = $subject->name;
                $this->partForm['lesson_type'] = $subject->type;
            }
            $this->chapters = CcChapter::where('cc_subject_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();
        } else {
            $this->chapters = [];
        }
    }

    public function updatedPartFormCcChapterId($value): void
    {
        $this->partForm['cc_topic_id'] = '';
        if ($value) {
            $this->topics = CcTopic::where('cc_chapter_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();
        } else {
            $this->topics = [];
        }
    }

    public function updatedPartFormCcTopicId($value): void
    {
        if ($value) {
            $topic = CcTopic::with('chapter.subject')->find($value);
            if ($topic) {
                $chapter = $topic->chapter;
                $subject = $chapter->subject;
                $this->partForm['description'] = $subject->name . ' » ' . $chapter->name . ' » ' . $topic->name;
            }
        }
    }

    public function closePartModal(): void
    {
        $this->showPartModal = false;
        $this->resetPartForm();
    }

    public function savePart(): void
    {
        $this->validate([
            'partForm.cc_subject_id' => 'required|exists:cc_subjects,id',
            'partForm.duration_minutes' => 'required|integer|min:1',
            'partForm.part_type' => 'required|in:test,descriptive,video',
        ], [
            'partForm.cc_subject_id.required' => 'انتخاب درس الزامی است.',
            'partForm.duration_minutes.required' => 'مدت زمان الزامی است.',
            'partForm.duration_minutes.min' => 'مدت زمان باید حداقل ۱ دقیقه باشد.',
            'partForm.part_type.required' => 'نوع پارت الزامی است.',
        ]);

        $gradeValue = $this->partForm['grade'] !== '' && $this->partForm['grade'] !== null
            ? (string) $this->partForm['grade']
            : null;


        // اول خود برنامه را ذخیره/آپدیت کن
        $this->saveProgram();
        $partDate = Carbon::parse($this->start_date)->addDays($this->selectedDay);
        // دریافت اطلاعات درس
        $subject = CcSubject::find($this->partForm['cc_subject_id']);
        $lessonName = $subject ? $subject->name : $this->partForm['lesson_name'];
        $lessonType = $subject ? $subject->type : 'specialized';
        if ($this->editingPartId) {
            // ویرایش پارت موجود
            $part = ProgramPart::find($this->editingPartId);

            if (!$part) {
                return;
            }
            $part->update([
                'lesson_id' => null,
                'lesson_name' => $lessonName,
                'description' => $this->partForm['description'],
                'duration_minutes' => $this->partForm['duration_minutes'],
                'test_count' => $this->partForm['test_count'],
                'part_type' => $this->partForm['part_type'],
                'lesson_type' => $lessonType,
                'grade' => $gradeValue,
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
                'lesson_id' => null,
                'lesson_name' => $lessonName,
                'part_date' => $partDate,
                'day_of_week' => $this->selectedDay,
                'part_order' => $existingCount + 1,
                'description' => $this->partForm['description'],
                'duration_minutes' => $this->partForm['duration_minutes'],
                'test_count' => $this->partForm['test_count'],
                'part_type' => $this->partForm['part_type'],
                'lesson_type' => $lessonType,
                'grade' => $gradeValue,
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
        $student = Student::with(['user.personalInformation', 'advisor', 'supporter'])->find($this->studentId);
        $weeklyProgram = $this->weeklyProgramId
            ? WeeklyProgram::with('parts')->find($this->weeklyProgramId)
            : null;
        // دوره‌های تحصیلی
        $educationLevels = EducationLevel::active()->ordered()->get();
        // محاسبه روزهای هفته با نام روز صحیح فارسی
        $weekDays = [];
        $jalaliDayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
        $startDate = $this->start_date
            ? Carbon::parse($this->start_date)
            : Carbon::tomorrow();
        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dayParts = $weeklyProgram
                ? $weeklyProgram->parts()->where('day_of_week', $i)->orderBy('part_order')->get()
                : collect();

            // محاسبه روز هفته واقعی از تاریخ
            $jalaliDate = jdate($date);
            $dayOfWeek = $jalaliDate->getDayOfWeek(); // 0 = شنبه، 6 = جمعه
            $dayName = $jalaliDayNames[$dayOfWeek];
            $weekDays[] = [
                'index' => $i,
                'name' => $dayName,
                'date' => $date,
                'jalali_date' => $jalaliDate->format('Y/m/d'),
                'parts' => $dayParts,
                'total_hours' => round($dayParts->sum('duration_minutes') / 60, 1),
                'total_tests' => $dayParts->sum('test_count') ?? 0,
            ];
        }
        $preSessions = AdvisingPreSession::where('student_id', $this->studentId)
            ->with(['advisingSession', 'exams', 'assignments', 'qas', 'miscellaneous'])
            ->latest()
            ->get();
        // نام مشاور و پشتیبان از دیتابیس student
        $advisorName = $student->advisor?->name ?? '-';
        $supporterName = $student->supporter?->name ?? '-';
        return view('livewire.admin.student.consultation.weekly-program-upload', [
            'student' => $student,
            'educationLevels' => $educationLevels,
            'weeklyProgram' => $weeklyProgram,
            'weekDays' => $weekDays,
            'preSessions' => $preSessions,
            'advisorName' => $advisorName,
            'supporterName' => $supporterName,
        ])->layout('layouts.admin.app');
    }
}
