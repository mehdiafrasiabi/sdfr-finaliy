<?php

namespace App\Livewire\Admin\Student\Consultation;

use App\Models\Student;
use App\Models\AdvisingSession;
use App\Models\WeeklyProgram;
use App\Models\ProgramPart;
use App\Models\Lesson;
use App\Models\AdvisingPreSession;
use App\Models\WeeklyProgramRestDay;
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

    // Rest day confirmation
    public bool $showRestDayConfirmModal = false;
    public ?int $restDayToToggle = null;
    public int $partsCountForRestDay = 0;
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
            $this->loadExistingParts();
        } else {
            // تاریخ شروع از تاریخ جلسه مشاوره گرفته میشه - نه فردا
            if ($session && $session->activation_date) {
                $this->start_date = Carbon::parse($session->activation_date)->format('Y-m-d');
            } else {
                $this->start_date = Carbon::tomorrow()->format('Y-m-d');
            }
        }

        // اطمینان از وجود آرایه برای هر ۸ روز
        for ($i = 0; $i < 8; $i++) {
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

        for ($i = 0; $i < 8; $i++) {
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
        $this->dispatch('modal-opened');
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
            'education_level_id' => $part->education_level_id,
            'cc_grade_id' => $part->cc_grade_id,
            'cc_field_id' => $part->cc_field_id,
            'cc_subject_id' => $part->cc_subject_id,
            'cc_chapter_id' => $part->cc_chapter_id,
            'cc_topic_id' => $part->cc_topic_id,
            'lesson_id' => $part->lesson_id,
            'lesson_name' => $part->lesson_name,
            'description' => $part->description,
            'duration_minutes' => $part->duration_minutes,
            'test_count' => $part->test_count,
            'part_type' => $part->part_type,
            'lesson_type' => $part->lesson_type,
            'grade' => $part->grade,
        ];

        // بارگذاری لیست‌ها برای ویرایش
        if ($part->education_level_id) {
            $this->grades = CcGrade::where('education_level_id', $part->education_level_id)
                ->where('is_active', true)
                ->with('field')
                ->orderBy('order')
                ->get();
        }

        $this->fields = CcField::active()->ordered()->get();

        if ($part->cc_grade_id) {
            $this->subjects = CcSubject::where('cc_grade_id', $part->cc_grade_id)
                ->when($part->cc_field_id, fn($q) => $q->where('cc_field_id', $part->cc_field_id))
                ->when(!$part->cc_field_id, fn($q) => $q->whereNull('cc_field_id'))
                ->orderBy('order')
                ->get();
        }

        if ($part->cc_subject_id) {
            $this->chapters = CcChapter::where('cc_subject_id', $part->cc_subject_id)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();
        }

        if ($part->cc_chapter_id) {
            $this->topics = CcTopic::where('cc_chapter_id', $part->cc_chapter_id)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();
        }

        $this->showPartModal = true;
        $this->dispatch('modal-opened');
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
                ->with('field')
                ->orderBy('order')
                ->get();
        } else {
            $this->grades = [];
        }
        $this->fields = CcField::active()->ordered()->get();

        $this->dispatchSelectUpdates(['grades', 'fields', 'subjects', 'chapters', 'topics']);
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
            $fieldId = $this->partForm['cc_field_id'] ?: null;
            $this->subjects = CcSubject::where('cc_grade_id', $value)
                ->when($fieldId, fn($q) => $q->where('cc_field_id', $fieldId))
                ->when(!$fieldId, fn($q) => $q->whereNull('cc_field_id'))
                ->orderBy('order')
                ->get();
        } else {
            $this->subjects = [];
        }

        $this->dispatchSelectUpdates(['subjects', 'chapters', 'topics']);
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

        $this->dispatchSelectUpdates(['subjects', 'chapters', 'topics']);
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

        $this->dispatchSelectUpdates(['chapters', 'topics']);
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

        $this->dispatchSelectUpdates(['topics']);
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

    /**
     * Dispatch select2 update events with formatted options data
     */
    protected function dispatchSelectUpdates(array $keys): void
    {
        $mapping = [
            'grades' => [
                'id' => 'grade-select',
                'items' => $this->grades,
                'selected' => $this->partForm['cc_grade_id'],
                'emptyText' => 'ابتدا دوره را انتخاب کنید',
                'format' => function ($item) {
                    $text = $item->name ?? $item['name'];
                    $field = is_object($item) ? $item->field : ($item['field'] ?? null);
                    if ($field) {
                        $fieldName = is_object($field) ? $field->name : ($field['name'] ?? '');
                        $text .= " ({$fieldName})";
                    }
                    return $text;
                },
            ],
            'fields' => [
                'id' => 'field-select',
                'items' => $this->fields,
                'selected' => $this->partForm['cc_field_id'],
                'emptyText' => 'بدون رشته',
                'format' => fn($item) => is_object($item) ? $item->name : ($item['name'] ?? ''),
            ],
            'subjects' => [
                'id' => 'subject-select',
                'items' => $this->subjects,
                'selected' => $this->partForm['cc_subject_id'],
                'emptyText' => 'ابتدا پایه را انتخاب کنید',
                'format' => function ($item) {
                    $name = is_object($item) ? $item->name : ($item['name'] ?? '');
                    $type = is_object($item) ? $item->type : ($item['type'] ?? 'specialized');
                    $typeLabel = $type === 'general' ? 'عمومی' : 'تخصصی';
                    return "{$name} ({$typeLabel})";
                },
            ],
            'chapters' => [
                'id' => 'chapter-select',
                'items' => $this->chapters,
                'selected' => $this->partForm['cc_chapter_id'],
                'emptyText' => 'ابتدا درس را انتخاب کنید',
                'format' => fn($item) => is_object($item) ? $item->name : ($item['name'] ?? ''),
            ],
            'topics' => [
                'id' => 'topic-select',
                'items' => $this->topics,
                'selected' => $this->partForm['cc_topic_id'],
                'emptyText' => 'ابتدا فصل را انتخاب کنید',
                'format' => fn($item) => is_object($item) ? $item->name : ($item['name'] ?? ''),
            ],
        ];

        foreach ($keys as $key) {
            if (!isset($mapping[$key])) continue;

            $config = $mapping[$key];
            $items = $config['items'];
            $options = [];

            // First empty option
            $placeholder = count($items) > 0 ? 'انتخاب کنید' : $config['emptyText'];
            $options[] = ['value' => '', 'text' => $placeholder];

            foreach ($items as $item) {
                $id = is_object($item) ? $item->id : ($item['id'] ?? '');
                $text = ($config['format'])($item);
                $options[] = ['value' => $id, 'text' => $text];
            }

            $this->dispatch('select2-update', [
                'id' => $config['id'],
                'options' => $options,
                'selected' => $config['selected'] ?? '',
                'disabled' => count($items) === 0,
            ]);
        }
    }

    public function closePartModal(): void
    {
        $this->showPartModal = false;
        $this->resetPartForm();
        $this->dispatch('modal-closed');
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
            ? (string)$this->partForm['grade']
            : null;

        // محاسبه grade_label
        $grade = CcGrade::find($this->partForm['cc_grade_id']);
        $gradeLabel = $grade ? $grade->name : null;

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
                'education_level_id' => $this->partForm['education_level_id'] ?: null,
                'cc_grade_id' => $this->partForm['cc_grade_id'] ?: null,
                'cc_field_id' => $this->partForm['cc_field_id'] ?: null,
                'cc_subject_id' => $this->partForm['cc_subject_id'] ?: null,
                'cc_chapter_id' => $this->partForm['cc_chapter_id'] ?: null,
                'cc_topic_id' => $this->partForm['cc_topic_id'] ?: null,
                'grade_label' => $gradeLabel,
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

            // چک ترتیب پلن‌ها - نباید جای خالی قبل از پلن فعلی وجود داشته باشد
            $existingOrders = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
                ->where('day_of_week', $this->selectedDay)
                ->pluck('part_order')
                ->sort()
                ->values()
                ->toArray();
            for ($i = 1; $i <= $existingCount; $i++) {
                if (!in_array($i, $existingOrders)) {
                    $this->dispatch('warning', "ابتدا باید پلن {$i} را پر کنید. نمی‌توانید با پلن خالی، پلن بعدی را پر کنید.");
                    return;
                }
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
                'education_level_id' => $this->partForm['education_level_id'] ?: null,
                'cc_grade_id' => $this->partForm['cc_grade_id'] ?: null,
                'cc_field_id' => $this->partForm['cc_field_id'] ?: null,
                'cc_subject_id' => $this->partForm['cc_subject_id'] ?: null,
                'cc_chapter_id' => $this->partForm['cc_chapter_id'] ?: null,
                'cc_topic_id' => $this->partForm['cc_topic_id'] ?: null,
                'grade_label' => $gradeLabel,
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
        $endDate = $startDate->copy()->addDays(7); // 8 days total

        if ($this->weeklyProgramId) {
            $program = WeeklyProgram::find($this->weeklyProgramId);

            if (!$program) {
                return;
            }

            $program->update([
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);
        } else {
            $program = WeeklyProgram::create([
                'student_id' => $this->studentId,
                'advisor_id' => auth()->id(),
                'advising_session_id' => $this->sessionId,
                'start_date' => $startDate,
                'end_date' => $endDate,
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

    /**
     * Toggle rest day - show confirmation if parts exist
     */
    public function toggleRestDay(int $dayIndex): void
    {
        if (!$this->weeklyProgramId) {
            $this->saveProgram();
        }

        $weeklyProgram = WeeklyProgram::find($this->weeklyProgramId);

        if (!$weeklyProgram) {
            return;
        }

        // Check if already a rest day
        $isCurrentlyRestDay = $weeklyProgram->isRestDay($dayIndex);

        if ($isCurrentlyRestDay) {
            // Remove rest day
            WeeklyProgramRestDay::where('weekly_program_id', $this->weeklyProgramId)
                ->where('day_index', $dayIndex)
                ->delete();

            $this->dispatch('success', 'روز استراحت برداشته شد.');

            return;
        }

        // Check if parts exist for this day
        $partsCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
            ->where('day_of_week', $dayIndex)
            ->count();

        if ($partsCount > 0) {
            // Show confirmation modal
            $this->restDayToToggle = $dayIndex;
            $this->partsCountForRestDay = $partsCount;
            $this->showRestDayConfirmModal = true;
        } else {
            // No parts, directly add rest day
            $this->restDayToToggle = $dayIndex;
            $this->confirmRestDay();
        }
    }

    /**
     * Confirm setting day as rest day (delete parts and set rest)
     */
    public function confirmRestDay(): void
    {
        $dayIndex = $this->restDayToToggle ?? $this->selectedDay;
        if (!$this->weeklyProgramId) {
            $this->saveProgram();
        }

        // Delete all parts for this day
        ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
            ->where('day_of_week', $dayIndex)
            ->delete();

        // Add rest day
        WeeklyProgramRestDay::updateOrCreate([
            'weekly_program_id' => $this->weeklyProgramId,
            'day_index' => $dayIndex,
        ]);

        $this->loadExistingParts();
        $this->closeRestDayConfirmModal();
        $this->dispatch('success', 'روز استراحت با موفقیت ثبت شد.');
    }

    /**
     * Close rest day confirmation modal
     */
    public function closeRestDayConfirmModal(): void
    {
        $this->showRestDayConfirmModal = false;
        $this->restDayToToggle = null;
        $this->partsCountForRestDay = 0;
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

        for ($i = 0; $i < 8; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dayParts = $weeklyProgram
                ? $weeklyProgram->parts()->where('day_of_week', $i)->orderBy('part_order')->get()
                : collect();

            // محاسبه روز هفته واقعی از تاریخ
            $jalaliDate = jdate($date);
            $dayOfWeek = $jalaliDate->getDayOfWeek(); // 0 = شنبه، 6 = جمعه
            $dayName = $jalaliDayNames[$dayOfWeek];

            // Check if this day is a rest day
            $isRestDay = $weeklyProgram ? $weeklyProgram->isRestDay($i) : false;

            $weekDays[] = [
                'index' => $i,
                'name' => $dayName,
                'date' => $date,
                'jalali_date' => $jalaliDate->format('Y/m/d'),
                'parts' => $dayParts,
                'total_hours' => round($dayParts->sum('duration_minutes') / 60, 1),
                'total_tests' => $dayParts->sum('test_count') ?? 0,
                'is_rest_day' => $isRestDay,
            ];
        }

        $preSessions = AdvisingPreSession::where('student_id', $this->studentId)
            ->when($this->sessionId, fn($q) => $q->where('advising_session_id', $this->sessionId))
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
