<?php

namespace App\Livewire\Admin\Student\Consultation;

use App\Models\Student;
use App\Models\AdvisingSession;
use App\Models\WeeklyProgram;
use App\Models\ProgramPart;
use App\Models\Lesson;
use App\Models\AdvisingPreSession;
use App\Models\WeeklyProgramRestDay;
use App\Models\ClassSchedule;
use App\Models\ClassSchedulePart;
use App\Models\Notification;
use App\Models\NotificationRecipient;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EducationLevel;
use App\Models\CcGrade;
use App\Models\CcField;
use App\Models\CcSubject;
use App\Models\CcChapter;
use App\Models\CcTopic;
use App\Models\WeeklyProgramExamDay;
use Morilog\Jalali\Jalalian;
use App\Models\ClassificationProject;
use App\Models\StudentClassification;
use App\Models\ProgramPartSource;
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
        'program_part_source_id' => null,
        'lesson_type' => 'specialized',
        'grade' => '',
    ];
    public $partSources = [];
    public $grades = [];
    public $fields = [];
    public $subjects = [];
    public $chapters = [];
    public $topics = [];
    // Global search
    public string $globalSearch = '';
    public array $globalSearchResults = [];
    // Class Schedule (برنامه کلاسی)
    public bool $showClassScheduleModal = false;
    public bool $showNoScheduleModal = false;
    public bool $showDailyReadingModal = false;
    public ?int $dailyReadingPartId = null;
    public ?string $dailyReadingType = null; // 'daily' or 'pre'
    public ?int $dailyReadingSubjectId = null;
    public string $dailyReadingDescription = '';
    public int $dailyReadingDuration = 20;
    public ?int $editingDailyReadingPartId = null;


    // Part A: Comprehensive Exam Day (آزمون جامع)
    public bool $showExamDayConfirmModal = false;
    public ?int $examDayToToggle = null;
    public int $partsCountForExamDay = 0;
    public bool $showExamPartModal = false;
    public ?int $editingExamPartId = null;
    public array $examPartForm = [
        'exam_name' => '',
        'duration_minutes' => 60,
        'description' => '',
    ];

    // Part D: Pre-session distribution modals
    public bool $showDistributeHomeworkModal = false;
    public bool $showDistributeExamModal = false;
    public bool $showDistributeQaModal = false;
    public array $distributionPreview = [];
    public string $distributionType = ''; // 'homework', 'exam', 'qa'
    // D1: Weekly reading auto-fill
    public bool $showWeeklyReadingsPreview = false;
    public array $weeklyReadingsPreview = [];

    // D2: Class exam day selection
    public bool $showExamDaySelectModal = false;
    public array $examDaySelectData = [];
    public ?int $examDaySelectTarget = null;
    // Classification Modal (طبقه‌بندی)
    public bool $showClassificationModal = false;
    public array $classificationTopics = [];
    public ?string $classificationProjectName = null;

    // Zero-time warning modal
    public bool $showZeroTimeWarningModal = false;
    public int $zeroTimePartsCount = 0;
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
                ->when($part->cc_field_id, fn($q) => $q->where(function ($q2) use ($part) {
                    $q2->where('cc_field_id', $part->cc_field_id)->orWhereNull('cc_field_id');
                }))
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
        $this->globalSearch = '';
        $this->globalSearchResults = [];
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
                ->when($fieldId, fn($q) => $q->where(function ($q2) use ($fieldId) {
                    $q2->where('cc_field_id', $fieldId)->orWhereNull('cc_field_id');
                }))
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
                ->when($value, fn($q) => $q->where(function ($q2) use ($value) {
                    $q2->where('cc_field_id', $value)->orWhereNull('cc_field_id');
                }))
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
                'format' => fn($item) => is_object($item) ? $item->name : ($item['name'] ?? ''),
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

    /**
     * Get the student's field filter for search queries
     */
    protected function getStudentFieldFilter(): ?int
    {
        $student = Student::with('user.personalInformation')->find($this->studentId);
        if (!$student?->user?->personalInformation) return null;

        $field = $student->user->personalInformation->field;
        if (!$field) return null;

        $ccField = CcField::where('slug', CcField::mapFromPersonalInfo($field))
            ->where('is_active', true)
            ->first();

        return $ccField?->id;
    }
    public function updatedGlobalSearch($value): void
    {
        if (mb_strlen($value) < 2) {
            $this->globalSearchResults = [];
            return;
        }

        $results = [];
        $studentFieldId = $this->getStudentFieldFilter();

        // Search topics - filtered by student's field
        $topics = CcTopic::where('is_active', true)
            ->where('name', 'like', "%{$value}%")
            ->with(['chapter.subject.grade.educationLevel', 'chapter.subject.field'])
            ->whereHas('chapter.subject', function ($q) use ($studentFieldId) {
                if ($studentFieldId) {
                    $q->where(function ($q2) use ($studentFieldId) {
                        $q2->where('cc_field_id', $studentFieldId)->orWhereNull('cc_field_id');
                    });
                }
            })
            ->limit(10)
            ->get();

        foreach ($topics as $topic) {
            $chapter = $topic->chapter;
            if (!$chapter) continue;
            $subject = $chapter->subject;
            if (!$subject) continue;
            $grade = $subject->grade;
            if (!$grade) continue;
            $educationLevel = $grade->educationLevel;
            if (!$educationLevel) continue;

            $results[] = [
                'type' => 'topic',
                'topic_id' => $topic->id,
                'chapter_id' => $chapter->id,
                'subject_id' => $subject->id,
                'grade_id' => $grade->id,
                'field_id' => $subject->cc_field_id,
                'education_level_id' => $educationLevel->id,
                'label' => $educationLevel->name . ' / ' . $grade->name . ' / ' . $subject->name . ' / ' . $chapter->name . ' / ' . $topic->name,
            ];
        }

        // Search chapters - filtered by student's field
        $chapters = CcChapter::where('is_active', true)
            ->where('name', 'like', "%{$value}%")
            ->with(['subject.grade.educationLevel', 'subject.field'])
            ->whereHas('subject', function ($q) use ($studentFieldId) {
                if ($studentFieldId) {
                    $q->where(function ($q2) use ($studentFieldId) {
                        $q2->where('cc_field_id', $studentFieldId)->orWhereNull('cc_field_id');
                    });
                }
            })
            ->limit(10)
            ->get();

        foreach ($chapters as $chapter) {
            $subject = $chapter->subject;
            if (!$subject) continue;
            $grade = $subject->grade;
            if (!$grade) continue;
            $educationLevel = $grade->educationLevel;
            if (!$educationLevel) continue;

            $results[] = [
                'type' => 'chapter',
                'topic_id' => null,
                'chapter_id' => $chapter->id,
                'subject_id' => $subject->id,
                'grade_id' => $grade->id,
                'field_id' => $subject->cc_field_id,
                'education_level_id' => $educationLevel->id,
                'label' => $educationLevel->name . ' / ' . $grade->name . ' / ' . $subject->name . ' / ' . $chapter->name,
            ];
        }

        // Search subjects - filtered by student's field
        $subjectQuery = CcSubject::where('name', 'like', "%{$value}%")
            ->with(['grade.educationLevel', 'field']);

        if ($studentFieldId) {
            $subjectQuery->where(function ($q) use ($studentFieldId) {
                $q->where('cc_field_id', $studentFieldId)->orWhereNull('cc_field_id');
            });
        }

        $subjects = $subjectQuery->limit(10)->get();

        foreach ($subjects as $subject) {
            $grade = $subject->grade;
            if (!$grade) continue;
            $educationLevel = $grade->educationLevel;
            if (!$educationLevel) continue;

            $results[] = [
                'type' => 'subject',
                'topic_id' => null,
                'chapter_id' => null,
                'subject_id' => $subject->id,
                'grade_id' => $grade->id,
                'field_id' => $subject->cc_field_id,
                'education_level_id' => $educationLevel->id,
                'label' => $educationLevel->name . ' / ' . $grade->name . ' / ' . $subject->name,
            ];
        }

        $this->globalSearchResults = array_slice($results, 0, 15);
    }

    public function selectGlobalResult(int $index): void
    {
        if (!isset($this->globalSearchResults[$index])) return;

        $result = $this->globalSearchResults[$index];

        // Fill form fields
        $this->partForm['education_level_id'] = $result['education_level_id'];
        $this->partForm['cc_grade_id'] = $result['grade_id'];
        $this->partForm['cc_field_id'] = $result['field_id'] ?? '';
        $this->partForm['cc_subject_id'] = $result['subject_id'];
        $this->partForm['cc_chapter_id'] = $result['chapter_id'] ?? '';
        $this->partForm['cc_topic_id'] = $result['topic_id'] ?? '';

        // Load cascading data
        $this->grades = CcGrade::where('education_level_id', $result['education_level_id'])
            ->where('is_active', true)
            ->with('field')
            ->orderBy('order')
            ->get();

        $this->fields = CcField::active()->ordered()->get();

        $fieldId = $result['field_id'] ?: null;
        $this->subjects = CcSubject::where('cc_grade_id', $result['grade_id'])
            ->when($fieldId, fn($q) => $q->where(function ($q2) use ($fieldId) {
                $q2->where('cc_field_id', $fieldId)->orWhereNull('cc_field_id');
            }))
            ->orderBy('order')
            ->get();

        if ($result['subject_id']) {
            $subject = CcSubject::find($result['subject_id']);
            if ($subject) {
                $this->partForm['lesson_name'] = $subject->name;
                $this->partForm['lesson_type'] = $subject->type;
            }
            $this->chapters = CcChapter::where('cc_subject_id', $result['subject_id'])
                ->where('is_active', true)
                ->orderBy('order')
                ->get();
        }

        if ($result['chapter_id']) {
            $this->topics = CcTopic::where('cc_chapter_id', $result['chapter_id'])
                ->where('is_active', true)
                ->orderBy('order')
                ->get();
        }

        // Set grade number
        $grade = CcGrade::find($result['grade_id']);
        if ($grade) {
            $this->partForm['grade'] = $grade->grade_number;
        }

        // Build description
        $descParts = [];
        if ($result['subject_id']) {
            $subject = CcSubject::find($result['subject_id']);
            if ($subject) $descParts[] = $subject->name;
        }
        if ($result['chapter_id']) {
            $chapter = CcChapter::find($result['chapter_id']);
            if ($chapter) $descParts[] = $chapter->name;
        }
        if ($result['topic_id']) {
            $topic = CcTopic::find($result['topic_id']);
            if ($topic) $descParts[] = $topic->name;
        }
        if (!empty($descParts)) {
            $this->partForm['description'] = implode(' » ', $descParts);
        }

        // Clear search
        $this->globalSearch = '';
        $this->globalSearchResults = [];

        // Get education levels for education-level-select update
        $educationLevels = EducationLevel::active()->ordered()->get();
        $eduOptions = [['value' => '', 'text' => 'انتخاب کنید']];
        foreach ($educationLevels as $level) {
            $eduOptions[] = ['value' => $level->id, 'text' => $level->name];
        }
        $this->dispatch('select2-update', [
            'id' => 'education-level-select',
            'options' => $eduOptions,
            'selected' => $result['education_level_id'],
            'disabled' => false,
        ]);

        // Dispatch updates for all other selects
        $this->dispatchSelectUpdates(['grades', 'fields', 'subjects', 'chapters', 'topics']);
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
            'partForm.part_type' => 'required|in:test,descriptive,video,topic_exam',
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
                'source_type' => ProgramPart::SOURCE_NORMAL,
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
            if ($existingCount >= 20) {
                $this->dispatch('warning', 'حداکثر ۲۰ پارت برای هر روز مجاز است.');
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
        // Check for 0-time parts before confirming
        if ($this->weeklyProgramId) {
            $totalParts = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)->count();

            if ($totalParts === 0) {
                $this->dispatch('warning', 'برنامه هیچ پارتی ندارد. ابتدا پارت اضافه کنید.');
                return;
            }

            $zeroCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
                ->where('duration_minutes', 0)
                ->count();

            if ($zeroCount > 0) {
                $this->zeroTimePartsCount = $zeroCount;
                $this->showZeroTimeWarningModal = true;
                return;
            }
            // All parts have time - auto-mark session result as held
            $this->markSessionAsHeld();
        }

        $this->dispatch('success', 'برنامه هفتگی با موفقیت ذخیره شد.');
    }

    public function forceFinalSave(): void
    {
        $this->showZeroTimeWarningModal = false;
        $this->dispatch('success', 'برنامه هفتگی ذخیره شد (بدون تایید نهایی - پارت‌های بدون تایم وجود دارد).');
    }

    /**
     * Mark the advising session result as "held" when program is complete
     */
    protected function markSessionAsHeld(): void
    {
        if (!$this->sessionId) return;

        $session = AdvisingSession::find($this->sessionId);
        if (!$session) return;

        $session->update([
            'result_status' => AdvisingSession::RESULT_HELD,
            'status' => AdvisingSession::STATUS_COMPLETED,
        ]);
    }
    public function closeZeroTimeWarningModal(): void
    {
        $this->showZeroTimeWarningModal = false;
    }

    public function openClassificationModal(): void
    {
        $student = Student::find($this->studentId);
        $userId = $student?->user_id;

        // Find last active project, fallback to most recent
        $project = ClassificationProject::active()->latest('start_at')->first()
            ?? ClassificationProject::orderBy('end_at', 'desc')->first();

        $this->classificationProjectName = $project?->name ?? null;
        $this->classificationTopics = [];

        if ($project && $userId) {
            $classifications = StudentClassification::where('user_id', $userId)
                ->where('classification_project_id', $project->id)
                ->with(['topic.chapter.subject'])
                ->get();

            $this->classificationTopics = $classifications->map(function ($c) {
                return [
                    'id'           => $c->id,
                    'topic_id'     => $c->cc_topic_id,
                    'topic_name'   => $c->topic?->name ?? 'نامشخص',
                    'chapter_name' => $c->topic?->chapter?->name ?? '',
                    'subject_name' => $c->topic?->chapter?->subject?->name ?? '',
                    'rating'       => $c->rating,
                    'rating_label' => $c->ratingLabel,
                    'rating_color' => $c->ratingColor,
                ];
            })->toArray();
        }

        $this->showClassificationModal = true;
    }

    public function closeClassificationModal(): void
    {
        $this->showClassificationModal = false;
    }
    // Classification inline add properties
    public ?int $classificationSelectedTopicId = null;
    public array $classificationAddForm = [
        'day_index' => null,
        'duration_hours' => 1,
        'duration_minutes' => 0,
    ];
    public bool $showClassificationAddForm = false;

    /**
     * Show inline add form for classification topic
     */
    public function showClassificationInlineAdd(int $topicId): void
    {
        $this->classificationSelectedTopicId = $topicId;
        $this->classificationAddForm = [
            'day_index' => null,
            'duration_hours' => 1,
            'duration_minutes' => 0,
        ];
        $this->showClassificationAddForm = true;
    }

    /**
     * Hide inline add form
     */
    public function hideClassificationInlineAdd(): void
    {
        $this->showClassificationAddForm = false;
        $this->classificationSelectedTopicId = null;
    }

    /**
     * Add classification topic directly to program with selected day/time
     */
    public function addClassificationToProgram(): void
    {
        if (!$this->classificationSelectedTopicId) return;

        $dayIndex = $this->classificationAddForm['day_index'];
        if ($dayIndex === null || $dayIndex === '') {
            $this->dispatch('warning', 'لطفاً یک روز انتخاب کنید.');
            return;
        }

        $hours = (int) ($this->classificationAddForm['duration_hours'] ?? 0);
        $minutes = (int) ($this->classificationAddForm['duration_minutes'] ?? 0);
        $totalMinutes = ($hours * 60) + $minutes;

        if ($totalMinutes < 1) {
            $this->dispatch('warning', 'مدت زمان باید حداقل ۱ دقیقه باشد.');
            return;
        }

        if (!$this->weeklyProgramId) {
            $this->saveProgram();
        }

        $topic = CcTopic::with(['chapter.subject.grade.educationLevel'])->find($this->classificationSelectedTopicId);
        if (!$topic) return;

        $chapter = $topic->chapter;
        $subject = $chapter?->subject;
        $grade   = $subject?->grade;
        $educationLevel = $grade?->educationLevel;

        $startDate = Carbon::parse($this->start_date);
        $partDate = $startDate->copy()->addDays((int) $dayIndex);

        $existingCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
            ->where('day_of_week', (int) $dayIndex)
            ->count();

        if ($existingCount >= 20) {
            $this->dispatch('warning', 'حداکثر ۲۰ پارت برای هر روز مجاز است.');
            return;
        }

        $path = collect([$subject?->name, $chapter?->name, $topic->name])->filter()->join(' > ');

        ProgramPart::create([
            'weekly_program_id' => $this->weeklyProgramId,
            'lesson_name' => $subject?->name ?? $topic->name,
            'part_date' => $partDate,
            'day_of_week' => (int) $dayIndex,
            'part_order' => $existingCount + 1,
            'description' => $path,
            'duration_minutes' => $totalMinutes,
            'test_count' => null,
            'part_type' => 'descriptive',
            'source_type' => ProgramPart::SOURCE_CLASSIFICATION,
            'lesson_type' => $subject?->type ?? 'specialized',
            'grade' => $grade?->grade_number,
            'education_level_id' => $educationLevel?->id,
            'cc_grade_id' => $grade?->id,
            'cc_field_id' => $subject?->cc_field_id,
            'cc_subject_id' => $subject?->id,
            'cc_chapter_id' => $chapter?->id,
            'cc_topic_id' => $topic->id,
        ]);

        $this->loadExistingParts();
        $this->hideClassificationInlineAdd();
        $this->dispatch('success', 'مبحث با موفقیت به برنامه اضافه شد.');
    }
    public function reorderParts(array $partIds, int $dayIndex): void
    {
        if (!$this->weeklyProgramId) return;

        foreach ($partIds as $index => $partId) {
            ProgramPart::where('id', $partId)
                ->where('weekly_program_id', $this->weeklyProgramId)
                ->where('day_of_week', $dayIndex)
                ->update(['part_order' => $index + 1]);
        }
    }
    /**
     * Move a part from one day to another day (cross-day drag-drop)
     */
    public function movePartToDay(int $partId, int $targetDayIndex): void
    {
        if (!$this->weeklyProgramId) return;

        $part = ProgramPart::where('id', $partId)
            ->where('weekly_program_id', $this->weeklyProgramId)
            ->first();

        if (!$part) return;
        if ($part->day_of_week === $targetDayIndex) return;

        $sourceDayIndex = $part->day_of_week;
        $startDate = Carbon::parse($this->start_date);

        // Calculate new part_order for target day
        $targetCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
            ->where('day_of_week', $targetDayIndex)
            ->count();

        // Move part to target day
        $part->update([
            'day_of_week' => $targetDayIndex,
            'part_date' => $startDate->copy()->addDays($targetDayIndex),
            'part_order' => $targetCount + 1,
        ]);

        // Reorder source day parts
        $sourceParts = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
            ->where('day_of_week', $sourceDayIndex)
            ->orderBy('part_order')
            ->get();

        foreach ($sourceParts as $idx => $sp) {
            $sp->update(['part_order' => $idx + 1]);
        }

        $this->loadExistingParts();
        $this->dispatch('success', 'پارت با موفقیت جابه‌جا شد.');
    }

    /**
     * Swap two parts between days (cross-day drag onto another part)
     */
    public function swapParts(int $partId1, int $partId2): void
    {
        if (!$this->weeklyProgramId) return;

        $part1 = ProgramPart::where('id', $partId1)
            ->where('weekly_program_id', $this->weeklyProgramId)
            ->first();

        $part2 = ProgramPart::where('id', $partId2)
            ->where('weekly_program_id', $this->weeklyProgramId)
            ->first();

        if (!$part1 || !$part2) return;

        $startDate = Carbon::parse($this->start_date);

        // Swap day_of_week, part_date, and part_order
        $tempDay = $part1->day_of_week;
        $tempOrder = $part1->part_order;
        $tempDate = $part1->part_date;

        $part1->update([
            'day_of_week' => $part2->day_of_week,
            'part_order' => $part2->part_order,
            'part_date' => $part2->part_date,
        ]);

        $part2->update([
            'day_of_week' => $tempDay,
            'part_order' => $tempOrder,
            'part_date' => $tempDate,
        ]);

        $this->loadExistingParts();
        $this->dispatch('success', 'پارت‌ها با موفقیت جابه‌جا شدند.');
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
    /**
     * باز کردن مودال مشاهده برنامه کلاسی
     */
    public function openClassScheduleModal(): void
    {
        $student = Student::find($this->studentId);
        if (!$student) return;

        $schedule = ClassSchedule::where('student_id', $student->id)
            ->where('is_finalized', true)
            ->latest()
            ->first();

        if ($schedule) {
            $this->showClassScheduleModal = true;
        } else {
            $this->showNoScheduleModal = true;
        }
    }

    public function closeClassScheduleModal(): void
    {
        $this->showClassScheduleModal = false;
    }

    public function closeNoScheduleModal(): void
    {
        $this->showNoScheduleModal = false;
    }

    /**
     * ارسال نوتیفیکیشن برای آپلود برنامه کلاسی
     */
    public function sendScheduleReminder(): void
    {
        $student = Student::with('user')->find($this->studentId);
        if (!$student || !$student->user) return;

        $notification = Notification::create([
            'title' => 'ارسال برنامه کلاسی',
            'body' => 'دانش‌آموز عزیز، لطفاً هرچه سریع‌تر برنامه کلاسی خود را از بخش اتاق مشاوره آپلود کنید.',
            'category' => Notification::CATEGORY_ADVISOR,
            'target_type' => Notification::TARGET_SINGLE,
            'admin_id' => auth('admin')->id(),
            'student_id' => $student->id,
            'is_from_manager' => false,
        ]);

        NotificationRecipient::create([
            'notification_id' => $notification->id,
            'user_id' => $student->user_id,
            'is_read' => false,
        ]);

        $this->closeNoScheduleModal();
        $this->dispatch('success', 'نوتیفیکیشن با موفقیت ارسال شد.');
    }

    /**
     * ثبت روزخوانی یا پیش‌خوانی
     */
    public function openDailyReadingModal(string $type, int $subjectId): void
    {
        $this->dailyReadingType = $type;
        $this->dailyReadingSubjectId = $subjectId;
        $this->editingDailyReadingPartId = null;

        $subject = CcSubject::find($subjectId);
        $subjectName = $subject ? $subject->name : '';

        if ($type === 'daily') {
            $this->dailyReadingDescription = 'روزخوانی - 20 دقیقه - ' . $subjectName;
            $this->dailyReadingDuration = 20;
        } else {
            $this->dailyReadingDescription = 'پیش‌خوانی - 15 دقیقه - ' . $subjectName;
            $this->dailyReadingDuration = 15;
        }

        $this->showDailyReadingModal = true;
    }

    public function closeDailyReadingModal(): void
    {
        $this->showDailyReadingModal = false;
        $this->dailyReadingType = null;
        $this->dailyReadingSubjectId = null;
        $this->dailyReadingDescription = '';
        $this->dailyReadingDuration = 20;
        $this->editingDailyReadingPartId = null;
    }

    public function saveDailyReading(): void
    {
        if (!$this->dailyReadingSubjectId) {
            $this->dispatch('warning', 'درس انتخاب نشده است.');
            return;
        }

        // اطمینان از وجود برنامه هفتگی
        if (!$this->weeklyProgramId) {
            $this->saveProgram();
        }

        if (!$this->weeklyProgramId) {
            $this->dispatch('warning', 'ابتدا باید برنامه هفتگی ایجاد شود.');
            return;
        }

        $subject = CcSubject::find($this->dailyReadingSubjectId);
        if (!$subject) return;

        // پیدا کردن روز امروز در برنامه هفتگی
        $startDate = Carbon::parse($this->start_date);
        $today = Carbon::today();
        $todayIndex = null;

        for ($i = 0; $i < 8; $i++) {
            $dayDate = $startDate->copy()->addDays($i);
            if ($dayDate->isSameDay($today)) {
                $todayIndex = $i;
                break;
            }
        }

        // اگر امروز در محدوده برنامه نیست، از روز 0 استفاده کن
        if ($todayIndex === null) {
            $todayIndex = 0;
        }

        $partDate = $startDate->copy()->addDays($todayIndex);

        if ($this->editingDailyReadingPartId) {
            // ویرایش پارت موجود
            $part = ProgramPart::find($this->editingDailyReadingPartId);
            if ($part) {
                $part->update([
                    'lesson_name' => $subject->name,
                    'description' => $this->dailyReadingDescription,
                    'duration_minutes' => $this->dailyReadingDuration,
                    'cc_subject_id' => $subject->id,
                ]);
            }
        } else {
            // محاسبه ترتیب پارت جدید
            $existingCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
                ->where('day_of_week', $todayIndex)
                ->count();

            if ($existingCount >= 20) {
                $this->dispatch('warning', 'حداکثر ۲۰ پارت برای هر روز مجاز است.');
                $this->closeDailyReadingModal();
                return;
            }

            ProgramPart::create([
                'weekly_program_id' => $this->weeklyProgramId,
                'lesson_name' => $subject->name,
                'part_date' => $partDate,
                'day_of_week' => $todayIndex,
                'part_order' => $existingCount + 1,
                'description' => $this->dailyReadingDescription,
                'duration_minutes' => $this->dailyReadingDuration,
                'test_count' => null,
                'part_type' => 'descriptive',
                'source_type' => $this->dailyReadingType === 'daily'
                    ? ProgramPart::SOURCE_DAILY_READING
                    : ProgramPart::SOURCE_PRE_READING,
                'lesson_type' => $subject->type ?? 'specialized',
                'cc_subject_id' => $subject->id,
            ]);
        }

        $this->loadExistingParts();
        $this->closeDailyReadingModal();
        $this->dispatch('success', ($this->dailyReadingType === 'daily' ? 'روزخوانی' : 'پیش‌خوانی') . ' با موفقیت ثبت شد.');
    }

    /**
     * ویرایش پارت روزخوانی/پیش‌خوانی
     */
    public function editDailyReadingPart(int $partId): void
    {
        $part = ProgramPart::find($partId);
        if (!$part) return;

        $this->editingDailyReadingPartId = $partId;
        $this->dailyReadingSubjectId = $part->cc_subject_id;
        $this->dailyReadingDescription = $part->description;
        $this->dailyReadingDuration = $part->duration_minutes;

        if (str_contains($part->description, 'روزخوانی')) {
            $this->dailyReadingType = 'daily';
        } else {
            $this->dailyReadingType = 'pre';
        }

        $this->showDailyReadingModal = true;
    }

    /**
     * حذف پارت روزخوانی/پیش‌خوانی
     */
    public function deleteDailyReadingPart(int $partId): void
    {
        $part = ProgramPart::find($partId);
        if (!$part) return;

        $part->delete();
        $this->loadExistingParts();
        $this->dispatch('success', 'پارت با موفقیت حذف شد.');
    }

    /**
     * دریافت برنامه کلاسی برای نمایش در مودال
     */
    protected function getClassScheduleData(): array
    {
        $student = Student::find($this->studentId);
        if (!$student) return ['schedule' => null, 'days' => [], 'todayParts' => [], 'tomorrowParts' => []];

        $schedule = ClassSchedule::where('student_id', $student->id)
            ->where('is_finalized', true)
            ->with('parts')
            ->latest()
            ->first();

        if (!$schedule) return ['schedule' => null, 'days' => [], 'todayParts' => [], 'tomorrowParts' => []];

        // ساخت آرایه روزها
        $days = [];
        for ($d = 0; $d < 7; $d++) {
            $dayParts = $schedule->parts->where('day_of_week', $d)->sortBy('part_order')->values();
            $days[$d] = [
                'day_of_week' => $d,
                'name' => ClassSchedule::getDayName($d),
                'parts' => $dayParts,
            ];
        }

        // روز امروز (شمسی)
        $todayJalali = jdate(Carbon::today());
        $todayDayOfWeek = $todayJalali->getDayOfWeek(); // 0=شنبه تا 6=جمعه
        $tomorrowDayOfWeek = ($todayDayOfWeek + 1) % 7;

        $todayParts = $schedule->parts->where('day_of_week', $todayDayOfWeek)->sortBy('part_order')->values();
        $tomorrowParts = $schedule->parts->where('day_of_week', $tomorrowDayOfWeek)->sortBy('part_order')->values();

        return [
            'schedule' => $schedule,
            'days' => $days,
            'todayParts' => $todayParts,
            'tomorrowParts' => $tomorrowParts,
            'todayName' => ClassSchedule::getDayName($todayDayOfWeek),
            'tomorrowName' => ClassSchedule::getDayName($tomorrowDayOfWeek),
        ];
    }

    // ==================== Part A: Comprehensive Exam Day ====================

    /**
     * Toggle comprehensive exam day
     */
    public function toggleExamDay(int $dayIndex): void
    {
        if (!$this->weeklyProgramId) {
            $this->saveProgram();
        }

        $weeklyProgram = WeeklyProgram::find($this->weeklyProgramId);
        if (!$weeklyProgram) return;

        $isCurrentlyExamDay = $weeklyProgram->isExamDay($dayIndex);

        if ($isCurrentlyExamDay) {
            // Remove exam day
            WeeklyProgramExamDay::where('weekly_program_id', $this->weeklyProgramId)
                ->where('day_index', $dayIndex)
                ->delete();
            // Remove rest day if it was also set
            WeeklyProgramRestDay::where('weekly_program_id', $this->weeklyProgramId)
                ->where('day_index', $dayIndex)
                ->delete();
            $this->loadExistingParts();
            $this->dispatch('success', 'حالت آزمون جامع برداشته شد.');
            return;
        }

        // Check if parts exist
        $partsCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
            ->where('day_of_week', $dayIndex)
            ->count();

        if ($partsCount > 0) {
            $this->examDayToToggle = $dayIndex;
            $this->partsCountForExamDay = $partsCount;
            $this->showExamDayConfirmModal = true;
        } else {
            $this->examDayToToggle = $dayIndex;
            $this->confirmExamDay();
        }
    }

    /**
     * Confirm setting day as comprehensive exam day
     */
    public function confirmExamDay(): void
    {
        $dayIndex = $this->examDayToToggle;
        if ($dayIndex === null) return;

        if (!$this->weeklyProgramId) {
            $this->saveProgram();
        }

        // Delete all existing parts for this day
        ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
            ->where('day_of_week', $dayIndex)
            ->delete();

        // Remove rest day if it was set
        WeeklyProgramRestDay::where('weekly_program_id', $this->weeklyProgramId)
            ->where('day_index', $dayIndex)
            ->delete();

        // Add exam day
        WeeklyProgramExamDay::updateOrCreate([
            'weekly_program_id' => $this->weeklyProgramId,
            'day_index' => $dayIndex,
        ]);

        $this->loadExistingParts();
        $this->closeExamDayConfirmModal();
        $this->dispatch('success', 'روز آزمون جامع با موفقیت ثبت شد.');
    }

    public function closeExamDayConfirmModal(): void
    {
        $this->showExamDayConfirmModal = false;
        $this->examDayToToggle = null;
        $this->partsCountForExamDay = 0;
    }

    /**
     * Open exam part modal for comprehensive exam day
     */
    public function openExamPartModal(int $dayIndex): void
    {
        $this->selectedDay = $dayIndex;
        $this->editingExamPartId = null;
        $this->examPartForm = [
            'exam_name' => '',
            'duration_minutes' => 60,
            'description' => '',
        ];
        $this->showExamPartModal = true;
    }

    /**
     * Edit an exam part in comprehensive exam mode
     */
    public function editExamPart(int $partId): void
    {
        $part = ProgramPart::find($partId);
        if (!$part) return;

        $this->editingExamPartId = $partId;
        $this->selectedDay = $part->day_of_week;
        $this->examPartForm = [
            'exam_name' => $part->lesson_name,
            'duration_minutes' => $part->duration_minutes,
            'description' => $part->description,
        ];
        $this->showExamPartModal = true;
    }

    public function closeExamPartModal(): void
    {
        $this->showExamPartModal = false;
        $this->editingExamPartId = null;
        $this->examPartForm = [
            'exam_name' => '',
            'duration_minutes' => 60,
            'description' => '',
        ];
    }

    /**
     * Save comprehensive exam part + auto-create analysis part
     */
    public function saveExamPart(): void
    {
        $this->validate([
            'examPartForm.exam_name' => 'required|string|max:255',
            'examPartForm.duration_minutes' => 'required|integer|min:1',
        ], [
            'examPartForm.exam_name.required' => 'نام آزمون الزامی است.',
            'examPartForm.duration_minutes.required' => 'مدت زمان الزامی است.',
            'examPartForm.duration_minutes.min' => 'مدت زمان باید حداقل ۱ دقیقه باشد.',
        ]);

        if (!$this->weeklyProgramId) {
            $this->saveProgram();
        }

        $partDate = Carbon::parse($this->start_date)->addDays($this->selectedDay);

        if ($this->editingExamPartId) {
            // Edit existing exam part
            $part = ProgramPart::find($this->editingExamPartId);
            if (!$part) return;

            $part->update([
                'lesson_name' => $this->examPartForm['exam_name'],
                'duration_minutes' => $this->examPartForm['duration_minutes'],
                'description' => $this->examPartForm['description'],
            ]);

            // Also update the corresponding analysis part if it exists
            $analysisPart = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
                ->where('day_of_week', $this->selectedDay)
                ->where('part_type', 'exam_analysis')
                ->where('part_order', $part->part_order + 1)
                ->first();

            if ($analysisPart) {
                $analysisPart->update([
                    'lesson_name' => 'تحلیل آزمون: ' . $this->examPartForm['exam_name'],
                    'duration_minutes' => $this->examPartForm['duration_minutes'],
                    'description' => 'تحلیل آزمون - ' . $this->examPartForm['description'],
                ]);
            }
        } else {
            // Check max 10 parts
            $existingCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
                ->where('day_of_week', $this->selectedDay)
                ->count();

            if ($existingCount >= 9) { // 9 because we add 2 parts (exam + analysis)
                $this->dispatch('warning', 'فضای کافی برای افزودن آزمون و تحلیل وجود ندارد.');
                return;
            }

            // Create exam part
            ProgramPart::create([
                'weekly_program_id' => $this->weeklyProgramId,
                'lesson_name' => $this->examPartForm['exam_name'],
                'part_date' => $partDate,
                'day_of_week' => $this->selectedDay,
                'part_order' => $existingCount + 1,
                'description' => $this->examPartForm['description'],
                'duration_minutes' => $this->examPartForm['duration_minutes'],
                'test_count' => null,
                'part_type' => 'comprehensive_exam',
                'source_type' => ProgramPart::SOURCE_COMPREHENSIVE_EXAM,
                'lesson_type' => 'specialized',
            ]);

            // Auto-create analysis part
            ProgramPart::create([
                'weekly_program_id' => $this->weeklyProgramId,
                'lesson_name' => 'تحلیل آزمون: ' . $this->examPartForm['exam_name'],
                'part_date' => $partDate,
                'day_of_week' => $this->selectedDay,
                'part_order' => $existingCount + 2,
                'description' => 'تحلیل آزمون - ' . $this->examPartForm['description'],
                'duration_minutes' => $this->examPartForm['duration_minutes'],
                'test_count' => null,
                'part_type' => 'exam_analysis',
                'source_type' => ProgramPart::SOURCE_COMPREHENSIVE_EXAM,
                'lesson_type' => 'specialized',
            ]);
        }

        $this->loadExistingParts();
        $this->closeExamPartModal();
        $this->dispatch('success', 'آزمون و تحلیل آزمون با موفقیت ذخیره شد.');
    }

    /**
     * Delete exam part and its corresponding analysis part
     */
    public function deleteExamPart(int $partId): void
    {
        $part = ProgramPart::find($partId);
        if (!$part) return;

        $dayIndex = $part->day_of_week;
        $partOrder = $part->part_order;

        // If it's an exam, delete the analysis too
        if ($part->part_type === 'comprehensive_exam') {
            ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
                ->where('day_of_week', $dayIndex)
                ->where('part_type', 'exam_analysis')
                ->where('part_order', $partOrder + 1)
                ->delete();
        }

        // If it's an analysis, also delete the exam
        if ($part->part_type === 'exam_analysis') {
            ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
                ->where('day_of_week', $dayIndex)
                ->where('part_type', 'comprehensive_exam')
                ->where('part_order', $partOrder - 1)
                ->delete();
        }

        $part->delete();

        // Reorder remaining parts
        $remainingParts = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
            ->where('day_of_week', $dayIndex)
            ->orderBy('part_order')
            ->get();

        foreach ($remainingParts as $idx => $rPart) {
            $rPart->update(['part_order' => $idx + 1]);
        }

        $this->loadExistingParts();
        $this->dispatch('success', 'آزمون و تحلیل آزمون حذف شد.');
    }

    // ==================== Part D: Pre-session Distribution ====================

    /**
     * Compute homework distribution preview
     */
    public function previewHomeworkDistribution(): void
    {
        if (!$this->weeklyProgramId) {
            $this->saveProgram();
        }

        $preSessions = AdvisingPreSession::where('student_id', $this->studentId)
            ->when($this->sessionId, fn($q) => $q->where('advising_session_id', $this->sessionId))
            ->with('assignments')
            ->latest()
            ->first();

        if (!$preSessions || $preSessions->assignments->isEmpty()) {
            $this->dispatch('warning', 'تکلیفی برای توزیع وجود ندارد.');
            return;
        }

        $startDate = Carbon::parse($this->start_date);
        $weekDates = [];
        $jalaliDayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];

        for ($i = 0; $i < 8; $i++) {
            $date = $startDate->copy()->addDays($i);
            $jalaliDate = jdate($date);
            $dayOfWeek = $jalaliDate->getDayOfWeek();
            $weekDates[$i] = [
                'date' => $date,
                'day_name' => $jalaliDayNames[$dayOfWeek],
                'day_of_week' => $dayOfWeek,
                'jalali_date' => $jalaliDate->format('Y/m/d'),
                'is_friday' => $dayOfWeek === 6,
            ];
        }

        // Find Friday index in the program
        $fridayIndex = null;
        foreach ($weekDates as $idx => $wd) {
            if ($wd['is_friday']) {
                $fridayIndex = $idx;
                break;
            }
        }

        $preview = [];
        foreach ($preSessions->assignments as $assignment) {
            $dueDate = Carbon::parse($assignment->due_date);
            $targetDayIndex = null;

            if ($fridayIndex !== null) {
                $fridayDate = $weekDates[$fridayIndex]['date'];
                if ($dueDate->gte($fridayDate)) {
                    // Deadline is on or after Friday -> place on Friday
                    $targetDayIndex = $fridayIndex;
                } else {
                    // Deadline is before Friday -> place one day before deadline
                    $oneDayBefore = $dueDate->copy()->subDay();
                    for ($i = 0; $i < 8; $i++) {
                        if ($weekDates[$i]['date']->isSameDay($oneDayBefore)) {
                            $targetDayIndex = $i;
                            break;
                        }
                    }
                }
            } else {
                // No Friday in range -> place one day before deadline
                $oneDayBefore = $dueDate->copy()->subDay();
                for ($i = 0; $i < 8; $i++) {
                    if ($weekDates[$i]['date']->isSameDay($oneDayBefore)) {
                        $targetDayIndex = $i;
                        break;
                    }
                }
            }

            // If no exact match found, find closest day before deadline
            if ($targetDayIndex === null) {
                for ($i = 7; $i >= 0; $i--) {
                    if ($weekDates[$i]['date']->lt($dueDate)) {
                        $targetDayIndex = $i;
                        break;
                    }
                }
            }

            if ($targetDayIndex === null) $targetDayIndex = 0;

            for ($p = 0; $p < $assignment->part_count; $p++) {
                $preview[] = [
                    'type' => 'homework',
                    'subject' => $assignment->subject,
                    'cc_subject_id' => $assignment->cc_subject_id,
                    'day_index' => $targetDayIndex,
                    'day_name' => $weekDates[$targetDayIndex]['day_name'],
                    'jalali_date' => $weekDates[$targetDayIndex]['jalali_date'],
                    'duration_minutes' => $assignment->time_per_part,
                    'description' => 'تکلیف: ' . $assignment->subject . ' (تحویل: ' . jdate($dueDate)->format('Y/m/d') . ')',
                ];
            }
        }

        $this->distributionPreview = $preview;
        $this->distributionType = 'homework';
        $this->showDistributeHomeworkModal = true;
    }

    /**
     * Compute exam distribution preview
     */
    public function previewExamDistribution(): void
    {
        if (!$this->weeklyProgramId) {
            $this->saveProgram();
        }

        $preSessions = AdvisingPreSession::where('student_id', $this->studentId)
            ->when($this->sessionId, fn($q) => $q->where('advising_session_id', $this->sessionId))
            ->with('exams')
            ->latest()
            ->first();

        if (!$preSessions || $preSessions->exams->isEmpty()) {
            $this->dispatch('warning', 'امتحانی برای توزیع وجود ندارد.');
            return;
        }

        $startDate = Carbon::parse($this->start_date);
        $weekDates = [];
        $jalaliDayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];

        for ($i = 0; $i < 8; $i++) {
            $date = $startDate->copy()->addDays($i);
            $jalaliDate = jdate($date);
            $dayOfWeek = $jalaliDate->getDayOfWeek();
            $weekDates[$i] = [
                'date' => $date,
                'day_name' => $jalaliDayNames[$dayOfWeek],
                'jalali_date' => $jalaliDate->format('Y/m/d'),
            ];
        }

        $preview = [];
        foreach ($preSessions->exams as $exam) {
            $examDate = Carbon::parse($exam->exam_date);
            $partCount = $exam->part_count;

            // Distribute evenly from start to day before exam
            $availableDays = [];
            for ($i = 0; $i < 8; $i++) {
                if ($weekDates[$i]['date']->lt($examDate)) {
                    $weeklyProgram = WeeklyProgram::find($this->weeklyProgramId);
                    if (!$weeklyProgram || (!$weeklyProgram->isRestDay($i) && !$weeklyProgram->isExamDay($i))) {
                        $availableDays[] = $i;
                    }
                }
            }

            if (empty($availableDays)) {
                $availableDays = [0]; // fallback
            }

            // Distribute parts across available days
            $partsPerDay = [];
            for ($p = 0; $p < $partCount; $p++) {
                $dayIdx = $availableDays[$p % count($availableDays)];
                if (!isset($partsPerDay[$dayIdx])) $partsPerDay[$dayIdx] = 0;
                $partsPerDay[$dayIdx]++;
            }

            foreach ($partsPerDay as $dayIdx => $count) {
                for ($c = 0; $c < $count; $c++) {
                    $chapterInfo = '';
                    if ($exam->cc_chapter_id) {
                        $chapter = CcChapter::find($exam->cc_chapter_id);
                        if ($chapter) $chapterInfo = ' - فصل: ' . $chapter->name;
                    }
                    $preview[] = [
                        'type' => 'exam',
                        'subject' => $exam->subject,
                        'cc_subject_id' => $exam->cc_subject_id,
                        'cc_chapter_id' => $exam->cc_chapter_id ?? null,
                        'day_index' => $dayIdx,
                        'day_name' => $weekDates[$dayIdx]['day_name'],
                        'jalali_date' => $weekDates[$dayIdx]['jalali_date'],
                        'duration_minutes' => $exam->time_per_part,
                        'description' => 'مطالعه امتحان: ' . $exam->subject . $chapterInfo . ' (تاریخ امتحان: ' . jdate($examDate)->format('Y/m/d') . ')',
                    ];
                }
            }
        }

        $this->distributionPreview = $preview;
        $this->distributionType = 'exam';
        $this->showDistributeExamModal = true;
    }

    /**
     * Compute QA distribution preview
     */
    public function previewQaDistribution(): void
    {
        if (!$this->weeklyProgramId) {
            $this->saveProgram();
        }

        $preSessions = AdvisingPreSession::where('student_id', $this->studentId)
            ->when($this->sessionId, fn($q) => $q->where('advising_session_id', $this->sessionId))
            ->with('qas')
            ->latest()
            ->first();

        if (!$preSessions || $preSessions->qas->isEmpty()) {
            $this->dispatch('warning', 'پرسش و پاسخی برای توزیع وجود ندارد.');
            return;
        }

        $startDate = Carbon::parse($this->start_date);
        $weekDates = [];
        $jalaliDayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];

        for ($i = 0; $i < 8; $i++) {
            $date = $startDate->copy()->addDays($i);
            $jalaliDate = jdate($date);
            $dayOfWeek = $jalaliDate->getDayOfWeek();
            $weekDates[$i] = [
                'date' => $date,
                'day_name' => $jalaliDayNames[$dayOfWeek],
                'jalali_date' => $jalaliDate->format('Y/m/d'),
            ];
        }

        $preview = [];
        foreach ($preSessions->qas as $qa) {
            $qaDate = Carbon::parse($qa->qa_date);
            // One day before the specified date
            $targetDate = $qaDate->copy()->subDay();
            $targetDayIndex = null;

            for ($i = 0; $i < 8; $i++) {
                if ($weekDates[$i]['date']->isSameDay($targetDate)) {
                    $targetDayIndex = $i;
                    break;
                }
            }

            // If no exact match, find closest day before
            if ($targetDayIndex === null) {
                for ($i = 7; $i >= 0; $i--) {
                    if ($weekDates[$i]['date']->lte($targetDate)) {
                        $targetDayIndex = $i;
                        break;
                    }
                }
            }

            if ($targetDayIndex === null) $targetDayIndex = 0;

            for ($p = 0; $p < $qa->part_count; $p++) {
                $chapterInfo = '';
                if ($qa->cc_chapter_id ?? null) {
                    $chapter = CcChapter::find($qa->cc_chapter_id);
                    if ($chapter) $chapterInfo = ' - فصل: ' . $chapter->name;
                }
                $preview[] = [
                    'type' => 'qa',
                    'subject' => $qa->subject,
                    'cc_subject_id' => $qa->cc_subject_id ?? null,
                    'cc_chapter_id' => $qa->cc_chapter_id ?? null,
                    'day_index' => $targetDayIndex,
                    'day_name' => $weekDates[$targetDayIndex]['day_name'],
                    'jalali_date' => $weekDates[$targetDayIndex]['jalali_date'],
                    'duration_minutes' => $qa->time_per_part,
                    'description' => 'پرسش و پاسخ: ' . $qa->subject . $chapterInfo . ' (تاریخ: ' . jdate($qaDate)->format('Y/m/d') . ')',
                ];
            }
        }

        $this->distributionPreview = $preview;
        $this->distributionType = 'qa';
        $this->showDistributeQaModal = true;
    }

    /**
     * Apply distribution to weekly program
     */
    public function applyDistribution(): void
    {
        if (empty($this->distributionPreview)) {
            $this->dispatch('warning', 'پیش‌نمایشی برای اعمال وجود ندارد.');
            return;
        }

        if (!$this->weeklyProgramId) {
            $this->saveProgram();
        }

        $startDate = Carbon::parse($this->start_date);

        foreach ($this->distributionPreview as $item) {
            $dayIndex = $item['day_index'];
            $partDate = $startDate->copy()->addDays($dayIndex);

            $existingCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
                ->where('day_of_week', $dayIndex)
                ->count();

            if ($existingCount >= 20) {
                continue; // Skip if day is full
            }

            $partType = 'descriptive';
            if ($item['type'] === 'exam') {
                $partType = 'test';
            }
            // Determine source_type based on distribution type
            $sourceType = match ($item['type']) {
                'homework' => ProgramPart::SOURCE_HOMEWORK,
                'exam' => ProgramPart::SOURCE_EXAM,
                'qa' => ProgramPart::SOURCE_CLASS_QA,
                default => ProgramPart::SOURCE_NORMAL,
            };
            $lessonName = $item['subject'];
            $lessonType = 'specialized';

            // Try to get lesson type from cc_subject
            if (!empty($item['cc_subject_id'])) {
                $subject = CcSubject::find($item['cc_subject_id']);
                if ($subject) {
                    $lessonName = $subject->name;
                    $lessonType = $subject->type;
                }
            }

            ProgramPart::create([
                'weekly_program_id' => $this->weeklyProgramId,
                'lesson_name' => $lessonName,
                'part_date' => $partDate,
                'day_of_week' => $dayIndex,
                'part_order' => $existingCount + 1,
                'description' => $item['description'],
                'duration_minutes' => $item['duration_minutes'],
                'test_count' => null,
                'part_type' => $partType,
                'source_type' => $sourceType,
                'lesson_type' => $lessonType,
                'cc_subject_id' => $item['cc_subject_id'] ?? null,
                'cc_chapter_id' => $item['cc_chapter_id'] ?? null,
            ]);
        }

        $this->loadExistingParts();
        $this->closeDistributionModal();
        $this->dispatch('success', 'پارت‌ها با موفقیت در برنامه اعمال شدند.');
    }

    public function closeDistributionModal(): void
    {
        $this->showDistributeHomeworkModal = false;
        $this->showDistributeExamModal = false;
        $this->showDistributeQaModal = false;
        $this->distributionPreview = [];
        $this->distributionType = '';
    }
    /**
     * Check if a pre-session exam is already registered in program parts
     */
    public function isExamRegistered(int $examIndex): bool
    {
        if (!$this->weeklyProgramId) return false;

        $preSessions = AdvisingPreSession::where('student_id', $this->studentId)
            ->when($this->sessionId, fn($q) => $q->where('advising_session_id', $this->sessionId))
            ->with('exams')
            ->latest()
            ->first();

        if (!$preSessions || !isset($preSessions->exams[$examIndex])) return false;

        $exam = $preSessions->exams[$examIndex];
        return ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
            ->where('source_type', ProgramPart::SOURCE_EXAM)
            ->where(function ($q) use ($exam) {
                $q->where('description', 'like', '%' . $exam->subject . '%')
                    ->orWhere('lesson_name', $exam->subject);
            })
            ->exists();
    }

    /**
     * Check if a pre-session QA is already registered in program parts
     */
    public function isQaRegistered(int $qaIndex): bool
    {
        if (!$this->weeklyProgramId) return false;

        $preSessions = AdvisingPreSession::where('student_id', $this->studentId)
            ->when($this->sessionId, fn($q) => $q->where('advising_session_id', $this->sessionId))
            ->with('qas')
            ->latest()
            ->first();

        if (!$preSessions || !isset($preSessions->qas[$qaIndex])) return false;

        $qa = $preSessions->qas[$qaIndex];
        return ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
            ->where('source_type', ProgramPart::SOURCE_CLASS_QA)
            ->where(function ($q) use ($qa) {
                $q->where('description', 'like', '%' . $qa->subject . '%')
                    ->orWhere('lesson_name', $qa->subject);
            })            ->exists();
    }

    /**
     * Check if a pre-session assignment is already registered in program parts
     */
    public function isAssignmentRegistered(int $assignmentIndex): bool
    {
        if (!$this->weeklyProgramId) return false;

        $preSessions = AdvisingPreSession::where('student_id', $this->studentId)
            ->when($this->sessionId, fn($q) => $q->where('advising_session_id', $this->sessionId))
            ->with('assignments')
            ->latest()
            ->first();

        if (!$preSessions || !isset($preSessions->assignments[$assignmentIndex])) return false;

        $assignment = $preSessions->assignments[$assignmentIndex];
        return ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
            ->where('source_type', ProgramPart::SOURCE_HOMEWORK)
            ->where(function ($q) use ($assignment) {
                $q->where('description', 'like', '%' . $assignment->subject . '%')
                    ->orWhere('lesson_name', $assignment->subject);
            })            ->exists();
    }

    /**
     * Revert (remove) parts added from a pre-session exam
     */
    public function revertExamParts(int $examIndex): void
    {
        if (!$this->weeklyProgramId) return;

        $preSessions = AdvisingPreSession::where('student_id', $this->studentId)
            ->when($this->sessionId, fn($q) => $q->where('advising_session_id', $this->sessionId))
            ->with('exams')
            ->latest()
            ->first();

        if (!$preSessions || !isset($preSessions->exams[$examIndex])) return;

        $exam = $preSessions->exams[$examIndex];

        ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
            ->where('source_type', ProgramPart::SOURCE_EXAM)
            ->where(function ($q) use ($exam) {
                $q->where('description', 'like', '%' . $exam->subject . '%')
                    ->orWhere('lesson_name', $exam->subject);
            })            ->delete();

        $this->reorderAllDays();
        $this->loadExistingParts();
        $this->dispatch('success', 'پارت‌های امتحان «' . $exam->subject . '» از برنامه حذف شدند.');
    }

    /**
     * Revert (remove) parts added from a pre-session QA
     */
    public function revertQaParts(int $qaIndex): void
    {
        if (!$this->weeklyProgramId) return;

        $preSessions = AdvisingPreSession::where('student_id', $this->studentId)
            ->when($this->sessionId, fn($q) => $q->where('advising_session_id', $this->sessionId))
            ->with('qas')
            ->latest()
            ->first();

        if (!$preSessions || !isset($preSessions->qas[$qaIndex])) return;

        $qa = $preSessions->qas[$qaIndex];

        ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
            ->where('source_type', ProgramPart::SOURCE_CLASS_QA)
            ->where(function ($q) use ($qa) {
                $q->where('description', 'like', '%' . $qa->subject . '%')
                    ->orWhere('lesson_name', $qa->subject);
            })            ->delete();

        $this->reorderAllDays();
        $this->loadExistingParts();
        $this->dispatch('success', 'پارت‌های پرسش و پاسخ «' . $qa->subject . '» از برنامه حذف شدند.');
    }

    /**
     * Revert (remove) parts added from a pre-session assignment
     */
    public function revertAssignmentParts(int $assignmentIndex): void
    {
        if (!$this->weeklyProgramId) return;

        $preSessions = AdvisingPreSession::where('student_id', $this->studentId)
            ->when($this->sessionId, fn($q) => $q->where('advising_session_id', $this->sessionId))
            ->with('assignments')
            ->latest()
            ->first();

        if (!$preSessions || !isset($preSessions->assignments[$assignmentIndex])) return;

        $assignment = $preSessions->assignments[$assignmentIndex];

        ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
            ->where('source_type', ProgramPart::SOURCE_HOMEWORK)
            ->where(function ($q) use ($assignment) {
                $q->where('description', 'like', '%' . $assignment->subject . '%')
                    ->orWhere('lesson_name', $assignment->subject);
            })            ->delete();

        $this->reorderAllDays();
        $this->loadExistingParts();
        $this->dispatch('success', 'پارت‌های تکلیف «' . $assignment->subject . '» از برنامه حذف شدند.');
    }

    /**
     * Reorder parts for all days after deletion
     */
    protected function reorderAllDays(): void
    {
        for ($i = 0; $i < 8; $i++) {
            $dayParts = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
                ->where('day_of_week', $i)
                ->orderBy('part_order')
                ->get();

            foreach ($dayParts as $idx => $part) {
                $part->update(['part_order' => $idx + 1]);
            }
        }
    }

    /**
     * D1: Preview weekly readings (daily reading + pre-reading) for entire week
     */
    public function previewWeeklyReadings(): void
    {
        if (!$this->weeklyProgramId) {
            $this->saveProgram();
        }

        $student = Student::find($this->studentId);
        if (!$student) return;

        $schedule = ClassSchedule::where('student_id', $student->id)
            ->where('is_finalized', true)
            ->with('parts.ccSubject')
            ->latest()
            ->first();

        if (!$schedule) {
            $this->dispatch('warning', 'برنامه کلاسی دانش‌آموز یافت نشد.');
            return;
        }

        $startDate = Carbon::parse($this->start_date);
        $jalaliDayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
        $weeklyProgram = WeeklyProgram::find($this->weeklyProgramId);

        // Get last session's reading durations for this student
        $lastReadingDurations = $this->getLastReadingDurations($student->id);

        $preview = [];

        for ($i = 0; $i < 8; $i++) {
            $date = $startDate->copy()->addDays($i);
            $jalaliDate = jdate($date);
            $dayOfWeek = $jalaliDate->getDayOfWeek(); // 0=شنبه تا 6=جمعه

            if ($weeklyProgram && ($weeklyProgram->isRestDay($i) || $weeklyProgram->isExamDay($i))) {
                continue;
            }

            $tomorrowDayOfWeek = ($dayOfWeek + 1) % 7;

            // Daily reading: today's class schedule subjects
            $todayClassParts = $schedule->parts->where('day_of_week', $dayOfWeek)->sortBy('part_order');
            foreach ($todayClassParts as $classPart) {
                $subjectName = $classPart->lesson_name;
                $subjectId = $classPart->cc_subject_id;
                $duration = $lastReadingDurations['daily'][$subjectId] ?? 0;

                $preview[] = [
                    'type' => 'daily',
                    'type_label' => 'روزخوانی',
                    'subject' => $subjectName,
                    'cc_subject_id' => $subjectId,
                    'day_index' => $i,
                    'day_name' => $jalaliDayNames[$dayOfWeek],
                    'jalali_date' => $jalaliDate->format('Y/m/d'),
                    'duration_minutes' => $duration,
                    'description' => 'روزخوانی - ' . $subjectName,
                ];
            }

            // Pre-reading: tomorrow's class schedule subjects
            $tomorrowClassParts = $schedule->parts->where('day_of_week', $tomorrowDayOfWeek)->sortBy('part_order');
            foreach ($tomorrowClassParts as $classPart) {
                $subjectName = $classPart->lesson_name;
                $subjectId = $classPart->cc_subject_id;
                $duration = $lastReadingDurations['pre'][$subjectId] ?? 0;

                $preview[] = [
                    'type' => 'pre',
                    'type_label' => 'پیش‌خوانی',
                    'subject' => $subjectName,
                    'cc_subject_id' => $subjectId,
                    'day_index' => $i,
                    'day_name' => $jalaliDayNames[$dayOfWeek],
                    'jalali_date' => $jalaliDate->format('Y/m/d'),
                    'duration_minutes' => $duration,
                    'description' => 'پیش‌خوانی - ' . $subjectName,
                ];
            }
        }

        $this->weeklyReadingsPreview = $preview;
        $this->showWeeklyReadingsPreview = true;
    }

    /**
     * Get last reading durations from previous sessions
     */
    protected function getLastReadingDurations(int $studentId): array
    {
        $result = ['daily' => [], 'pre' => []];

        // Find the most recent weekly program that has reading parts
        $lastPrograms = WeeklyProgram::where('student_id', $studentId)
            ->where('id', '!=', $this->weeklyProgramId ?? 0)
            ->latest()
            ->limit(5)
            ->pluck('id');

        if ($lastPrograms->isEmpty()) return $result;

        $readingParts = ProgramPart::whereIn('weekly_program_id', $lastPrograms)
            ->where(function ($q) {
                $q->where('description', 'like', '%روزخوانی%')
                    ->orWhere('description', 'like', '%پیش‌خوانی%');
            })
            ->whereNotNull('cc_subject_id')
            ->where('duration_minutes', '>', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($readingParts as $part) {
            $type = str_contains($part->description, 'روزخوانی') ? 'daily' : 'pre';
            if (!isset($result[$type][$part->cc_subject_id])) {
                $result[$type][$part->cc_subject_id] = $part->duration_minutes;
            }
        }

        return $result;
    }

    /**
     * Apply weekly readings to the program
     */
    public function applyWeeklyReadings(): void
    {
        if (!$this->weeklyProgramId) {
            $this->dispatch('warning', 'ابتدا باید برنامه هفتگی ایجاد شود.');
            return;
        }

        $startDate = Carbon::parse($this->start_date);

        foreach ($this->weeklyReadingsPreview as $item) {
            $dayIndex = $item['day_index'];
            $partDate = $startDate->copy()->addDays($dayIndex);

            $existingCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
                ->where('day_of_week', $dayIndex)
                ->count();

            $subject = CcSubject::find($item['cc_subject_id']);

            ProgramPart::create([
                'weekly_program_id' => $this->weeklyProgramId,
                'lesson_name' => $item['subject'],
                'part_date' => $partDate,
                'day_of_week' => $dayIndex,
                'part_order' => $existingCount + 1,
                'description' => $item['description'],
                'duration_minutes' => $item['duration_minutes'],
                'test_count' => null,
                'part_type' => 'descriptive',
                'source_type' => $item['type'] === 'daily'
                    ? ProgramPart::SOURCE_DAILY_READING
                    : ProgramPart::SOURCE_PRE_READING,
                'lesson_type' => $subject?->type ?? 'specialized',
                'cc_subject_id' => $item['cc_subject_id'],
            ]);
        }

        $this->loadExistingParts();
        $this->showWeeklyReadingsPreview = false;
        $this->weeklyReadingsPreview = [];
        $this->dispatch('success', 'روزخوانی و پیش‌خوانی هفتگی با موفقیت ثبت شد.');
    }

    public function closeWeeklyReadingsPreview(): void
    {
        $this->showWeeklyReadingsPreview = false;
        $this->weeklyReadingsPreview = [];
    }

    /**
     * D2: Open exam day selection modal for class exams
     */
    public function openExamDaySelect(int $examIndex): void
    {
        $preSessions = AdvisingPreSession::where('student_id', $this->studentId)
            ->when($this->sessionId, fn($q) => $q->where('advising_session_id', $this->sessionId))
            ->with('exams')
            ->latest()
            ->first();

        if (!$preSessions || !isset($preSessions->exams[$examIndex])) {
            $this->dispatch('warning', 'امتحان یافت نشد.');
            return;
        }

        $exam = $preSessions->exams[$examIndex];
        $this->examDaySelectData = [
            'subject' => $exam->subject,
            'cc_subject_id' => $exam->cc_subject_id,
            'part_count' => $exam->part_count,
            'time_per_part' => $exam->time_per_part,
            'exam_date' => $exam->exam_date,
        ];
        $this->examDaySelectTarget = null;
        $this->showExamDaySelectModal = true;
    }

    /**
     * Apply class exam to a specific day
     */
    public function applyExamToDay(): void
    {
        if ($this->examDaySelectTarget === null || empty($this->examDaySelectData)) {
            $this->dispatch('warning', 'لطفا یک روز انتخاب کنید.');
            return;
        }

        if (!$this->weeklyProgramId) {
            $this->saveProgram();
        }

        $startDate = Carbon::parse($this->start_date);
        $dayIndex = $this->examDaySelectTarget;
        $partDate = $startDate->copy()->addDays($dayIndex);
        $data = $this->examDaySelectData;

        $subject = $data['cc_subject_id'] ? CcSubject::find($data['cc_subject_id']) : null;

        for ($p = 0; $p < $data['part_count']; $p++) {
            $existingCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
                ->where('day_of_week', $dayIndex)
                ->count();

            ProgramPart::create([
                'weekly_program_id' => $this->weeklyProgramId,
                'lesson_name' => $data['subject'],
                'part_date' => $partDate,
                'day_of_week' => $dayIndex,
                'part_order' => $existingCount + 1,
                'description' => 'امتحان کلاسی: ' . $data['subject'],
                'duration_minutes' => $data['time_per_part'],
                'test_count' => null,
                'part_type' => 'descriptive',
                     'source_type' => ProgramPart::SOURCE_EXAM,
                'lesson_type' => $subject?->type ?? 'specialized',
                'cc_subject_id' => $data['cc_subject_id'],
            ]);
        }

        $this->loadExistingParts();
        $this->showExamDaySelectModal = false;
        $this->examDaySelectData = [];
        $this->examDaySelectTarget = null;
        $this->dispatch('success', $data['part_count'] . ' پارت امتحان کلاسی با موفقیت ثبت شد.');
    }

    public function closeExamDaySelectModal(): void
    {
        $this->showExamDaySelectModal = false;
        $this->examDaySelectData = [];
        $this->examDaySelectTarget = null;
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

            // Check if this day is a rest day or exam day
            $isRestDay = $weeklyProgram ? $weeklyProgram->isRestDay($i) : false;
            $isExamDay = $weeklyProgram ? $weeklyProgram->isExamDay($i) : false;

            $weekDays[] = [
                'index' => $i,
                'name' => $dayName,
                'date' => $date,
                'jalali_date' => $jalaliDate->format('Y/m/d'),
                'parts' => $dayParts,
                'total_hours' => round($dayParts->sum('duration_minutes') / 60, 1),
                'total_tests' => $dayParts->sum('test_count') ?? 0,
                'is_rest_day' => $isRestDay,
                'is_exam_day' => $isExamDay,

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
        $classScheduleData = $this->getClassScheduleData();
        return view('livewire.admin.student.consultation.weekly-program-upload', [
            'student' => $student,
            'educationLevels' => $educationLevels,
            'weeklyProgram' => $weeklyProgram,
            'weekDays' => $weekDays,
            'preSessions' => $preSessions,
            'advisorName' => $advisorName,
            'supporterName' => $supporterName,
            'classScheduleData' => $classScheduleData,
        ])->layout('layouts.admin.app');
    }
}
