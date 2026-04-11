<?php

namespace App\Livewire\Admin\Student\Consultation;

use App\Models\Student;
use App\Models\AdvisingSession;
use App\Models\WeeklyProgram;
use App\Models\ProgramPart;
use App\Models\AdvisingPreSession;
use App\Models\WeeklyProgramRestDay;
use App\Models\ClassSchedule;
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
use App\Models\ClassificationProject;
use App\Models\StudentClassification;
use App\Models\DailyReport;
use App\Models\StudyPartSession;

class WeeklyProgramUpload extends Component
{
    use WithPagination;
    public string $prevProgramSortField = 'day';
    public string $prevProgramFilterGrade = '';
    public string $prevProgramFilterType = '';
    public string $prevProgramFilterDay = '';
    public string $prevStudySortField = 'day';
    public string $prevStudyFilterTypeField = '';
    public string $prevStudyFilterDayField = '';
    // ==================== Core ====================
    public $studentId;
    public $sessionId;
    public $weeklyProgramId;
    public $start_date;
    public $parts = [];
    public $selectedDay = 0;

    // ==================== Part Modal ====================
    public bool $showPartModal = false;
    public ?int $editingPartId = null;
    public array $partForm = [
        'education_level_id' => '',
        'cc_grade_id'        => '',
        'cc_field_id'        => '',
        'cc_subject_id'      => '',
        'cc_chapter_id'      => '',
        'cc_topic_id'        => '',
        'lesson_name'        => '',
        'description'        => '',
        'duration_minutes'   => 60,
        'test_count'         => null,
        'part_type'          => 'descriptive',
        'program_part_source_id' => null,
        'lesson_type'        => 'specialized',
        'grade'              => '',
    ];
    public $partSources  = [];
    public $grades       = [];
    public $fields       = [];
    public $subjects     = [];
    public $chapters     = [];
    public $topics       = [];

    // ==================== Global Search ====================
    public string $globalSearch = '';
    public array  $globalSearchResults = [];

    // ==================== Rest Day ====================
    public bool  $showRestDayConfirmModal = false;
    public ?int  $restDayToToggle         = null;
    public int   $partsCountForRestDay    = 0;

    // ==================== Exam Day ====================
    public bool  $showExamDayConfirmModal = false;
    public ?int  $examDayToToggle         = null;
    public int   $partsCountForExamDay    = 0;
    public bool  $showExamPartModal       = false;
    public ?int  $editingExamPartId       = null;
    public array $examPartForm = [
        'exam_name'        => '',
        'duration_minutes' => 60,
        'description'      => '',
    ];

    // ==================== Class Schedule ====================
    public bool $showClassScheduleModal = false;
    public bool $showNoScheduleModal    = false;
    public string $readingTypeFilter    = '';

    // ==================== Weekly Readings ====================
    public bool  $showWeeklyReadingsPreview = false;
    public array $weeklyReadingsPreview     = [];

    // ==================== Exam Day Select (Pre-session) ====================
    public bool  $showExamDaySelectModal = false;
    public array $examDaySelectData      = [];
    public ?int  $examDaySelectTarget    = null;

    // ==================== Classification ====================
    public bool    $showClassificationModal      = false;
    public array   $classificationTopics         = [];
    public ?string $classificationProjectName    = null;
    public string  $classificationSort           = 'rating';
    public ?int    $classificationSelectedTopicId = null;
    public array   $classificationAddForm = [
        'day_index'        => null,
        'duration_hours'   => 1,
        'duration_minutes' => 0,
        'part_type'        => 'descriptive',
        'test_count'       => null,
    ];
    public bool $showClassificationAddForm = false;

    // ==================== Distribution (Pre-session) ====================
    public bool   $showDistributeHomeworkModal = false;
    public bool   $showDistributeExamModal     = false;
    public bool   $showDistributeQaModal       = false;
    public array  $distributionPreview         = [];
    public string $distributionType            = '';

    // ==================== Previous Session Program ====================
    public bool   $showPrevProgramModal      = false;
    public array  $prevSessionParts          = [];
    public ?int   $prevSessionProgramId      = null;
    public ?int   $copyingPrevPartId         = null;
    public ?int   $copyPrevPartTargetDay     = null;
    public array  $prevSelectedPartIds       = [];
    public ?int   $prevMultiCopyTargetDaySelected = null;
    public array  $copiedFromPrevPartIds     = [];

    // Inline add form for previous session
    public ?int  $prevPartInlineSelectedId = null;
    public array $prevPartInlineForm = [
        'part_type'        => 'descriptive',
        'duration_hours'   => 1,
        'duration_minutes' => 0,
        'day_index'        => null,
    ];
    public bool $showPrevPartInlineForm = false;

    // Sort for prev program modal
    public string $prevProgramSort = 'day'; // 'day','grade','lesson_type','rating'

    // ==================== Previous Session Report/Study ====================
    public bool   $showPrevReportModal = false;
    public array  $prevReportData      = [];
    public string $prevReportType      = '';

    // Sort for report modal
    public string $prevStudySort = 'day'; // 'day','lesson_type','rating','not_registered'

    // ==================== Copy/Cut/Select ====================
    public bool  $partSelectMode  = false;
    public bool  $cutMode         = false;
    public array $selectedPartIds = [];
    public $copyTargetDay  = null;
    public array $copyTargetDays = [];

    // ==================== Bulk Delete ====================
    public bool  $bulkDeleteMode             = false;
    public array $bulkDeleteSelectedIds      = [];
    public bool  $showBulkDeleteConfirmModal = false;

    // ==================== Zero Time Warning ====================
    public bool $showZeroTimeWarningModal = false;
    public int  $zeroTimePartsCount       = 0;

    // ==================== Prev Program Filters ====================
    public string $prevFilterGrade    = ''; // '', '10', '11', '12'
    public string $prevFilterType     = ''; // '', 'general', 'specialized'
    public string $prevFilterRating   = 'desc'; // 'desc', 'asc'
    public string $prevFilterDay      = ''; // روز هفته

    // ==================== Prev Study Filters ====================
    public string $prevStudyFilterType       = ''; // '', 'general', 'specialized'
    public string $prevStudyFilterRating     = 'desc';
    public string $prevStudyFilterDay        = '';
    public bool   $prevStudyShowUnregistered = false;

    // ==================== Report Filters ====================
    public string $prevReportFilterDay = ''; // نمایش روزهای ارسال نشده در کنار ارسال شده

    // ==================== Messages ====================
    protected function messages(): array
    {
        return [
            'start_date.required'                => 'تاریخ شروع برنامه الزامی است.',
            'start_date.date'                    => 'فرمت تاریخ صحیح نیست.',
            'partForm.lesson_name.required'      => 'نام درس الزامی است.',
            'partForm.duration_minutes.required' => 'مدت زمان الزامی است.',
            'partForm.duration_minutes.min'      => 'مدت زمان باید حداقل ۱ دقیقه باشد.',
            'partForm.part_type.required'        => 'نوع پارت الزامی است.',
            'partForm.lesson_type.required'      => 'نوع درس الزامی است.',
        ];
    }

    // ==================== Mount ====================
    public function mount(Student $student, AdvisingSession $session = null): void
    {
        $this->studentId = $student->id;
        $this->sessionId = $session?->id;

        $existingProgram = WeeklyProgram::where('student_id', $this->studentId)
            ->when($this->sessionId, fn($q) => $q->where('advising_session_id', $this->sessionId))
            ->latest()
            ->first();

        if ($existingProgram) {
            $this->weeklyProgramId = $existingProgram->id;
            $this->start_date      = $existingProgram->start_date->format('Y-m-d');
            $this->loadExistingParts();
        } else {
            $this->start_date = ($session && $session->activation_date)
                ? Carbon::parse($session->activation_date)->format('Y-m-d')
                : Carbon::tomorrow()->format('Y-m-d');
        }

        for ($i = 0; $i < 8; $i++) {
            if (!isset($this->parts[$i])) {
                $this->parts[$i] = [];
            }
        }
    }

    // ==================== Load Parts ====================
    protected function loadExistingParts(): void
    {
        if (!$this->weeklyProgramId) return;

        $program = WeeklyProgram::find($this->weeklyProgramId);
        if (!$program) return;

        $this->parts = [];
        for ($i = 0; $i < 8; $i++) {
            $this->parts[$i] = $program->parts()
                ->where('day_of_week', $i)
                ->orderBy('part_order')
                ->get()
                ->toArray();
        }
    }

    // ==================== Part Modal ====================
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
        if (!$part) return;

        $this->editingPartId = $partId;
        $this->selectedDay   = $part->day_of_week;

        $this->partForm = [
            'education_level_id' => $part->education_level_id,
            'cc_grade_id'        => $part->cc_grade_id,
            'cc_field_id'        => $part->cc_field_id,
            'cc_subject_id'      => $part->cc_subject_id,
            'cc_chapter_id'      => $part->cc_chapter_id,
            'cc_topic_id'        => $part->cc_topic_id,
            'lesson_id'          => $part->lesson_id,
            'lesson_name'        => $part->lesson_name,
            'description'        => $part->description,
            'duration_minutes'   => $part->duration_minutes,
            'test_count'         => $part->test_count,
            'part_type'          => $part->part_type,
            'lesson_type'        => $part->lesson_type,
            'grade'              => $part->grade,
        ];

        if ($part->education_level_id) {
            $this->grades = CcGrade::where('education_level_id', $part->education_level_id)
                ->where('is_active', true)->with('field')->orderBy('order')->get();
        }

        $this->fields = CcField::active()->ordered()->get();

        if ($part->cc_grade_id) {
            $this->subjects = CcSubject::where('cc_grade_id', $part->cc_grade_id)
                ->when($part->cc_field_id, fn($q) => $q->where(function ($q2) use ($part) {
                    $q2->where('cc_field_id', $part->cc_field_id)->orWhereNull('cc_field_id');
                }))->orderBy('order')->get();
        }

        if ($part->cc_subject_id) {
            $this->chapters = CcChapter::where('cc_subject_id', $part->cc_subject_id)
                ->where('is_active', true)->orderBy('order')->get();
        }

        if ($part->cc_chapter_id) {
            $this->topics = CcTopic::where('cc_chapter_id', $part->cc_chapter_id)
                ->where('is_active', true)->whereNull('parent_id')->orderBy('order')->get();
        }

        $this->showPartModal = true;
        $this->dispatch('modal-opened');
    }

    public function resetPartForm(): void
    {
        $this->editingPartId = null;
        $this->partForm = [
            'education_level_id' => '',
            'cc_grade_id'        => '',
            'cc_field_id'        => '',
            'cc_subject_id'      => '',
            'cc_chapter_id'      => '',
            'cc_topic_id'        => '',
            'lesson_name'        => '',
            'description'        => '',
            'duration_minutes'   => 60,
            'test_count'         => null,
            'part_type'          => 'descriptive',
            'lesson_type'        => 'specialized',
            'grade'              => '',
        ];
        $this->grades   = [];
        $this->fields   = [];
        $this->subjects = [];
        $this->chapters = [];
        $this->topics   = [];
        $this->globalSearch        = '';
        $this->globalSearchResults = [];
    }

    public function closePartModal(): void
    {
        $this->showPartModal = false;
        $this->resetPartForm();
        $this->dispatch('modal-closed');
    }

    // ==================== Cascading Selects ====================
    public function updatedPartFormEducationLevelId($value): void
    {
        $this->partForm['cc_grade_id']   = '';
        $this->partForm['cc_field_id']   = '';
        $this->partForm['cc_subject_id'] = '';
        $this->partForm['cc_chapter_id'] = '';
        $this->partForm['cc_topic_id']   = '';
        $this->subjects = [];
        $this->chapters = [];
        $this->topics   = [];

        $this->grades = $value
            ? CcGrade::where('education_level_id', $value)->where('is_active', true)->with('field')->orderBy('order')->get()
            : [];

        $this->fields = CcField::active()->ordered()->get();
        $this->dispatchSelectUpdates(['grades', 'fields', 'subjects', 'chapters', 'topics']);
    }

    public function updatedPartFormCcGradeId($value): void
    {
        $this->partForm['cc_subject_id'] = '';
        $this->partForm['cc_chapter_id'] = '';
        $this->partForm['cc_topic_id']   = '';
        $this->chapters = [];
        $this->topics   = [];

        if ($value) {
            $grade = CcGrade::find($value);
            if ($grade) $this->partForm['grade'] = $grade->grade_number;

            $fieldId        = $this->partForm['cc_field_id'] ?: null;
            $this->subjects = CcSubject::where('cc_grade_id', $value)
                ->when($fieldId, fn($q) => $q->where(function ($q2) use ($fieldId) {
                    $q2->where('cc_field_id', $fieldId)->orWhereNull('cc_field_id');
                }))->orderBy('order')->get();
        } else {
            $this->subjects = [];
        }

        $this->dispatchSelectUpdates(['subjects', 'chapters', 'topics']);
    }

    public function updatedPartFormCcFieldId($value): void
    {
        $this->partForm['cc_subject_id'] = '';
        $this->partForm['cc_chapter_id'] = '';
        $this->partForm['cc_topic_id']   = '';
        $this->chapters = [];
        $this->topics   = [];

        if ($this->partForm['cc_grade_id']) {
            $this->subjects = CcSubject::where('cc_grade_id', $this->partForm['cc_grade_id'])
                ->when($value, fn($q) => $q->where(function ($q2) use ($value) {
                    $q2->where('cc_field_id', $value)->orWhereNull('cc_field_id');
                }))->orderBy('order')->get();
        }

        $this->dispatchSelectUpdates(['subjects', 'chapters', 'topics']);
    }

    public function updatedPartFormCcSubjectId($value): void
    {
        $this->partForm['cc_chapter_id'] = '';
        $this->partForm['cc_topic_id']   = '';
        $this->topics = [];

        if ($value) {
            $subject = CcSubject::find($value);
            if ($subject) {
                $this->partForm['lesson_name'] = $subject->name;
                $this->partForm['lesson_type'] = $subject->type;
            }
            $this->chapters = CcChapter::where('cc_subject_id', $value)
                ->where('is_active', true)->orderBy('order')->get();
        } else {
            $this->chapters = [];
        }

        $this->dispatchSelectUpdates(['chapters', 'topics']);
    }

    public function updatedPartFormCcChapterId($value): void
    {
        $this->partForm['cc_topic_id'] = '';

        $this->topics = $value
            ? CcTopic::where('cc_chapter_id', $value)->where('is_active', true)->whereNull('parent_id')->orderBy('order')->get()
            : [];

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

    protected function dispatchSelectUpdates(array $keys): void
    {
        $mapping = [
            'grades' => [
                'id'        => 'grade-select',
                'items'     => $this->grades,
                'selected'  => $this->partForm['cc_grade_id'],
                'emptyText' => 'ابتدا دوره را انتخاب کنید',
                'format'    => fn($item) => is_object($item) ? $item->name : ($item['name'] ?? ''),
            ],
            'fields' => [
                'id'        => 'field-select',
                'items'     => $this->fields,
                'selected'  => $this->partForm['cc_field_id'],
                'emptyText' => 'بدون رشته',
                'format'    => fn($item) => is_object($item) ? $item->name : ($item['name'] ?? ''),
            ],
            'subjects' => [
                'id'        => 'subject-select',
                'items'     => $this->subjects,
                'selected'  => $this->partForm['cc_subject_id'],
                'emptyText' => 'ابتدا پایه را انتخاب کنید',
                'format'    => function ($item) {
                    $name  = is_object($item) ? $item->name : ($item['name'] ?? '');
                    $type  = is_object($item) ? $item->type : ($item['type'] ?? 'specialized');
                    $label = $type === 'general' ? 'عمومی' : 'تخصصی';
                    return "{$name} ({$label})";
                },
            ],
            'chapters' => [
                'id'        => 'chapter-select',
                'items'     => $this->chapters,
                'selected'  => $this->partForm['cc_chapter_id'],
                'emptyText' => 'ابتدا درس را انتخاب کنید',
                'format'    => fn($item) => is_object($item) ? $item->name : ($item['name'] ?? ''),
            ],
            'topics' => [
                'id'        => 'topic-select',
                'items'     => $this->topics,
                'selected'  => $this->partForm['cc_topic_id'],
                'emptyText' => 'ابتدا فصل را انتخاب کنید',
                'format'    => fn($item) => is_object($item) ? $item->name : ($item['name'] ?? ''),
            ],
        ];

        foreach ($keys as $key) {
            if (!isset($mapping[$key])) continue;

            $config  = $mapping[$key];
            $items   = $config['items'];
            $options = [];

            $placeholder = count($items) > 0 ? 'انتخاب کنید' : $config['emptyText'];
            $options[]   = ['value' => '', 'text' => $placeholder];

            foreach ($items as $item) {
                $id      = is_object($item) ? $item->id : ($item['id'] ?? '');
                $text    = ($config['format'])($item);
                $options[] = ['value' => $id, 'text' => $text];
            }

            $this->dispatch('select2-update', [
                'id'       => $config['id'],
                'options'  => $options,
                'selected' => $config['selected'] ?? '',
                'disabled' => count($items) === 0,
            ]);
        }
    }

    // ==================== Student Filters ====================
    protected function getStudentFieldFilter(): ?int
    {
        $student = Student::with('user.personalInformation')->find($this->studentId);
        if (!$student?->user?->personalInformation) return null;

        $field = $student->user->personalInformation->field;
        if (!$field) return null;

        return CcField::where('slug', CcField::mapFromPersonalInfo($field))->where('is_active', true)->first()?->id;
    }

    protected function getStudentGradeFilter(): array
    {
        $student = Student::with('user.personalInformation')->find($this->studentId);
        if (!$student?->user?->personalInformation) return ['grade_numbers' => null, 'field_id' => null];

        $gradeNum  = (int)($student->user->personalInformation->grade ?? 0);
        $field     = $student->user->personalInformation->field;
        $ccFieldId = null;

        if ($field) {
            $ccFieldId = CcField::where('slug', CcField::mapFromPersonalInfo($field))->where('is_active', true)->first()?->id;
        }

        return match (true) {
            $gradeNum === 12             => ['grade_numbers' => [10, 11, 12], 'field_id' => $ccFieldId],
            $gradeNum === 11             => ['grade_numbers' => [10, 11],     'field_id' => $ccFieldId],
            $gradeNum === 10             => ['grade_numbers' => [10],         'field_id' => $ccFieldId],
            $gradeNum >= 7 && $gradeNum <= 9 => ['grade_numbers' => [$gradeNum], 'field_id' => null],
            default                      => ['grade_numbers' => null,         'field_id' => null],
        };
    }

    // ==================== Global Search ====================
    public function updatedGlobalSearch($value): void
    {
        if (mb_strlen($value) < 2) {
            $this->globalSearchResults = [];
            return;
        }

        $results     = [];
        $gradeFilter = $this->getStudentGradeFilter();
        $allowedGrades   = $gradeFilter['grade_numbers'];
        $studentFieldId  = $gradeFilter['field_id'];
        $seen = [];

        // 1. Subjects
        $subjectQuery = CcSubject::where('name', 'like', "%{$value}%")
            ->with(['grade.educationLevel', 'chapters' => fn($q) => $q->where('is_active', true)->orderBy('order')]);

        if ($studentFieldId) {
            $subjectQuery->where(fn($q) => $q->where('cc_field_id', $studentFieldId)->orWhereNull('cc_field_id'));
        }
        if ($allowedGrades !== null) {
            $subjectQuery->whereHas('grade', fn($q) => $q->whereIn('grade_number', $allowedGrades));
        }

        foreach ($subjectQuery->limit(5)->get() as $subject) {
            $grade          = $subject->grade;
            $educationLevel = $grade?->educationLevel;
            if (!$grade || !$educationLevel) continue;

            $subjectKey = 'subject_' . $subject->id;
            if (!isset($seen[$subjectKey])) {
                $seen[$subjectKey] = true;
                $results[] = [
                    'type'               => 'subject',
                    'sort'               => 0,
                    'topic_id'           => null,
                    'chapter_id'         => null,
                    'subject_id'         => $subject->id,
                    'grade_id'           => $grade->id,
                    'grade_name'         => $grade->name,
                    'grade_number'       => $grade->grade_number,
                    'field_id'           => $subject->cc_field_id,
                    'education_level_id' => $educationLevel->id,
                    'label'              => $subject->name,
                ];
            }

            foreach ($subject->chapters as $chapter) {
                $key = 'chapter_' . $chapter->id;
                if (isset($seen[$key])) continue;
                $seen[$key] = true;
                $results[] = [
                    'type'               => 'chapter',
                    'sort'               => 1,
                    'topic_id'           => null,
                    'chapter_id'         => $chapter->id,
                    'subject_id'         => $subject->id,
                    'grade_id'           => $grade->id,
                    'grade_name'         => $grade->name,
                    'grade_number'       => $grade->grade_number,
                    'field_id'           => $subject->cc_field_id,
                    'education_level_id' => $educationLevel->id,
                    'label'              => $subject->name . ' / ' . $chapter->name,
                ];
            }
        }

        // 2. Chapters
        $chapters = CcChapter::where('is_active', true)
            ->where('name', 'like', "%{$value}%")
            ->with(['subject.grade.educationLevel', 'topics' => fn($q) => $q->where('is_active', true)->whereNull('parent_id')->orderBy('order')])
            ->whereHas('subject', function ($q) use ($studentFieldId, $allowedGrades) {
                if ($studentFieldId) {
                    $q->where(fn($q2) => $q2->where('cc_field_id', $studentFieldId)->orWhereNull('cc_field_id'));
                }
                if ($allowedGrades !== null) {
                    $q->whereHas('grade', fn($q3) => $q3->whereIn('grade_number', $allowedGrades));
                }
            })->limit(8)->get();

        foreach ($chapters as $chapter) {
            $subject        = $chapter->subject;
            $grade          = $subject?->grade;
            $educationLevel = $grade?->educationLevel;
            if (!$subject || !$grade || !$educationLevel) continue;

            $chapterKey = 'chapter_' . $chapter->id;
            if (!isset($seen[$chapterKey])) {
                $seen[$chapterKey] = true;
                $results[] = [
                    'type'               => 'chapter',
                    'sort'               => 1,
                    'topic_id'           => null,
                    'chapter_id'         => $chapter->id,
                    'subject_id'         => $subject->id,
                    'grade_id'           => $grade->id,
                    'grade_name'         => $grade->name,
                    'grade_number'       => $grade->grade_number,
                    'field_id'           => $subject->cc_field_id,
                    'education_level_id' => $educationLevel->id,
                    'label'              => $subject->name . ' / ' . $chapter->name,
                ];
            }

            foreach ($chapter->topics as $topic) {
                $key = 'topic_' . $topic->id;
                if (isset($seen[$key])) continue;
                $seen[$key] = true;
                $results[] = [
                    'type'               => 'topic',
                    'sort'               => 2,
                    'topic_id'           => $topic->id,
                    'chapter_id'         => $chapter->id,
                    'subject_id'         => $subject->id,
                    'grade_id'           => $grade->id,
                    'grade_name'         => $grade->name,
                    'grade_number'       => $grade->grade_number,
                    'field_id'           => $subject->cc_field_id,
                    'education_level_id' => $educationLevel->id,
                    'label'              => $subject->name . ' / ' . $chapter->name . ' / ' . $topic->name,
                ];
            }
        }

        // 3. Topics
        $topics = CcTopic::where('is_active', true)
            ->whereNull('parent_id')
            ->where('name', 'like', "%{$value}%")
            ->with(['chapter.subject.grade.educationLevel'])
            ->whereHas('chapter.subject', function ($q) use ($studentFieldId, $allowedGrades) {
                if ($studentFieldId) {
                    $q->where(fn($q2) => $q2->where('cc_field_id', $studentFieldId)->orWhereNull('cc_field_id'));
                }
                if ($allowedGrades !== null) {
                    $q->whereHas('grade', fn($q3) => $q3->whereIn('grade_number', $allowedGrades));
                }
            })->limit(10)->get();

        foreach ($topics as $topic) {
            $key = 'topic_' . $topic->id;
            if (isset($seen[$key])) continue;
            $seen[$key] = true;

            $chapter        = $topic->chapter;
            $subject        = $chapter?->subject;
            $grade          = $subject?->grade;
            $educationLevel = $grade?->educationLevel;
            if (!$chapter || !$subject || !$grade || !$educationLevel) continue;

            $results[] = [
                'type'               => 'topic',
                'sort'               => 2,
                'topic_id'           => $topic->id,
                'chapter_id'         => $chapter->id,
                'subject_id'         => $subject->id,
                'grade_id'           => $grade->id,
                'grade_name'         => $grade->name,
                'grade_number'       => $grade->grade_number,
                'field_id'           => $subject->cc_field_id,
                'education_level_id' => $educationLevel->id,
                'label'              => $subject->name . ' / ' . $chapter->name . ' / ' . $topic->name,
            ];
        }

        usort($results, fn($a, $b) => $a['sort'] <=> $b['sort']);
        $results = array_map(fn($r) => array_diff_key($r, ['sort' => '']), $results);
        $this->globalSearchResults = array_slice($results, 0, 15);
    }

    public function selectGlobalResult(int $index): void
    {
        if (!isset($this->globalSearchResults[$index])) return;

        $result = $this->globalSearchResults[$index];

        $this->partForm['education_level_id'] = $result['education_level_id'];
        $this->partForm['cc_grade_id']        = $result['grade_id'];
        $this->partForm['cc_field_id']        = $result['field_id'] ?? '';
        $this->partForm['cc_subject_id']      = $result['subject_id'];
        $this->partForm['cc_chapter_id']      = $result['chapter_id'] ?? '';
        $this->partForm['cc_topic_id']        = $result['topic_id'] ?? '';

        $this->grades = CcGrade::where('education_level_id', $result['education_level_id'])
            ->where('is_active', true)->with('field')->orderBy('order')->get();

        $this->fields = CcField::active()->ordered()->get();

        $fieldId        = $result['field_id'] ?: null;
        $this->subjects = CcSubject::where('cc_grade_id', $result['grade_id'])
            ->when($fieldId, fn($q) => $q->where(fn($q2) => $q2->where('cc_field_id', $fieldId)->orWhereNull('cc_field_id')))
            ->orderBy('order')->get();

        if ($result['subject_id']) {
            $subject = CcSubject::find($result['subject_id']);
            if ($subject) {
                $this->partForm['lesson_name'] = $subject->name;
                $this->partForm['lesson_type'] = $subject->type;
            }
            $this->chapters = CcChapter::where('cc_subject_id', $result['subject_id'])
                ->where('is_active', true)->orderBy('order')->get();
        }

        if ($result['chapter_id']) {
            $this->topics = CcTopic::where('cc_chapter_id', $result['chapter_id'])
                ->where('is_active', true)->whereNull('parent_id')->orderBy('order')->get();
        }

        $grade = CcGrade::find($result['grade_id']);
        if ($grade) $this->partForm['grade'] = $grade->grade_number;

        // Build description
        $descParts = [];
        if ($result['subject_id'] && $s = CcSubject::find($result['subject_id'])) $descParts[] = $s->name;
        if ($result['chapter_id'] && $c = CcChapter::find($result['chapter_id']))  $descParts[] = $c->name;
        if ($result['topic_id']   && $t = CcTopic::find($result['topic_id']))      $descParts[] = $t->name;
        if (!empty($descParts)) $this->partForm['description'] = implode(' » ', $descParts);

        $this->globalSearch        = '';
        $this->globalSearchResults = [];

        // Update education level select
        $educationLevels = EducationLevel::active()->ordered()->get();
        $eduOptions      = [['value' => '', 'text' => 'انتخاب کنید']];
        foreach ($educationLevels as $level) {
            $eduOptions[] = ['value' => $level->id, 'text' => $level->name];
        }
        $this->dispatch('select2-update', [
            'id'       => 'education-level-select',
            'options'  => $eduOptions,
            'selected' => $result['education_level_id'],
            'disabled' => false,
        ]);

        $this->dispatchSelectUpdates(['grades', 'fields', 'subjects', 'chapters', 'topics']);
    }

    // ==================== Save Part ====================
    public function savePart(): void
    {
        $this->validate([
            'partForm.cc_subject_id'      => 'required|exists:cc_subjects,id',
            'partForm.duration_minutes'   => 'required|integer|min:1',
            'partForm.part_type'          => 'required|in:test,descriptive,video,topic_exam',
        ], [
            'partForm.cc_subject_id.required'    => 'انتخاب درس الزامی است.',
            'partForm.duration_minutes.required' => 'مدت زمان الزامی است.',
            'partForm.duration_minutes.min'      => 'مدت زمان باید حداقل ۱ دقیقه باشد.',
            'partForm.part_type.required'        => 'نوع پارت الزامی است.',
        ]);

        $gradeValue = ($this->partForm['grade'] !== '' && $this->partForm['grade'] !== null)
            ? (string)$this->partForm['grade']
            : null;

        $grade      = CcGrade::find($this->partForm['cc_grade_id']);
        $gradeLabel = $grade?->name;

        $this->saveProgram();

        $partDate   = Carbon::parse($this->start_date)->addDays((int)$this->selectedDay);
        $subject    = CcSubject::find($this->partForm['cc_subject_id']);
        $lessonName = $subject?->name ?? $this->partForm['lesson_name'];
        $lessonType = $subject?->type ?? 'specialized';

        $commonData = [
            'lesson_name'        => $lessonName,
            'description'        => $this->partForm['description'],
            'duration_minutes'   => $this->partForm['duration_minutes'],
            'test_count'         => $this->partForm['test_count'],
            'part_type'          => $this->partForm['part_type'],
            'lesson_type'        => $lessonType,
            'grade'              => $gradeValue,
            'grade_label'        => $gradeLabel,
            'education_level_id' => $this->partForm['education_level_id'] ?: null,
            'cc_grade_id'        => $this->partForm['cc_grade_id'] ?: null,
            'cc_field_id'        => $this->partForm['cc_field_id'] ?: null,
            'cc_subject_id'      => $this->partForm['cc_subject_id'] ?: null,
            'cc_chapter_id'      => $this->partForm['cc_chapter_id'] ?: null,
            'cc_topic_id'        => $this->partForm['cc_topic_id'] ?: null,
        ];

        if ($this->editingPartId) {
            $part = ProgramPart::find($this->editingPartId);
            if (!$part) return;
            $part->update(array_merge($commonData, ['lesson_id' => null, 'source_type' => ProgramPart::SOURCE_NORMAL]));
        } else {
            $existingCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
                ->where('day_of_week', $this->selectedDay)->count();

            if ($existingCount >= 20) {
                $this->dispatch('warning', 'حداکثر ۲۰ پارت برای هر روز مجاز است.');
                return;
            }

            ProgramPart::create(array_merge($commonData, [
                'weekly_program_id' => $this->weeklyProgramId,
                'lesson_id'         => null,
                'part_date'         => $partDate,
                'day_of_week'       => $this->selectedDay,
                'part_order'        => $existingCount + 1,
            ]));
        }

        $this->loadExistingParts();
        $this->closePartModal();
        $this->dispatch('success', 'پارت با موفقیت ذخیره شد.');
    }

    public function deletePart(int $partId): void
    {
        ProgramPart::find($partId)?->delete();
        $this->loadExistingParts();
        $this->closePartModal();
        $this->dispatch('success', 'پارت حذف شد.');
    }

    // ==================== Save Program ====================
    public function saveProgram(): void
    {
        $this->validate(['start_date' => 'required|date'], $this->messages());

        $startDate = Carbon::parse($this->start_date);
        $endDate   = $startDate->copy()->addDays(7);

        if ($this->weeklyProgramId) {
            WeeklyProgram::find($this->weeklyProgramId)?->update([
                'start_date' => $startDate,
                'end_date'   => $endDate,
            ]);
        } else {
            $program = WeeklyProgram::create([
                'student_id'          => $this->studentId,
                'advisor_id'          => auth()->id(),
                'advising_session_id' => $this->sessionId,
                'start_date'          => $startDate,
                'end_date'            => $endDate,
                'is_active'           => true,
            ]);
            $this->weeklyProgramId = $program->id;
        }
    }

    public function finalSave(): void
    {
        $this->saveProgram();

        if ($this->weeklyProgramId) {
            $totalParts = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)->count();
            if ($totalParts === 0) {
                $this->dispatch('warning', 'برنامه هیچ پارتی ندارد. ابتدا پارت اضافه کنید.');
                return;
            }

            $zeroCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
                ->where('duration_minutes', 0)->count();

            if ($zeroCount > 0) {
                $this->zeroTimePartsCount     = $zeroCount;
                $this->showZeroTimeWarningModal = true;
                return;
            }

            $this->markSessionAsHeld();
        }

        $this->dispatch('success', 'برنامه هفتگی با موفقیت ذخیره شد.');
    }

    public function forceFinalSave(): void
    {
        $this->showZeroTimeWarningModal = false;
        $this->dispatch('success', 'برنامه هفتگی ذخیره شد (بدون تایید نهایی - پارت‌های بدون تایم وجود دارد).');
    }

    public function closeZeroTimeWarningModal(): void
    {
        $this->showZeroTimeWarningModal = false;
    }

    protected function markSessionAsHeld(): void
    {
        if (!$this->sessionId) return;
        AdvisingSession::find($this->sessionId)?->update([
            'result_status' => AdvisingSession::RESULT_HELD,
            'status'        => AdvisingSession::STATUS_COMPLETED,
        ]);
    }

    // ==================== Classification ====================
    public function openClassificationModal(): void
    {
        $student = Student::find($this->studentId);
        $userId  = $student?->user_id;

        $project = ClassificationProject::active()->latest('start_at')->first()
            ?? ClassificationProject::orderBy('end_at', 'desc')->first();

        $this->classificationProjectName = $project?->name;
        $this->classificationTopics      = [];

        if ($project && $userId) {
            $classifications = StudentClassification::where('user_id', $userId)
                ->where('classification_project_id', $project->id)
                ->with(['topic.chapter.subject'])
                ->get();

            $addedTopicIds = [];
            if ($this->weeklyProgramId) {
                $addedTopicIds = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
                    ->where('source_type', ProgramPart::SOURCE_CLASSIFICATION)
                    ->whereNotNull('cc_topic_id')
                    ->pluck('cc_topic_id')->toArray();
            }

            $this->classificationTopics = $classifications->map(function ($c) use ($addedTopicIds) {
                $subject = $c->topic?->chapter?->subject;
                $grade   = $subject?->grade;
                return [
                    'id'               => $c->id,
                    'topic_id'         => $c->cc_topic_id,
                    'topic_name'       => $c->topic?->name ?? 'نامشخص',
                    'chapter_name'     => $c->topic?->chapter?->name ?? '',
                    'subject_name'     => $subject?->name ?? '',
                    'rating'           => $c->rating,
                    'rating_label'     => $c->ratingLabel,
                    'rating_color'     => $c->ratingColor,
                    'grade'            => $grade?->grade_number ?? '',
                    'grade_label'      => match ($grade?->grade_number) {
                        '10' => 'دهم', '11' => 'یازدهم', '12' => 'دوازدهم', default => ''
                    },
                    'lesson_type'      => $subject?->type ?? 'specialized',
                    'lesson_type_label'=> ($subject?->type === 'general') ? 'عمومی' : 'تخصصی',
                    'is_added'         => in_array($c->cc_topic_id, $addedTopicIds),
                ];
            })->toArray();

            $this->applySortToClassificationTopics();
        }

        $this->showClassificationModal = true;
    }

    private function applySortToClassificationTopics(): void
    {
        $collection = collect($this->classificationTopics);

        $this->classificationTopics = match ($this->classificationSort) {
            'rating_asc'             => $collection->sortBy('rating')->values()->toArray(),
            'grade_12'               => $collection->filter(fn($i) => $i['grade'] === '12')->values()->toArray(),
            'grade_11'               => $collection->filter(fn($i) => $i['grade'] === '11')->values()->toArray(),
            'grade_10'               => $collection->filter(fn($i) => $i['grade'] === '10')->values()->toArray(),
            'lesson_type_general'    => $collection->filter(fn($i) => $i['lesson_type'] === 'general')->values()->toArray(),
            'lesson_type_specialized'=> $collection->filter(fn($i) => $i['lesson_type'] === 'specialized')->values()->toArray(),
            'lesson_type_all'        => $collection->sortBy('lesson_type')->values()->toArray(),
            default                  => $collection->sortByDesc('rating')->values()->toArray(), // rating_desc
        };
    }

    public function sortClassification(string $sort): void
    {
        $this->classificationSort = $sort;
        $this->applySortToClassificationTopics();
    }

    public function closeClassificationModal(): void
    {
        $this->showClassificationModal = false;
    }

    public function showClassificationInlineAdd(int $topicId): void
    {
        if ($this->classificationSelectedTopicId === $topicId && $this->showClassificationAddForm) {
            $this->hideClassificationInlineAdd();
            return;
        }
        $this->classificationSelectedTopicId = $topicId;
        $this->classificationAddForm = [
            'day_index'        => null,
            'duration_hours'   => 1,
            'duration_minutes' => 0,
            'part_type'        => 'descriptive',
            'test_count'       => null,
        ];
        $this->showClassificationAddForm = true;
    }

    public function hideClassificationInlineAdd(): void
    {
        $this->showClassificationAddForm     = false;
        $this->classificationSelectedTopicId = null;
    }

    public function revertClassificationPart(int $topicId): void
    {
        if (!$this->weeklyProgramId) return;

        ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
            ->where('cc_topic_id', $topicId)
            ->where('source_type', ProgramPart::SOURCE_CLASSIFICATION)
            ->delete();

        $this->loadExistingParts();
        $this->dispatch('success', 'پارت از برنامه حذف شد.');
        $this->openClassificationModal();
    }

    public function addClassificationToProgram(): void
    {
        if (!$this->classificationSelectedTopicId) return;

        $dayIndex = $this->classificationAddForm['day_index'];
        if ($dayIndex === null || $dayIndex === '') {
            $this->dispatch('warning', 'لطفاً یک روز انتخاب کنید.');
            return;
        }

        $hours        = (int)($this->classificationAddForm['duration_hours'] ?? 0);
        $minutes      = (int)($this->classificationAddForm['duration_minutes'] ?? 0);
        $totalMinutes = ($hours * 60) + $minutes;

        if ($totalMinutes < 1) {
            $this->dispatch('warning', 'مدت زمان باید حداقل ۱ دقیقه باشد.');
            return;
        }

        if (!$this->weeklyProgramId) $this->saveProgram();

        $topic   = CcTopic::with(['chapter.subject.grade.educationLevel'])->find($this->classificationSelectedTopicId);
        if (!$topic) return;

        $chapter        = $topic->chapter;
        $subject        = $chapter?->subject;
        $grade          = $subject?->grade;
        $educationLevel = $grade?->educationLevel;

        $startDate     = Carbon::parse($this->start_date);
        $partDate      = $startDate->copy()->addDays((int)$dayIndex);
        $existingCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
            ->where('day_of_week', (int)$dayIndex)->count();

        if ($existingCount >= 20) {
            $this->dispatch('warning', 'حداکثر ۲۰ پارت برای هر روز مجاز است.');
            return;
        }

        $path     = collect([$subject?->name, $chapter?->name, $topic->name])->filter()->join(' > ');
        $partType = $this->classificationAddForm['part_type'] ?? 'descriptive';
        $testCount = in_array($partType, ['test', 'topic_exam'])
            ? ((int)($this->classificationAddForm['test_count'] ?? 0) ?: null)
            : null;

        ProgramPart::create([
            'weekly_program_id'  => $this->weeklyProgramId,
            'lesson_name'        => $subject?->name ?? $topic->name,
            'part_date'          => $partDate,
            'day_of_week'        => (int)$dayIndex,
            'part_order'         => $existingCount + 1,
            'description'        => $path,
            'duration_minutes'   => $totalMinutes,
            'test_count'         => $testCount,
            'part_type'          => $partType,
            'source_type'        => ProgramPart::SOURCE_CLASSIFICATION,
            'lesson_type'        => $subject?->type ?? 'specialized',
            'grade'              => $grade?->grade_number,
            'education_level_id' => $educationLevel?->id,
            'cc_grade_id'        => $grade?->id,
            'cc_field_id'        => $subject?->cc_field_id,
            'cc_subject_id'      => $subject?->id,
            'cc_chapter_id'      => $chapter?->id,
            'cc_topic_id'        => $topic->id,
        ]);

        $this->loadExistingParts();
        $this->hideClassificationInlineAdd();
        $this->dispatch('success', 'مبحث با موفقیت به برنامه اضافه شد.');
    }

    // ==================== Reorder / Move / Swap ====================
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

    public function movePartToDay(int $partId, int $targetDayIndex): void
    {
        if (!$this->weeklyProgramId) return;

        $part = ProgramPart::where('id', $partId)->where('weekly_program_id', $this->weeklyProgramId)->first();
        if (!$part || $part->day_of_week === $targetDayIndex) return;

        $sourceDayIndex = $part->day_of_week;
        $targetCount    = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
            ->where('day_of_week', $targetDayIndex)->count();

        $part->update([
            'day_of_week' => $targetDayIndex,
            'part_date'   => Carbon::parse($this->start_date)->addDays($targetDayIndex),
            'part_order'  => $targetCount + 1,
        ]);

        $sourceParts = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
            ->where('day_of_week', $sourceDayIndex)->orderBy('part_order')->get();

        foreach ($sourceParts as $idx => $sp) {
            $sp->update(['part_order' => $idx + 1]);
        }

        $this->loadExistingParts();
        $this->dispatch('success', 'پارت با موفقیت جابه‌جا شد.');
    }

    public function swapParts(int $partId1, int $partId2): void
    {
        if (!$this->weeklyProgramId) return;

        $part1 = ProgramPart::where('id', $partId1)->where('weekly_program_id', $this->weeklyProgramId)->first();
        $part2 = ProgramPart::where('id', $partId2)->where('weekly_program_id', $this->weeklyProgramId)->first();
        if (!$part1 || !$part2) return;

        [$p1Day, $p1Order, $p1Date] = [$part1->day_of_week, $part1->part_order, $part1->part_date];

        $part1->update(['day_of_week' => $part2->day_of_week, 'part_order' => $part2->part_order, 'part_date' => $part2->part_date]);
        $part2->update(['day_of_week' => $p1Day, 'part_order' => $p1Order, 'part_date' => $p1Date]);

        $this->loadExistingParts();
        $this->dispatch('success', 'پارت‌ها با موفقیت جابه‌جا شدند.');
    }

    // ==================== Rest Day ====================
    public function toggleRestDay(int $dayIndex): void
    {
        if (!$this->weeklyProgramId) $this->saveProgram();

        $weeklyProgram = WeeklyProgram::find($this->weeklyProgramId);
        if (!$weeklyProgram) return;

        if ($weeklyProgram->isRestDay($dayIndex)) {
            WeeklyProgramRestDay::where('weekly_program_id', $this->weeklyProgramId)->where('day_index', $dayIndex)->delete();
            $this->dispatch('success', 'روز استراحت برداشته شد.');
            return;
        }

        $partsCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)->where('day_of_week', $dayIndex)->count();

        if ($partsCount > 0) {
            $this->restDayToToggle      = $dayIndex;
            $this->partsCountForRestDay = $partsCount;
            $this->showRestDayConfirmModal = true;
        } else {
            $this->restDayToToggle = $dayIndex;
            $this->confirmRestDay();
        }
    }

    public function confirmRestDay(): void
    {
        $dayIndex = $this->restDayToToggle ?? $this->selectedDay;
        if (!$this->weeklyProgramId) $this->saveProgram();

        ProgramPart::where('weekly_program_id', $this->weeklyProgramId)->where('day_of_week', $dayIndex)->delete();
        WeeklyProgramRestDay::updateOrCreate([
            'weekly_program_id' => $this->weeklyProgramId,
            'day_index'         => $dayIndex,
        ]);

        $this->loadExistingParts();
        $this->closeRestDayConfirmModal();
        $this->dispatch('success', 'روز استراحت با موفقیت ثبت شد.');
    }

    public function closeRestDayConfirmModal(): void
    {
        $this->showRestDayConfirmModal = false;
        $this->restDayToToggle        = null;
        $this->partsCountForRestDay   = 0;
    }

    // ==================== Class Schedule ====================
    public function openClassScheduleModal(): void
    {
        $student  = Student::find($this->studentId);
        if (!$student) return;

        $schedule = ClassSchedule::where('student_id', $student->id)->where('is_finalized', true)->latest()->first();

        if ($schedule) {
            $this->showClassScheduleModal = true;
            if (empty($this->weeklyReadingsPreview)) {
                $this->previewWeeklyReadings();
            }
        } else {
            $this->showNoScheduleModal = true;
        }
    }

    public function closeClassScheduleModal(): void { $this->showClassScheduleModal = false; }
    public function closeNoScheduleModal(): void    { $this->showNoScheduleModal    = false; }

    public function sendScheduleReminder(): void
    {
        $student = Student::with('user')->find($this->studentId);
        if (!$student || !$student->user) return;

        $notification = Notification::create([
            'title'           => 'ارسال برنامه کلاسی',
            'body'            => 'دانش‌آموز عزیز، لطفاً هرچه سریع‌تر برنامه کلاسی خود را از بخش اتاق مشاوره آپلود کنید.',
            'category'        => Notification::CATEGORY_ADVISOR,
            'target_type'     => Notification::TARGET_SINGLE,
            'admin_id'        => auth('admin')->id(),
            'student_id'      => $student->id,
            'is_from_manager' => false,
        ]);

        NotificationRecipient::create([
            'notification_id' => $notification->id,
            'user_id'         => $student->user_id,
            'is_read'         => false,
        ]);

        $this->closeNoScheduleModal();
        $this->dispatch('success', 'نوتیفیکیشن با موفقیت ارسال شد.');
    }

    protected function getClassScheduleData(): array
    {
        $student = Student::find($this->studentId);
        if (!$student) return ['schedule' => null, 'days' => [], 'todayParts' => [], 'tomorrowParts' => []];

        $schedule = ClassSchedule::where('student_id', $student->id)->where('is_finalized', true)->with('parts')->latest()->first();
        if (!$schedule) return ['schedule' => null, 'days' => [], 'todayParts' => [], 'tomorrowParts' => []];

        $days = [];
        for ($d = 0; $d < 7; $d++) {
            $days[$d] = [
                'day_of_week' => $d,
                'name'        => ClassSchedule::getDayName($d),
                'parts'       => $schedule->parts->where('day_of_week', $d)->sortBy('part_order')->values(),
            ];
        }

        $todayJalali       = jdate(Carbon::today());
        $todayDayOfWeek    = $todayJalali->getDayOfWeek();
        $tomorrowDayOfWeek = ($todayDayOfWeek + 1) % 7;

        return [
            'schedule'     => $schedule,
            'days'         => $days,
            'todayParts'   => $schedule->parts->where('day_of_week', $todayDayOfWeek)->sortBy('part_order')->values(),
            'tomorrowParts'=> $schedule->parts->where('day_of_week', $tomorrowDayOfWeek)->sortBy('part_order')->values(),
            'todayName'    => ClassSchedule::getDayName($todayDayOfWeek),
            'tomorrowName' => ClassSchedule::getDayName($tomorrowDayOfWeek),
        ];
    }

    // ==================== Exam Day ====================
    public function toggleExamDay(int $dayIndex): void
    {
        if (!$this->weeklyProgramId) $this->saveProgram();

        $weeklyProgram = WeeklyProgram::find($this->weeklyProgramId);
        if (!$weeklyProgram) return;

        if ($weeklyProgram->isExamDay($dayIndex)) {
            WeeklyProgramExamDay::where('weekly_program_id', $this->weeklyProgramId)->where('day_index', $dayIndex)->delete();
            WeeklyProgramRestDay::where('weekly_program_id', $this->weeklyProgramId)->where('day_index', $dayIndex)->delete();
            $this->loadExistingParts();
            $this->dispatch('success', 'حالت آزمون جامع برداشته شد.');
            return;
        }

        $partsCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)->where('day_of_week', $dayIndex)->count();

        if ($partsCount > 0) {
            $this->examDayToToggle      = $dayIndex;
            $this->partsCountForExamDay = $partsCount;
            $this->showExamDayConfirmModal = true;
        } else {
            $this->examDayToToggle = $dayIndex;
            $this->confirmExamDay();
        }
    }

    public function confirmExamDay(): void
    {
        $dayIndex = $this->examDayToToggle;
        if ($dayIndex === null) return;
        if (!$this->weeklyProgramId) $this->saveProgram();

        ProgramPart::where('weekly_program_id', $this->weeklyProgramId)->where('day_of_week', $dayIndex)->delete();
        WeeklyProgramRestDay::where('weekly_program_id', $this->weeklyProgramId)->where('day_index', $dayIndex)->delete();
        WeeklyProgramExamDay::updateOrCreate(['weekly_program_id' => $this->weeklyProgramId, 'day_index' => $dayIndex]);

        $this->loadExistingParts();
        $this->closeExamDayConfirmModal();
        $this->dispatch('success', 'روز آزمون جامع با موفقیت ثبت شد.');
    }

    public function closeExamDayConfirmModal(): void
    {
        $this->showExamDayConfirmModal = false;
        $this->examDayToToggle        = null;
        $this->partsCountForExamDay   = 0;
    }

    public function openExamPartModal(int $dayIndex): void
    {
        $this->selectedDay     = $dayIndex;
        $this->editingExamPartId = null;
        $this->examPartForm    = ['exam_name' => '', 'duration_minutes' => 60, 'description' => ''];
        $this->showExamPartModal = true;
    }

    public function editExamPart(int $partId): void
    {
        $part = ProgramPart::find($partId);
        if (!$part) return;

        $this->editingExamPartId = $partId;
        $this->selectedDay       = $part->day_of_week;
        $this->examPartForm = [
            'exam_name'        => $part->lesson_name,
            'duration_minutes' => $part->duration_minutes,
            'description'      => $part->description,
        ];
        $this->showExamPartModal = true;
    }

    public function closeExamPartModal(): void
    {
        $this->showExamPartModal  = false;
        $this->editingExamPartId  = null;
        $this->examPartForm       = ['exam_name' => '', 'duration_minutes' => 60, 'description' => ''];
    }

    public function saveExamPart(): void
    {
        $this->validate([
            'examPartForm.exam_name'        => 'required|string|max:255',
            'examPartForm.duration_minutes' => 'required|integer|min:1',
        ], [
            'examPartForm.exam_name.required'        => 'نام آزمون الزامی است.',
            'examPartForm.duration_minutes.required' => 'مدت زمان الزامی است.',
            'examPartForm.duration_minutes.min'      => 'مدت زمان باید حداقل ۱ دقیقه باشد.',
        ]);

        if (!$this->weeklyProgramId) $this->saveProgram();

        $partDate = Carbon::parse($this->start_date)->addDays($this->selectedDay);

        if ($this->editingExamPartId) {
            $part = ProgramPart::find($this->editingExamPartId);
            if (!$part) return;
            $part->update([
                'lesson_name'      => $this->examPartForm['exam_name'],
                'duration_minutes' => $this->examPartForm['duration_minutes'],
                'description'      => $this->examPartForm['description'],
            ]);

            ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
                ->where('day_of_week', $this->selectedDay)
                ->where('part_type', 'exam_analysis')
                ->where('part_order', $part->part_order + 1)
                ->update([
                    'lesson_name'      => 'تحلیل آزمون: ' . $this->examPartForm['exam_name'],
                    'duration_minutes' => $this->examPartForm['duration_minutes'],
                    'description'      => 'تحلیل آزمون - ' . $this->examPartForm['description'],
                ]);
        } else {
            $existingCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
                ->where('day_of_week', $this->selectedDay)->count();

            if ($existingCount >= 9) {
                $this->dispatch('warning', 'فضای کافی برای افزودن آزمون و تحلیل وجود ندارد.');
                return;
            }

            ProgramPart::create([
                'weekly_program_id' => $this->weeklyProgramId,
                'lesson_name'       => $this->examPartForm['exam_name'],
                'part_date'         => $partDate,
                'day_of_week'       => $this->selectedDay,
                'part_order'        => $existingCount + 1,
                'description'       => $this->examPartForm['description'],
                'duration_minutes'  => $this->examPartForm['duration_minutes'],
                'part_type'         => 'comprehensive_exam',
                'source_type'       => ProgramPart::SOURCE_COMPREHENSIVE_EXAM,
                'lesson_type'       => 'specialized',
            ]);

            ProgramPart::create([
                'weekly_program_id' => $this->weeklyProgramId,
                'lesson_name'       => 'تحلیل آزمون: ' . $this->examPartForm['exam_name'],
                'part_date'         => $partDate,
                'day_of_week'       => $this->selectedDay,
                'part_order'        => $existingCount + 2,
                'description'       => 'تحلیل آزمون - ' . $this->examPartForm['description'],
                'duration_minutes'  => $this->examPartForm['duration_minutes'],
                'part_type'         => 'exam_analysis',
                'source_type'       => ProgramPart::SOURCE_COMPREHENSIVE_EXAM,
                'lesson_type'       => 'specialized',
            ]);
        }

        $this->loadExistingParts();
        $this->closeExamPartModal();
        $this->dispatch('success', 'آزمون و تحلیل آزمون با موفقیت ذخیره شد.');
    }

    public function deleteExamPart(int $partId): void
    {
        $part = ProgramPart::find($partId);
        if (!$part) return;

        $dayIndex  = $part->day_of_week;
        $partOrder = $part->part_order;

        if ($part->part_type === 'comprehensive_exam') {
            ProgramPart::where('weekly_program_id', $this->weeklyProgramId)->where('day_of_week', $dayIndex)
                ->where('part_type', 'exam_analysis')->where('part_order', $partOrder + 1)->delete();
        }

        if ($part->part_type === 'exam_analysis') {
            ProgramPart::where('weekly_program_id', $this->weeklyProgramId)->where('day_of_week', $dayIndex)
                ->where('part_type', 'comprehensive_exam')->where('part_order', $partOrder - 1)->delete();
        }

        $part->delete();

        foreach (ProgramPart::where('weekly_program_id', $this->weeklyProgramId)->where('day_of_week', $dayIndex)->orderBy('part_order')->get() as $idx => $rPart) {
            $rPart->update(['part_order' => $idx + 1]);
        }

        $this->loadExistingParts();
        $this->dispatch('success', 'آزمون و تحلیل آزمون حذف شد.');
    }

    // ==================== Distribution ====================
    public function previewHomeworkDistribution(): void
    {
        if (!$this->weeklyProgramId) $this->saveProgram();

        $preSessions = AdvisingPreSession::where('student_id', $this->studentId)
            ->when($this->sessionId, fn($q) => $q->where('advising_session_id', $this->sessionId))
            ->with('assignments')->latest()->first();

        if (!$preSessions || $preSessions->assignments->isEmpty()) {
            $this->dispatch('warning', 'تکلیفی برای توزیع وجود ندارد.');
            return;
        }

        $weekDates = $this->buildWeekDates();
        $fridayIndex = collect($weekDates)->search(fn($wd) => $wd['is_friday']);
        $preview = [];

        foreach ($preSessions->assignments as $assignment) {
            $dueDate       = Carbon::parse($assignment->due_date);
            $targetDayIndex = null;

            if ($fridayIndex !== false) {
                $fridayDate = $weekDates[$fridayIndex]['date'];
                if ($dueDate->gte($fridayDate)) {
                    $targetDayIndex = $fridayIndex;
                } else {
                    $oneDayBefore = $dueDate->copy()->subDay();
                    foreach ($weekDates as $i => $wd) {
                        if ($wd['date']->isSameDay($oneDayBefore)) { $targetDayIndex = $i; break; }
                    }
                }
            } else {
                $oneDayBefore = $dueDate->copy()->subDay();
                foreach ($weekDates as $i => $wd) {
                    if ($wd['date']->isSameDay($oneDayBefore)) { $targetDayIndex = $i; break; }
                }
            }

            if ($targetDayIndex === null) {
                for ($i = 7; $i >= 0; $i--) {
                    if ($weekDates[$i]['date']->lt($dueDate)) { $targetDayIndex = $i; break; }
                }
            }

            if ($targetDayIndex === null) $targetDayIndex = 0;

            for ($p = 0; $p < $assignment->part_count; $p++) {
                $preview[] = [
                    'type'             => 'homework',
                    'subject'          => $assignment->subject,
                    'cc_subject_id'    => $assignment->cc_subject_id,
                    'day_index'        => $targetDayIndex,
                    'day_name'         => $weekDates[$targetDayIndex]['day_name'],
                    'jalali_date'      => $weekDates[$targetDayIndex]['jalali_date'],
                    'duration_minutes' => $assignment->time_per_part,
                    'description'      => 'تکلیف: ' . $assignment->subject . ' (تحویل: ' . jdate($dueDate)->format('Y/m/d') . ')',
                ];
            }
        }

        $this->distributionPreview    = $preview;
        $this->distributionType       = 'homework';
        $this->showDistributeHomeworkModal = true;
    }

    public function previewExamDistribution(): void
    {
        if (!$this->weeklyProgramId) $this->saveProgram();

        $preSessions = AdvisingPreSession::where('student_id', $this->studentId)
            ->when($this->sessionId, fn($q) => $q->where('advising_session_id', $this->sessionId))
            ->with('exams')->latest()->first();

        if (!$preSessions || $preSessions->exams->isEmpty()) {
            $this->dispatch('warning', 'امتحانی برای توزیع وجود ندارد.');
            return;
        }

        $weekDates   = $this->buildWeekDates();
        $preview     = [];
        $weeklyProg  = WeeklyProgram::find($this->weeklyProgramId);

        foreach ($preSessions->exams as $exam) {
            $examDate      = Carbon::parse($exam->exam_date);
            $availableDays = [];

            for ($i = 0; $i < 8; $i++) {
                if ($weekDates[$i]['date']->lt($examDate) && $weeklyProg && !$weeklyProg->isRestDay($i) && !$weeklyProg->isExamDay($i)) {
                    $availableDays[] = $i;
                }
            }
            if (empty($availableDays)) $availableDays = [0];

            $partsPerDay = [];
            for ($p = 0; $p < $exam->part_count; $p++) {
                $dayIdx = $availableDays[$p % count($availableDays)];
                $partsPerDay[$dayIdx] = ($partsPerDay[$dayIdx] ?? 0) + 1;
            }

            foreach ($partsPerDay as $dayIdx => $count) {
                $chapterInfo = '';
                if ($exam->cc_chapter_id && $chapter = CcChapter::find($exam->cc_chapter_id)) {
                    $chapterInfo = ' - فصل: ' . $chapter->name;
                }
                for ($c = 0; $c < $count; $c++) {
                    $preview[] = [
                        'type'             => 'exam',
                        'subject'          => $exam->subject,
                        'cc_subject_id'    => $exam->cc_subject_id,
                        'cc_chapter_id'    => $exam->cc_chapter_id ?? null,
                        'day_index'        => $dayIdx,
                        'day_name'         => $weekDates[$dayIdx]['day_name'],
                        'jalali_date'      => $weekDates[$dayIdx]['jalali_date'],
                        'duration_minutes' => $exam->time_per_part,
                        'description'      => 'مطالعه امتحان: ' . $exam->subject . $chapterInfo . ' (تاریخ امتحان: ' . jdate($examDate)->format('Y/m/d') . ')',
                    ];
                }
            }
        }

        $this->distributionPreview  = $preview;
        $this->distributionType     = 'exam';
        $this->showDistributeExamModal = true;
    }

    public function previewQaDistribution(): void
    {
        if (!$this->weeklyProgramId) $this->saveProgram();

        $preSessions = AdvisingPreSession::where('student_id', $this->studentId)
            ->when($this->sessionId, fn($q) => $q->where('advising_session_id', $this->sessionId))
            ->with('qas')->latest()->first();

        if (!$preSessions || $preSessions->qas->isEmpty()) {
            $this->dispatch('warning', 'پرسش و پاسخی برای توزیع وجود ندارد.');
            return;
        }

        $weekDates = $this->buildWeekDates();
        $preview   = [];

        foreach ($preSessions->qas as $qa) {
            $qaDate       = Carbon::parse($qa->qa_date);
            $targetDate   = $qaDate->copy()->subDay();
            $targetDayIndex = null;

            foreach ($weekDates as $i => $wd) {
                if ($wd['date']->isSameDay($targetDate)) { $targetDayIndex = $i; break; }
            }
            if ($targetDayIndex === null) {
                for ($i = 7; $i >= 0; $i--) {
                    if ($weekDates[$i]['date']->lte($targetDate)) { $targetDayIndex = $i; break; }
                }
            }
            if ($targetDayIndex === null) $targetDayIndex = 0;

            $chapterInfo = '';
            if (($qa->cc_chapter_id ?? null) && $chapter = CcChapter::find($qa->cc_chapter_id)) {
                $chapterInfo = ' - فصل: ' . $chapter->name;
            }

            for ($p = 0; $p < $qa->part_count; $p++) {
                $preview[] = [
                    'type'             => 'qa',
                    'subject'          => $qa->subject,
                    'cc_subject_id'    => $qa->cc_subject_id ?? null,
                    'cc_chapter_id'    => $qa->cc_chapter_id ?? null,
                    'day_index'        => $targetDayIndex,
                    'day_name'         => $weekDates[$targetDayIndex]['day_name'],
                    'jalali_date'      => $weekDates[$targetDayIndex]['jalali_date'],
                    'duration_minutes' => $qa->time_per_part,
                    'description'      => 'پرسش و پاسخ: ' . $qa->subject . $chapterInfo . ' (تاریخ: ' . jdate($qaDate)->format('Y/m/d') . ')',
                ];
            }
        }

        $this->distributionPreview = $preview;
        $this->distributionType    = 'qa';
        $this->showDistributeQaModal = true;
    }

    protected function buildWeekDates(): array
    {
        $startDate    = Carbon::parse($this->start_date);
        $weekDates    = [];
        $jalaliDayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];

        for ($i = 0; $i < 8; $i++) {
            $date        = $startDate->copy()->addDays($i);
            $jalaliDate  = jdate($date);
            $dayOfWeek   = $jalaliDate->getDayOfWeek();
            $weekDates[$i] = [
                'date'        => $date,
                'day_name'    => $jalaliDayNames[$dayOfWeek],
                'day_of_week' => $dayOfWeek,
                'jalali_date' => $jalaliDate->format('Y/m/d'),
                'is_friday'   => $dayOfWeek === 6,
            ];
        }

        return $weekDates;
    }

    public function applyDistribution(): void
    {
        if (empty($this->distributionPreview)) {
            $this->dispatch('warning', 'پیش‌نمایشی برای اعمال وجود ندارد.');
            return;
        }
        if (!$this->weeklyProgramId) $this->saveProgram();

        $startDate = Carbon::parse($this->start_date);

        foreach ($this->distributionPreview as $item) {
            $dayIndex      = $item['day_index'];
            $existingCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)->where('day_of_week', $dayIndex)->count();
            if ($existingCount >= 20) continue;

            $sourceType = match ($item['type']) {
                'homework' => ProgramPart::SOURCE_HOMEWORK,
                'exam'     => ProgramPart::SOURCE_EXAM,
                'qa'       => ProgramPart::SOURCE_CLASS_QA,
                default    => ProgramPart::SOURCE_NORMAL,
            };

            $subject     = !empty($item['cc_subject_id']) ? CcSubject::with('grade.educationLevel')->find($item['cc_subject_id']) : null;
            $lessonName  = $subject?->name ?? $item['subject'];
            $lessonType  = $subject?->type ?? 'specialized';
            $partType    = $item['type'] === 'exam' ? 'test' : 'descriptive';

            ProgramPart::create([
                'weekly_program_id'  => $this->weeklyProgramId,
                'lesson_name'        => $lessonName,
                'part_date'          => $startDate->copy()->addDays($dayIndex),
                'day_of_week'        => $dayIndex,
                'part_order'         => $existingCount + 1,
                'description'        => $item['description'],
                'duration_minutes'   => $item['duration_minutes'],
                'test_count'         => null,
                'part_type'          => $partType,
                'source_type'        => $sourceType,
                'lesson_type'        => $lessonType,
                'cc_subject_id'      => $item['cc_subject_id'] ?? null,
                'cc_chapter_id'      => $item['cc_chapter_id'] ?? null,
                'cc_grade_id'        => $subject?->grade?->id,
                'cc_field_id'        => $subject?->cc_field_id,
                'grade'              => $subject?->grade?->grade_number,
                'education_level_id' => $subject?->grade?->educationLevel?->id,
            ]);
        }

        $this->loadExistingParts();
        $this->closeDistributionModal();
        $this->dispatch('success', 'پارت‌ها با موفقیت در برنامه اعمال شدند.');
    }

    public function closeDistributionModal(): void
    {
        $this->showDistributeHomeworkModal = false;
        $this->showDistributeExamModal     = false;
        $this->showDistributeQaModal       = false;
        $this->distributionPreview         = [];
        $this->distributionType            = '';
    }

    // ==================== Pre-session Revert ====================
    public function isExamRegistered(int $examIndex): bool
    {
        if (!$this->weeklyProgramId) return false;
        $preSessions = AdvisingPreSession::where('student_id', $this->studentId)
            ->when($this->sessionId, fn($q) => $q->where('advising_session_id', $this->sessionId))
            ->with('exams')->latest()->first();
        if (!$preSessions || !isset($preSessions->exams[$examIndex])) return false;
        $exam = $preSessions->exams[$examIndex];
        return ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
            ->where('source_type', ProgramPart::SOURCE_EXAM)
            ->where(fn($q) => $q->where('description', 'like', '%'.$exam->subject.'%')->orWhere('lesson_name', $exam->subject))
            ->exists();
    }

    public function isQaRegistered(int $qaIndex): bool
    {
        if (!$this->weeklyProgramId) return false;
        $preSessions = AdvisingPreSession::where('student_id', $this->studentId)
            ->when($this->sessionId, fn($q) => $q->where('advising_session_id', $this->sessionId))
            ->with('qas')->latest()->first();
        if (!$preSessions || !isset($preSessions->qas[$qaIndex])) return false;
        $qa = $preSessions->qas[$qaIndex];
        return ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
            ->where('source_type', ProgramPart::SOURCE_CLASS_QA)
            ->where(fn($q) => $q->where('description', 'like', '%'.$qa->subject.'%')->orWhere('lesson_name', $qa->subject))
            ->exists();
    }

    public function isAssignmentRegistered(int $assignmentIndex): bool
    {
        if (!$this->weeklyProgramId) return false;
        $preSessions = AdvisingPreSession::where('student_id', $this->studentId)
            ->when($this->sessionId, fn($q) => $q->where('advising_session_id', $this->sessionId))
            ->with('assignments')->latest()->first();
        if (!$preSessions || !isset($preSessions->assignments[$assignmentIndex])) return false;
        $assignment = $preSessions->assignments[$assignmentIndex];
        return ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
            ->where('source_type', ProgramPart::SOURCE_HOMEWORK)
            ->where(fn($q) => $q->where('description', 'like', '%'.$assignment->subject.'%')->orWhere('lesson_name', $assignment->subject))
            ->exists();
    }

    public function revertExamParts(int $examIndex): void
    {
        if (!$this->weeklyProgramId) return;
        $preSessions = AdvisingPreSession::where('student_id', $this->studentId)
            ->when($this->sessionId, fn($q) => $q->where('advising_session_id', $this->sessionId))
            ->with('exams')->latest()->first();
        if (!$preSessions || !isset($preSessions->exams[$examIndex])) return;
        $exam = $preSessions->exams[$examIndex];
        ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
            ->where('source_type', ProgramPart::SOURCE_EXAM)
            ->where(fn($q) => $q->where('description', 'like', '%'.$exam->subject.'%')->orWhere('lesson_name', $exam->subject))
            ->delete();
        $this->reorderAllDays();
        $this->loadExistingParts();
        $this->dispatch('success', 'پارت‌های امتحان «'.$exam->subject.'» از برنامه حذف شدند.');
    }

    public function revertQaParts(int $qaIndex): void
    {
        if (!$this->weeklyProgramId) return;
        $preSessions = AdvisingPreSession::where('student_id', $this->studentId)
            ->when($this->sessionId, fn($q) => $q->where('advising_session_id', $this->sessionId))
            ->with('qas')->latest()->first();
        if (!$preSessions || !isset($preSessions->qas[$qaIndex])) return;
        $qa = $preSessions->qas[$qaIndex];
        ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
            ->where('source_type', ProgramPart::SOURCE_CLASS_QA)
            ->where(fn($q) => $q->where('description', 'like', '%'.$qa->subject.'%')->orWhere('lesson_name', $qa->subject))
            ->delete();
        $this->reorderAllDays();
        $this->loadExistingParts();
        $this->dispatch('success', 'پارت‌های پرسش و پاسخ «'.$qa->subject.'» از برنامه حذف شدند.');
    }

    public function revertAssignmentParts(int $assignmentIndex): void
    {
        if (!$this->weeklyProgramId) return;
        $preSessions = AdvisingPreSession::where('student_id', $this->studentId)
            ->when($this->sessionId, fn($q) => $q->where('advising_session_id', $this->sessionId))
            ->with('assignments')->latest()->first();
        if (!$preSessions || !isset($preSessions->assignments[$assignmentIndex])) return;
        $assignment = $preSessions->assignments[$assignmentIndex];
        ProgramPart::where('weekly_program_id', $this->weeklyProgramId)
            ->where('source_type', ProgramPart::SOURCE_HOMEWORK)
            ->where(fn($q) => $q->where('description', 'like', '%'.$assignment->subject.'%')->orWhere('lesson_name', $assignment->subject))
            ->delete();
        $this->reorderAllDays();
        $this->loadExistingParts();
        $this->dispatch('success', 'پارت‌های تکلیف «'.$assignment->subject.'» از برنامه حذف شدند.');
    }

    protected function reorderAllDays(): void
    {
        for ($i = 0; $i < 8; $i++) {
            foreach (ProgramPart::where('weekly_program_id', $this->weeklyProgramId)->where('day_of_week', $i)->orderBy('part_order')->get() as $idx => $part) {
                $part->update(['part_order' => $idx + 1]);
            }
        }
    }

    // ==================== Weekly Readings ====================
    public function previewWeeklyReadings(): void
    {
        if (!$this->weeklyProgramId) $this->saveProgram();

        $student  = Student::find($this->studentId);
        if (!$student) return;

        $schedule = ClassSchedule::where('student_id', $student->id)->where('is_finalized', true)
            ->with('parts.ccSubject')->latest()->first();

        if (!$schedule) {
            $this->dispatch('warning', 'برنامه کلاسی دانش‌آموز یافت نشد.');
            return;
        }

        $startDate    = Carbon::parse($this->start_date);
        $jalaliDayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
        $weeklyProgram = WeeklyProgram::find($this->weeklyProgramId);
        $lastReadingDurations = $this->getLastReadingDurations($student->id);
        $uniqueSubjects = [];

        for ($i = 0; $i < 8; $i++) {
            if ($weeklyProgram && ($weeklyProgram->isRestDay($i) || $weeklyProgram->isExamDay($i))) continue;

            $date        = $startDate->copy()->addDays($i);
            $jalaliDate  = jdate($date);
            $dayOfWeek   = $jalaliDate->getDayOfWeek();
            $tomorrowDow = ($dayOfWeek + 1) % 7;

            foreach ($schedule->parts->where('day_of_week', $dayOfWeek)->sortBy('part_order') as $classPart) {
                $subjectId = $classPart->cc_subject_id;
                $key       = 'daily:' . $subjectId;
                if (!isset($uniqueSubjects[$key])) {
                    $uniqueSubjects[$key] = [
                        'type'             => 'daily',
                        'subject'          => $classPart->lesson_name,
                        'cc_subject_id'    => $subjectId,
                        'duration_minutes' => $lastReadingDurations['daily'][$subjectId] ?? 0,
                        'day_indices'      => [],
                        'day_names'        => [],
                    ];
                }
                if (!in_array($i, $uniqueSubjects[$key]['day_indices'])) {
                    $uniqueSubjects[$key]['day_indices'][] = $i;
                    $uniqueSubjects[$key]['day_names'][]   = $jalaliDayNames[$dayOfWeek];
                }
            }

            foreach ($schedule->parts->where('day_of_week', $tomorrowDow)->sortBy('part_order') as $classPart) {
                $subjectId = $classPart->cc_subject_id;
                $key       = 'pre:' . $subjectId;
                if (!isset($uniqueSubjects[$key])) {
                    $uniqueSubjects[$key] = [
                        'type'             => 'pre',
                        'subject'          => $classPart->lesson_name,
                        'cc_subject_id'    => $subjectId,
                        'duration_minutes' => $lastReadingDurations['pre'][$subjectId] ?? 0,
                        'day_indices'      => [],
                        'day_names'        => [],
                    ];
                }
                if (!in_array($i, $uniqueSubjects[$key]['day_indices'])) {
                    $uniqueSubjects[$key]['day_indices'][] = $i;
                    $uniqueSubjects[$key]['day_names'][]   = $jalaliDayNames[$dayOfWeek];
                }
            }
        }

        $this->weeklyReadingsPreview = array_values($uniqueSubjects);
    }

    protected function getLastReadingDurations(int $studentId): array
    {
        $result = ['daily' => [], 'pre' => []];

        $lastHeldSession = AdvisingSession::where('student_id', $studentId)
            ->where('result_status', AdvisingSession::RESULT_HELD)
            ->when($this->sessionId, fn($q) => $q->where('id', '!=', $this->sessionId))
            ->latest()->first();

        if (!$lastHeldSession) return $result;

        $lastProgram = WeeklyProgram::where('advising_session_id', $lastHeldSession->id)->where('student_id', $studentId)->first();
        if (!$lastProgram) return $result;

        $readingParts = ProgramPart::where('weekly_program_id', $lastProgram->id)
            ->whereIn('source_type', [ProgramPart::SOURCE_DAILY_READING, ProgramPart::SOURCE_PRE_READING])
            ->whereNotNull('cc_subject_id')->where('duration_minutes', '>', 0)->get();

        foreach ($readingParts as $part) {
            $type = $part->source_type === ProgramPart::SOURCE_DAILY_READING ? 'daily' : 'pre';
            if (!isset($result[$type][$part->cc_subject_id])) {
                $result[$type][$part->cc_subject_id] = $part->duration_minutes;
            }
        }

        return $result;
    }

    public function applyWeeklyReadings(): void
    {
        if (!$this->weeklyProgramId) {
            $this->dispatch('warning', 'ابتدا باید برنامه هفتگی ایجاد شود.');
            return;
        }

        $startDate = Carbon::parse($this->start_date);

        foreach ($this->weeklyReadingsPreview as $item) {
            if ((int)$item['duration_minutes'] === 0) continue;

            $subject    = CcSubject::with('grade.educationLevel')->find($item['cc_subject_id']);
            $sourceType = $item['type'] === 'daily' ? ProgramPart::SOURCE_DAILY_READING : ProgramPart::SOURCE_PRE_READING;
            $description = ($item['type'] === 'daily' ? 'روزخوانی' : 'پیش‌خوانی') . ' - ' . $item['subject'];

            foreach ($item['day_indices'] as $dayIndex) {
                $existingCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)->where('day_of_week', $dayIndex)->count();
                if ($existingCount >= 20) continue;

                ProgramPart::create([
                    'weekly_program_id'  => $this->weeklyProgramId,
                    'lesson_name'        => $item['subject'],
                    'part_date'          => $startDate->copy()->addDays($dayIndex),
                    'day_of_week'        => $dayIndex,
                    'part_order'         => $existingCount + 1,
                    'description'        => $description,
                    'duration_minutes'   => $item['duration_minutes'],
                    'test_count'         => null,
                    'part_type'          => 'descriptive',
                    'source_type'        => $sourceType,
                    'lesson_type'        => $subject?->type ?? 'specialized',
                    'cc_subject_id'      => $item['cc_subject_id'],
                    'cc_grade_id'        => $subject?->grade?->id,
                    'cc_field_id'        => $subject?->cc_field_id,
                    'grade'              => $subject?->grade?->grade_number,
                    'education_level_id' => $subject?->grade?->educationLevel?->id,
                ]);
            }
        }

        $this->loadExistingParts();
        $this->weeklyReadingsPreview  = [];
        $this->showClassScheduleModal = false;
        $this->dispatch('success', 'روزخوانی و پیش‌خوانی هفتگی با موفقیت ثبت شد.');
    }

    public function closeWeeklyReadingsPreview(): void
    {
        $this->showWeeklyReadingsPreview = false;
        $this->weeklyReadingsPreview     = [];
    }

    public function applyOnlyDailyReadings(): void
    {
        $this->applyReadingsByType('daily');
    }

    public function applyOnlyPreReadings(): void
    {
        $this->applyReadingsByType('pre');
    }

    protected function applyReadingsByType(string $type): void
    {
        if (!$this->weeklyProgramId) $this->saveProgram();

        if (empty($this->weeklyReadingsPreview)) {
            $this->dispatch('warning', 'ابتدا پیش‌نمایش را بارگذاری کنید.');
            return;
        }

        $startDate   = Carbon::parse($this->start_date);
        $sourceType  = $type === 'daily' ? ProgramPart::SOURCE_DAILY_READING : ProgramPart::SOURCE_PRE_READING;
        $label       = $type === 'daily' ? 'روزخوانی' : 'پیش‌خوانی';
        $added       = 0;

        foreach ($this->weeklyReadingsPreview as $item) {
            if ($item['type'] !== $type || (int)$item['duration_minutes'] === 0) continue;

            $subject     = CcSubject::with('grade.educationLevel')->find($item['cc_subject_id']);
            $description = $label . ' - ' . $item['subject'];

            foreach ($item['day_indices'] as $dayIndex) {
                $existingCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)->where('day_of_week', $dayIndex)->count();
                if ($existingCount >= 20) continue;

                ProgramPart::create([
                    'weekly_program_id'  => $this->weeklyProgramId,
                    'lesson_name'        => $item['subject'],
                    'part_date'          => $startDate->copy()->addDays($dayIndex),
                    'day_of_week'        => $dayIndex,
                    'part_order'         => $existingCount + 1,
                    'description'        => $description,
                    'duration_minutes'   => $item['duration_minutes'],
                    'test_count'         => null,
                    'part_type'          => 'descriptive',
                    'source_type'        => $sourceType,
                    'lesson_type'        => $subject?->type ?? 'specialized',
                    'cc_subject_id'      => $item['cc_subject_id'],
                    'cc_grade_id'        => $subject?->grade?->id,
                    'cc_field_id'        => $subject?->cc_field_id,
                    'grade'              => $subject?->grade?->grade_number,
                    'education_level_id' => $subject?->grade?->educationLevel?->id,
                ]);
                $added++;
            }
        }

        $this->loadExistingParts();
        if ($added > 0) {
            $this->dispatch('success', $added . ' ' . $label . ' با موفقیت به برنامه اضافه شد.');
        } else {
            $this->dispatch('warning', 'هیچ ' . $label . '‌ای برای اضافه کردن وجود ندارد (یا تایم همه صفر است).');
        }
    }

    public function revertDailyReadings(): void
    {
        if (!$this->weeklyProgramId) return;
        ProgramPart::where('weekly_program_id', $this->weeklyProgramId)->where('source_type', ProgramPart::SOURCE_DAILY_READING)->delete();
        $this->loadExistingParts();
        $this->dispatch('success', 'روزخوانی‌ها از برنامه حذف شدند.');
    }

    public function revertPreReadings(): void
    {
        if (!$this->weeklyProgramId) return;
        ProgramPart::where('weekly_program_id', $this->weeklyProgramId)->where('source_type', ProgramPart::SOURCE_PRE_READING)->delete();
        $this->loadExistingParts();
        $this->dispatch('success', 'پیش‌خوانی‌ها از برنامه حذف شدند.');
    }

    public function hasDailyReadings(): bool
    {
        if (!$this->weeklyProgramId) return false;
        return ProgramPart::where('weekly_program_id', $this->weeklyProgramId)->where('source_type', ProgramPart::SOURCE_DAILY_READING)->exists();
    }

    public function hasPreReadings(): bool
    {
        if (!$this->weeklyProgramId) return false;
        return ProgramPart::where('weekly_program_id', $this->weeklyProgramId)->where('source_type', ProgramPart::SOURCE_PRE_READING)->exists();
    }

    // ==================== Exam Day Select ====================
    public function openExamDaySelect(int $examIndex): void
    {
        $preSessions = AdvisingPreSession::where('student_id', $this->studentId)
            ->when($this->sessionId, fn($q) => $q->where('advising_session_id', $this->sessionId))
            ->with('exams')->latest()->first();

        if (!$preSessions || !isset($preSessions->exams[$examIndex])) {
            $this->dispatch('warning', 'امتحان یافت نشد.');
            return;
        }

        $exam = $preSessions->exams[$examIndex];
        $this->examDaySelectData = [
            'subject'       => $exam->subject,
            'cc_subject_id' => $exam->cc_subject_id,
            'part_count'    => $exam->part_count,
            'time_per_part' => $exam->time_per_part,
            'exam_date'     => $exam->exam_date,
        ];
        $this->examDaySelectTarget    = null;
        $this->showExamDaySelectModal = true;
    }

    public function applyExamToDay(): void
    {
        if ($this->examDaySelectTarget === null || empty($this->examDaySelectData)) {
            $this->dispatch('warning', 'لطفا یک روز انتخاب کنید.');
            return;
        }
        if (!$this->weeklyProgramId) $this->saveProgram();

        $startDate = Carbon::parse($this->start_date);
        $dayIndex  = $this->examDaySelectTarget;
        $partDate  = $startDate->copy()->addDays($dayIndex);
        $data      = $this->examDaySelectData;
        $subject   = $data['cc_subject_id'] ? CcSubject::with('grade.educationLevel')->find($data['cc_subject_id']) : null;

        for ($p = 0; $p < $data['part_count']; $p++) {
            $existingCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)->where('day_of_week', $dayIndex)->count();
            ProgramPart::create([
                'weekly_program_id'  => $this->weeklyProgramId,
                'lesson_name'        => $data['subject'],
                'part_date'          => $partDate,
                'day_of_week'        => $dayIndex,
                'part_order'         => $existingCount + 1,
                'description'        => 'امتحان کلاسی: ' . $data['subject'],
                'duration_minutes'   => $data['time_per_part'],
                'test_count'         => null,
                'part_type'          => 'descriptive',
                'source_type'        => ProgramPart::SOURCE_EXAM,
                'lesson_type'        => $subject?->type ?? 'specialized',
                'cc_subject_id'      => $data['cc_subject_id'],
                'cc_grade_id'        => $subject?->grade?->id,
                'cc_field_id'        => $subject?->cc_field_id,
                'grade'              => $subject?->grade?->grade_number,
                'education_level_id' => $subject?->grade?->educationLevel?->id,
            ]);
        }

        $this->loadExistingParts();
        $this->showExamDaySelectModal = false;
        $this->examDaySelectData      = [];
        $this->examDaySelectTarget    = null;
        $this->dispatch('success', $data['part_count'] . ' پارت امتحان کلاسی با موفقیت ثبت شد.');
    }

    public function closeExamDaySelectModal(): void
    {
        $this->showExamDaySelectModal = false;
        $this->examDaySelectData      = [];
        $this->examDaySelectTarget    = null;
    }

    // ==================== Previous Program ====================
    public function openPrevProgramModal(): void
    {
        $session = AdvisingSession::find($this->sessionId);
        if (!$session) { $this->dispatch('warning', 'جلسه فعلی یافت نشد.'); return; }

        $prevSession = AdvisingSession::where('student_id', $this->studentId)
            ->where('result_status', AdvisingSession::RESULT_HELD)
            ->where('id', '!=', $this->sessionId)->latest()->first();

        if (!$prevSession) { $this->dispatch('warning', 'جلسه قبلی برگزار شده‌ای یافت نشد.'); return; }

        $prevProgram = WeeklyProgram::where('advising_session_id', $prevSession->id)
            ->with(['parts.studyPartSessions.feedback', 'parts.ccSubject', 'parts.ccChapter', 'parts.ccTopic'])->first();

        if (!$prevProgram) { $this->dispatch('warning', 'برنامه جلسه قبلی یافت نشد.'); return; }

        $this->prevSessionProgramId = $prevProgram->id;

        $this->prevSessionParts = $prevProgram->parts()
            ->whereNotIn('source_type', ['comprehensive_exam', 'exam_analysis'])
            ->orderBy('day_of_week')->orderBy('part_order')->get()
            ->map(function ($part) {
                $sessions        = $part->studyPartSessions()->with('feedback')->get();
                $feedbackRatings = $sessions->pluck('feedback.rating')->filter()->values();
                $reportRating    = $feedbackRatings->isNotEmpty() ? round($feedbackRatings->avg()) : null;

                $avgLabel = $avgColor = null;
                if ($reportRating !== null) {
                    if ($reportRating >= 8)       { $avgLabel = 'عالی';             $avgColor = 'success'; }
                    elseif ($reportRating >= 5)   { $avgLabel = 'مطالعه با کیفیت'; $avgColor = 'info'; }
                    else                          { $avgLabel = 'مطالعه بی‌کیفیت'; $avgColor = 'danger'; }
                }

                return [
                    'id'                => $part->id,
                    'lesson_name'       => $part->lesson_name,
                    'description'       => $part->description,
                    'duration_minutes'  => $part->duration_minutes,
                    'test_count'        => $part->test_count,
                    'part_type'         => $part->part_type,
                    'part_type_label'   => $part->part_type_label,
                    'source_type'       => $part->source_type,
                    'source_type_label' => $part->source_type_label,
                    'source_type_color' => $part->source_type_color,
                    'grade_label'       => $part->grade_label,
                    'day_of_week'       => $part->day_of_week,
                    'day_name'          => $part->day_name,
                    'cc_subject_id'     => $part->cc_subject_id,
                    'cc_chapter_id'     => $part->cc_chapter_id,
                    'cc_topic_id'       => $part->cc_topic_id,
                    'cc_grade_id'       => $part->cc_grade_id,
                    'cc_field_id'       => $part->cc_field_id,
                    'education_level_id'=> $part->education_level_id,
                    'grade'             => $part->grade,
                    'lesson_type'       => $part->lesson_type,
                    'lesson_type_label' => $part->lesson_type === 'general' ? 'عمومی' : 'تخصصی',
                    'report_rating'     => $reportRating,
                    'avg_rating'        => $reportRating,
                    'avg_label'         => $avgLabel,
                    'avg_color'         => $avgColor,
                    'subject_name'      => $part->ccSubject?->name ?? '',
                    'chapter_name'      => $part->ccChapter?->name ?? '',
                    'topic_name'        => $part->ccTopic?->name  ?? '',
                ];
            })->toArray();

        $this->copyingPrevPartId          = null;
        $this->copyPrevPartTargetDay      = null;
        $this->prevSelectedPartIds        = [];
        $this->prevMultiCopyTargetDaySelected = null;
        $this->copiedFromPrevPartIds      = [];
        $this->showPrevProgramModal       = true;
    }

    public function closePrevProgramModal(): void
    {
        $this->showPrevProgramModal           = false;
        $this->prevSessionParts               = [];
        $this->copyingPrevPartId              = null;
        $this->copyPrevPartTargetDay          = null;
        $this->prevSelectedPartIds            = [];
        $this->prevMultiCopyTargetDaySelected = null;
        $this->copiedFromPrevPartIds          = [];
        $this->showPrevPartInlineForm         = false;
        $this->prevPartInlineSelectedId       = null;
    }

    public function showPrevPartInlineAddForm(int $partId): void
    {
        if ($this->prevPartInlineSelectedId === $partId) {
            $this->showPrevPartInlineForm   = false;
            $this->prevPartInlineSelectedId = null;
            return;
        }

        $part  = collect($this->prevSessionParts)->firstWhere('id', $partId);
        $hours = $part ? floor($part['duration_minutes'] / 60) : 1;
        $mins  = $part ? ($part['duration_minutes'] % 60) : 0;

        $this->prevPartInlineSelectedId = $partId;
        $this->prevPartInlineForm = [
            'part_type'        => $part['part_type'] ?? 'descriptive',
            'duration_hours'   => $hours,
            'duration_minutes' => $mins,
            'day_index'        => null,
        ];
        $this->showPrevPartInlineForm = true;
    }

    public function hidePrevPartInlineAddForm(): void
    {
        $this->showPrevPartInlineForm   = false;
        $this->prevPartInlineSelectedId = null;
    }

    public function addSinglePrevPartToProgram(): void
    {
        if (!$this->prevPartInlineSelectedId) return;

        $dayIndex = $this->prevPartInlineForm['day_index'];
        if ($dayIndex === null || $dayIndex === '') {
            $this->dispatch('warning', 'لطفاً یک روز انتخاب کنید.');
            return;
        }

        $hours        = (int)($this->prevPartInlineForm['duration_hours'] ?? 0);
        $minutes      = (int)($this->prevPartInlineForm['duration_minutes'] ?? 0);
        $totalMinutes = ($hours * 60) + $minutes;

        if ($totalMinutes < 1) {
            $this->dispatch('warning', 'مدت زمان باید حداقل ۱ دقیقه باشد.');
            return;
        }

        if (!$this->weeklyProgramId) $this->saveProgram();

        $prevPart = collect($this->prevSessionParts)->firstWhere('id', $this->prevPartInlineSelectedId);
        if (!$prevPart) return;

        $existingCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)->where('day_of_week', (int)$dayIndex)->count();
        if ($existingCount >= 20) {
            $this->dispatch('warning', 'حداکثر ۲۰ پارت برای هر روز مجاز است.');
            return;
        }

        $startDate = Carbon::parse($this->start_date);
        $partType  = $this->prevPartInlineForm['part_type'] ?? $prevPart['part_type'] ?? 'descriptive';
        $testCount = in_array($partType, ['test', 'topic_exam']) ? ($prevPart['test_count'] ?: null) : null;

        $newPart = ProgramPart::create([
            'weekly_program_id'  => $this->weeklyProgramId,
            'lesson_name'        => $prevPart['lesson_name'],
            'part_date'          => $startDate->copy()->addDays((int)$dayIndex),
            'day_of_week'        => (int)$dayIndex,
            'part_order'         => $existingCount + 1,
            'description'        => $prevPart['description'],
            'duration_minutes'   => $totalMinutes,
            'test_count'         => $testCount,
            'part_type'          => $partType,
            'source_type'        => ProgramPart::SOURCE_NORMAL,
            'lesson_type'        => $prevPart['lesson_type'] ?? 'specialized',
            'grade'              => $prevPart['grade'] ?? null,
            'education_level_id' => $prevPart['education_level_id'] ?? null,
            'cc_grade_id'        => $prevPart['cc_grade_id'] ?? null,
            'cc_field_id'        => $prevPart['cc_field_id'] ?? null,
            'cc_subject_id'      => $prevPart['cc_subject_id'] ?? null,
            'cc_chapter_id'      => $prevPart['cc_chapter_id'] ?? null,
            'cc_topic_id'        => $prevPart['cc_topic_id'] ?? null,
        ]);

        $this->copiedFromPrevPartIds[$this->prevPartInlineSelectedId] = $newPart->id;
        $this->loadExistingParts();
        $this->hidePrevPartInlineAddForm();
        $this->dispatch('success', 'پارت با موفقیت به برنامه اضافه شد.');
    }

    public function togglePrevPartSelection(int $partId): void
    {
        if (in_array($partId, $this->prevSelectedPartIds)) {
            $this->prevSelectedPartIds = array_values(array_filter($this->prevSelectedPartIds, fn($id) => $id !== $partId));
        } else {
            $this->prevSelectedPartIds[] = $partId;
        }
    }

    public function addSelectedPrevParts(): void
    {
        if (empty($this->prevSelectedPartIds) || $this->prevMultiCopyTargetDaySelected === null) {
            $this->dispatch('warning', 'لطفاً پارت‌ها و روز مقصد را انتخاب کنید.');
            return;
        }
        if (!$this->weeklyProgramId) $this->saveProgram();

        $dayIndex  = (int)$this->prevMultiCopyTargetDaySelected;
        $startDate = Carbon::parse($this->start_date);
        $partDate  = $startDate->copy()->addDays($dayIndex);
        $added     = 0;

        foreach ($this->prevSelectedPartIds as $partId) {
            $part          = collect($this->prevSessionParts)->firstWhere('id', $partId);
            if (!$part) continue;
            $existingCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)->where('day_of_week', $dayIndex)->count();
            if ($existingCount >= 20) break;

            $newPart = ProgramPart::create([
                'weekly_program_id'  => $this->weeklyProgramId,
                'lesson_name'        => $part['lesson_name'],
                'part_date'          => $partDate,
                'day_of_week'        => $dayIndex,
                'part_order'         => $existingCount + 1,
                'description'        => $part['description'],
                'duration_minutes'   => $part['duration_minutes'],
                'test_count'         => $part['test_count'],
                'part_type'          => $part['part_type'],
                'source_type'        => ProgramPart::SOURCE_NORMAL,
                'lesson_type'        => $part['lesson_type'],
                'grade'              => $part['grade'],
                'education_level_id' => $part['education_level_id'],
                'cc_grade_id'        => $part['cc_grade_id'],
                'cc_field_id'        => $part['cc_field_id'],
                'cc_subject_id'      => $part['cc_subject_id'],
                'cc_chapter_id'      => $part['cc_chapter_id'],
                'cc_topic_id'        => $part['cc_topic_id'],
            ]);
            $this->copiedFromPrevPartIds[$partId] = $newPart->id;
            $added++;
        }

        $this->loadExistingParts();
        $this->prevSelectedPartIds            = [];
        $this->prevMultiCopyTargetDaySelected = null;

        $added > 0
            ? $this->dispatch('success', $added . ' پارت با موفقیت به برنامه اضافه شد.')
            : $this->dispatch('warning', 'هیچ پارتی اضافه نشد.');
    }

    public function revertAddedPrevPart(int $prevPartId): void
    {
        if (!isset($this->copiedFromPrevPartIds[$prevPartId])) return;

        ProgramPart::where('id', $this->copiedFromPrevPartIds[$prevPartId])
            ->where('weekly_program_id', $this->weeklyProgramId)->delete();

        unset($this->copiedFromPrevPartIds[$prevPartId]);
        $this->reorderAllDays();
        $this->loadExistingParts();
        $this->dispatch('success', 'پارت از برنامه حذف شد.');
    }

    public function copyPrevPartToProgram(): void
    {
        if (!$this->copyingPrevPartId || $this->copyPrevPartTargetDay === null) {
            $this->dispatch('warning', 'لطفاً پارت و روز مقصد را انتخاب کنید.');
            return;
        }

        $part = collect($this->prevSessionParts)->firstWhere('id', $this->copyingPrevPartId);
        if (!$part) return;

        if (!$this->weeklyProgramId) $this->saveProgram();

        $dayIndex      = (int)$this->copyPrevPartTargetDay;
        $startDate     = Carbon::parse($this->start_date);
        $existingCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)->where('day_of_week', $dayIndex)->count();

        if ($existingCount >= 20) {
            $this->dispatch('warning', 'حداکثر ۲۰ پارت برای هر روز مجاز است.');
            return;
        }

        ProgramPart::create([
            'weekly_program_id'  => $this->weeklyProgramId,
            'lesson_name'        => $part['lesson_name'],
            'part_date'          => $startDate->copy()->addDays($dayIndex),
            'day_of_week'        => $dayIndex,
            'part_order'         => $existingCount + 1,
            'description'        => $part['description'],
            'duration_minutes'   => $part['duration_minutes'],
            'test_count'         => $part['test_count'],
            'part_type'          => $part['part_type'],
            'source_type'        => ProgramPart::SOURCE_NORMAL,
            'lesson_type'        => $part['lesson_type'],
            'grade'              => $part['grade'],
            'education_level_id' => $part['education_level_id'],
            'cc_grade_id'        => $part['cc_grade_id'],
            'cc_field_id'        => $part['cc_field_id'],
            'cc_subject_id'      => $part['cc_subject_id'],
            'cc_chapter_id'      => $part['cc_chapter_id'],
            'cc_topic_id'        => $part['cc_topic_id'],
        ]);

        $this->loadExistingParts();
        $this->copyingPrevPartId = null;
        $this->copyPrevPartTargetDay = null;
        $this->dispatch('success', 'پارت «'.$part['lesson_name'].'» به برنامه اضافه شد.');
    }

    // ==================== Previous Report/Study ====================
    public function openPrevReportModal(string $type): void
    {
        $session = AdvisingSession::find($this->sessionId);
        if (!$session) { $this->dispatch('warning', 'جلسه فعلی یافت نشد.'); return; }

        $prevSession = AdvisingSession::where('student_id', $this->studentId)
            ->where('result_status', AdvisingSession::RESULT_HELD)
            ->where('id', '!=', $this->sessionId)->latest()->first();

        if (!$prevSession) { $this->dispatch('warning', 'جلسه قبلی برگزار شده‌ای یافت نشد.'); return; }

        $this->prevReportType = $type;
        $this->prevReportData = [];

        if ($type === 'report') {
            $prevProgram     = WeeklyProgram::where('advising_session_id', $prevSession->id)->first();
            $programStartDate = $prevProgram?->start_date ? Carbon::parse($prevProgram->start_date) : null;
            $programEndDate   = $programStartDate?->copy()->addDays(7);

            $programDates = collect();
            if ($programStartDate && $programEndDate) {
                $d = $programStartDate->copy();
                while ($d->lessThan($programEndDate)) {
                    $programDates->push($d->toDateString());
                    $d->addDay();
                }
            }

            $reports    = DailyReport::where('student_id', $this->studentId)
                ->where('session_id', $prevSession->id)
                ->with(['detail', 'reportParts.programPart'])->orderBy('report_date')->get();

            $sentDates    = $reports->pluck('report_date')->map(fn($d) => Carbon::parse($d)->toDateString())->toArray();
            $missingDates = $programDates->filter(fn($d) => !in_array($d, $sentDates))->values();

            $reportItems = $reports->map(function ($report) use ($prevProgram) {
                $reportPartsCount = $report->reportParts->count();
                $donePartsCount   = $report->reportParts->where('is_read', true)->count();
                $totalPlanned     = $reportPartsCount;

                if ($prevProgram) {
                    $startDate    = Carbon::parse($prevProgram->start_date);
                    $dayIndex     = $startDate->diffInDays(Carbon::parse($report->report_date));
                    $totalPlanned = ProgramPart::where('weekly_program_id', $prevProgram->id)
                        ->where('day_of_week', $dayIndex)
                        ->whereNotIn('part_type', ['comprehensive_exam', 'exam_analysis'])->count();
                    if ($totalPlanned === 0) $totalPlanned = $reportPartsCount;
                }

                $status = $report->detail?->status ?? 'pending';
                return [
                    'id'           => $report->id,
                    'date'         => $report->report_date ? jdate($report->report_date)->format('Y/m/d') : '',
                    'day_name'     => $report->report_date ? $this->getJalaliDayName($report->report_date) : '',
                    'content'      => $report->detail?->description ?? '',
                    'rating'       => $report->detail?->rating ?? null,
                    'status'       => $status,
                    'status_label' => match ($status) { 'approved' => 'تایید شده', 'rejected' => 'رد شده', default => 'در انتظار' },
                    'status_color' => match ($status) { 'approved' => 'success', 'rejected' => 'danger', default => 'warning' },
                    'parts_done'   => $donePartsCount,
                    'parts_total'  => $totalPlanned,
                    'missed_reason'=> $report->detail?->missed_parts_reason ?? null,
                    'is_sent'      => true,
                ];
            })->toArray();

            $missingDayItems = $missingDates->map(fn($dateStr) => [
                'date'     => jdate($dateStr)->format('Y/m/d'),
                'day_name' => $this->getJalaliDayName($dateStr),
                'is_sent'  => false,
            ])->toArray();

            // ترکیب ارسال شده و نشده در یک لیست (مرتب‌سازی بر اساس تاریخ)
            $allItems = array_merge($reportItems, $missingDayItems);
            usort($allItems, fn($a, $b) => strcmp($a['date'], $b['date']));

            $this->prevReportData = [
                'session_date'        => $prevSession->activation_date ? jdate($prevSession->activation_date)->format('Y/m/d') : '',
                'result_status'       => 'برگزار شده',
                'items'               => $allItems,
                'type_label'          => 'گزارش فعالیت روزانه',
                'sent_days_count'     => count($reportItems),
                'not_sent_days_count' => $missingDates->count(),
            ];
        } elseif ($type === 'study') {
            $prevProgram = WeeklyProgram::where('advising_session_id', $prevSession->id)->first();
            if (!$prevProgram) { $this->dispatch('warning', 'برنامه جلسه قبلی یافت نشد.'); return; }

            $totalAssignedParts = ProgramPart::where('weekly_program_id', $prevProgram->id)
                ->whereNotIn('part_type', ['comprehensive_exam', 'exam_analysis'])->count();

            // همه پارت‌های برنامه (شامل ثبت نشده)
            $allProgramParts = ProgramPart::where('weekly_program_id', $prevProgram->id)
                ->whereNotIn('part_type', ['comprehensive_exam', 'exam_analysis'])
                ->orderBy('day_of_week')->orderBy('part_order')->get();

            $studySessionsCollection = StudyPartSession::where('weekly_program_id', $prevProgram->id)
                ->where('student_id', $this->studentId)
                ->with(['programPart', 'feedback'])->orderBy('started_at')->get();

            $registeredPartIds = $studySessionsCollection->pluck('program_part_id')->unique()->toArray();
            $doneParts         = $studySessionsCollection->where('is_completed', true)->groupBy('program_part_id')->count();

            $studySessions = $studySessionsCollection->map(function ($session) {
                $durationSeconds      = $session->duration_seconds ?? 0;
                $durationMinutes      = (int)round($durationSeconds / 60);
                $hours                = floor($durationMinutes / 60);
                $mins                 = $durationMinutes % 60;
                $durationLabel        = $hours > 0 ? ($hours . ' ساعت ' . ($mins > 0 ? $mins . ' دقیقه' : '')) : ($mins . ' دقیقه');

                $plannedMinutes      = $session->programPart?->duration_minutes ?? 0;
                $actualElapsedMinutes = 0;
                $isSuspicious         = false;

                if ($session->started_at && $session->ended_at) {
                    $actualElapsedMinutes = (int)round($session->started_at->diffInSeconds($session->ended_at) / 60);
                    if ($plannedMinutes > 0 && $actualElapsedMinutes > ($plannedMinutes * 2)) {
                        $isSuspicious = true;
                    }
                }

                return [
                    'id'                    => $session->id,
                    'program_part_id'       => $session->program_part_id,
                    'date'                  => $session->started_at ? jdate($session->started_at)->format('Y/m/d') : '',
                    'day_name'              => $session->started_at ? $this->getJalaliDayName($session->started_at) : '',
                    'day_of_week'           => $session->programPart?->day_of_week ?? null,
                    'subject'               => $session->programPart?->lesson_name ?? '-',
                    'subject_description'   => $session->programPart?->description ?? '',
                    'duration_minutes'      => $durationMinutes,
                    'duration_label'        => $durationLabel,
                    'planned_minutes'       => $plannedMinutes,
                    'actual_elapsed_minutes'=> $actualElapsedMinutes,
                    'is_suspicious'         => $isSuspicious,
                    'rating'                => $session->feedback?->rating ?? null,
                    'feedback'              => $session->feedback?->comment ?? null,
                    'has_feedback'          => $session->feedback !== null,
                    'is_completed'          => (bool)$session->is_completed,
                    'started_at'            => $session->started_at?->format('H:i'),
                    'ended_at'              => $session->ended_at?->format('H:i'),
                    'grade_label'           => $session->programPart?->grade_label ?? '',
                    'lesson_type'           => $session->programPart?->lesson_type ?? 'specialized',
                    'lesson_type_label'     => $session->programPart?->lesson_type === 'general' ? 'عمومی' : 'تخصصی',
                    'is_registered'         => true,
                ];
            })->toArray();

            // پارت‌های ثبت نشده
            $unregisteredParts = $allProgramParts->filter(fn($p) => !in_array($p->id, $registeredPartIds))
                ->map(fn($part) => [
                    'id'               => null,
                    'program_part_id'  => $part->id,
                    'date'             => '',
                    'day_name'         => $part->day_name ?? '',
                    'day_of_week'      => $part->day_of_week,
                    'subject'          => $part->lesson_name,
                    'subject_description' => $part->description ?? '',
                    'duration_minutes' => 0,
                    'duration_label'   => 'ثبت نشده',
                    'planned_minutes'  => $part->duration_minutes,
                    'is_suspicious'    => false,
                    'rating'           => null,
                    'feedback'         => null,
                    'has_feedback'     => false,
                    'is_completed'     => false,
                    'started_at'       => null,
                    'ended_at'         => null,
                    'grade_label'      => $part->grade_label ?? '',
                    'lesson_type'      => $part->lesson_type ?? 'specialized',
                    'lesson_type_label'=> $part->lesson_type === 'general' ? 'عمومی' : 'تخصصی',
                    'is_registered'    => false,
                ])->toArray();

            $allStudyItems = array_merge($studySessions, $unregisteredParts);

            $totalMinutes = collect($studySessions)->sum('duration_minutes');
            $totalHours   = floor($totalMinutes / 60);
            $totalMins    = $totalMinutes % 60;
            $totalLabel   = $totalHours > 0 ? ($totalHours . ' ساعت ' . ($totalMins > 0 ? $totalMins . ' دقیقه' : '')) : ($totalMins . ' دقیقه');

            $this->prevReportData = [
                'session_date'          => $prevSession->activation_date ? jdate($prevSession->activation_date)->format('Y/m/d') : '',
                'result_status'         => 'برگزار شده',
                'items'                 => $allStudyItems,
                'total_label'           => $totalLabel,
                'type_label'            => 'ساعت مطالعه پارت‌ها',
                'total_assigned_parts'  => $totalAssignedParts,
                'total_done_parts'      => $doneParts,
            ];
        }

        $this->showPrevReportModal = true;
    }

    public function closePrevReportModal(): void
    {
        $this->showPrevReportModal = false;
        $this->prevReportData      = [];
        $this->prevReportType      = '';
    }

    protected function getJalaliDayName($date): string
    {
        $jalaliDayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
        return $jalaliDayNames[jdate($date)->getDayOfWeek()] ?? '';
    }

    // ==================== Copy / Cut / Select ====================
    public function togglePartSelectMode(bool $cut = false): void
    {
        if ($this->partSelectMode && $this->cutMode === $cut) {
            $this->partSelectMode  = false;
            $this->cutMode         = false;
            $this->selectedPartIds = [];
            $this->copyTargetDay   = null;
            $this->copyTargetDays  = [];
        } else {
            $this->partSelectMode  = true;
            $this->cutMode         = $cut;
            $this->selectedPartIds = [];
            $this->copyTargetDay   = null;
            $this->copyTargetDays  = [];
        }
    }

    public function togglePartSelection(int $partId): void
    {
        if (in_array($partId, $this->selectedPartIds)) {
            $this->selectedPartIds = array_values(array_filter($this->selectedPartIds, fn($id) => $id !== $partId));
        } else {
            $this->selectedPartIds[] = $partId;
        }
    }

    public function clearPartSelection(): void
    {
        $this->selectedPartIds = [];
        $this->copyTargetDay   = null;
        $this->partSelectMode  = false;
        $this->cutMode         = false;
    }

    public function copySelectedParts(): void
    {
        $targetDays = !empty($this->copyTargetDays)
            ? array_map('intval', $this->copyTargetDays)
            : ($this->copyTargetDay !== null ? [(int)$this->copyTargetDay] : []);

        if (empty($this->selectedPartIds) || empty($targetDays)) {
            $this->dispatch('warning', 'لطفاً پارت‌ها و حداقل یک روز مقصد را انتخاب کنید.');
            return;
        }
        if (!$this->weeklyProgramId) $this->saveProgram();

        $startDate = Carbon::parse($this->start_date);
        $copied    = 0;

        foreach ($targetDays as $dayIndex) {
            foreach ($this->selectedPartIds as $partId) {
                $part          = ProgramPart::find($partId);
                if (!$part) continue;
                $existingCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)->where('day_of_week', $dayIndex)->count();
                if ($existingCount >= 20) break;

                ProgramPart::create([
                    'weekly_program_id'  => $this->weeklyProgramId,
                    'lesson_name'        => $part->lesson_name,
                    'part_date'          => $startDate->copy()->addDays($dayIndex),
                    'day_of_week'        => $dayIndex,
                    'part_order'         => $existingCount + 1,
                    'description'        => $part->description,
                    'duration_minutes'   => $part->duration_minutes,
                    'test_count'         => $part->test_count,
                    'part_type'          => $part->part_type,
                    'source_type'        => ProgramPart::SOURCE_NORMAL,
                    'lesson_type'        => $part->lesson_type,
                    'grade'              => $part->grade,
                    'education_level_id' => $part->education_level_id,
                    'cc_grade_id'        => $part->cc_grade_id,
                    'cc_field_id'        => $part->cc_field_id,
                    'cc_subject_id'      => $part->cc_subject_id,
                    'cc_chapter_id'      => $part->cc_chapter_id,
                    'cc_topic_id'        => $part->cc_topic_id,
                ]);
                $copied++;
            }
        }

        $this->loadExistingParts();
        $this->selectedPartIds = [];
        $this->copyTargetDay   = null;
        $this->copyTargetDays  = [];
        $this->partSelectMode  = false;

        $copied > 0
            ? $this->dispatch('success', $copied . ' پارت با موفقیت کپی شد.')
            : $this->dispatch('warning', 'هیچ پارتی کپی نشد.');
    }

    public function cutSelectedParts(): void
    {
        $targetDays = [];
        if (!empty($this->copyTargetDays)) {
            $targetDays = array_map('intval', (array)$this->copyTargetDays);
        } elseif ($this->copyTargetDay !== null && $this->copyTargetDay !== '') {
            $targetDays = [(int)$this->copyTargetDay];
        }

        if (empty($this->selectedPartIds) || empty($targetDays)) {
            $this->dispatch('warning', 'لطفاً پارت‌ها و حداقل یک روز مقصد را انتخاب کنید.');
            return;
        }
        if (!$this->weeklyProgramId) $this->saveProgram();

        $startDate = Carbon::parse($this->start_date);
        $dayIndex  = $targetDays[0];
        $moved     = 0;

        foreach ($this->selectedPartIds as $partId) {
            $part = ProgramPart::find($partId);
            if (!$part || $part->weekly_program_id !== $this->weeklyProgramId) continue;

            $existingCount = ProgramPart::where('weekly_program_id', $this->weeklyProgramId)->where('day_of_week', $dayIndex)->count();
            if ($existingCount >= 20) break;

            ProgramPart::create([
                'weekly_program_id'  => $this->weeklyProgramId,
                'lesson_name'        => $part->lesson_name,
                'part_date'          => $startDate->copy()->addDays($dayIndex),
                'day_of_week'        => $dayIndex,
                'part_order'         => $existingCount + 1,
                'description'        => $part->description,
                'duration_minutes'   => $part->duration_minutes,
                'test_count'         => $part->test_count,
                'part_type'          => $part->part_type,
                'source_type'        => $part->source_type,
                'lesson_type'        => $part->lesson_type,
                'grade'              => $part->grade,
                'education_level_id' => $part->education_level_id,
                'cc_grade_id'        => $part->cc_grade_id,
                'cc_field_id'        => $part->cc_field_id,
                'cc_subject_id'      => $part->cc_subject_id,
                'cc_chapter_id'      => $part->cc_chapter_id,
                'cc_topic_id'        => $part->cc_topic_id,
                'grade_label'        => $part->grade_label,
            ]);

            $part->delete();
            $moved++;
        }

        $this->reorderAllDays();
        $this->loadExistingParts();
        $this->selectedPartIds = [];
        $this->copyTargetDay   = null;
        $this->copyTargetDays  = [];
        $this->partSelectMode  = false;
        $this->cutMode         = false;

        $moved > 0
            ? $this->dispatch('success', $moved . ' پارت با موفقیت جابه‌جا (کات) شد.')
            : $this->dispatch('warning', 'هیچ پارتی جابه‌جا نشد.');
    }

    // ==================== Bulk Delete ====================
    public function toggleBulkDeleteMode(): void
    {
        $this->bulkDeleteMode        = !$this->bulkDeleteMode;
        $this->bulkDeleteSelectedIds = [];
        if ($this->bulkDeleteMode && $this->partSelectMode) {
            $this->partSelectMode  = false;
            $this->selectedPartIds = [];
            $this->cutMode         = false;
            $this->copyTargetDays  = [];
        }
    }

    public function toggleBulkDeleteSelection(int $partId): void
    {
        if (in_array($partId, $this->bulkDeleteSelectedIds)) {
            $this->bulkDeleteSelectedIds = array_values(array_filter($this->bulkDeleteSelectedIds, fn($id) => $id !== $partId));
        } else {
            $this->bulkDeleteSelectedIds[] = $partId;
        }
    }

    public function openBulkDeleteConfirm(): void
    {
        if (empty($this->bulkDeleteSelectedIds)) {
            $this->dispatch('warning', 'حداقل یک پارت انتخاب کنید.');
            return;
        }
        $this->showBulkDeleteConfirmModal = true;
    }

    public function closeBulkDeleteConfirmModal(): void
    {
        $this->showBulkDeleteConfirmModal = false;
    }

    public function executeBulkDelete(): void
    {
        if (empty($this->bulkDeleteSelectedIds) || !$this->weeklyProgramId) return;

        $count = count($this->bulkDeleteSelectedIds);
        ProgramPart::whereIn('id', $this->bulkDeleteSelectedIds)->where('weekly_program_id', $this->weeklyProgramId)->delete();

        $this->reorderAllDays();
        $this->loadExistingParts();
        $this->bulkDeleteSelectedIds      = [];
        $this->showBulkDeleteConfirmModal = false;
        $this->bulkDeleteMode             = false;

        $this->dispatch('success', $count . ' پارت با موفقیت حذف شد.');
    }
    public function getFilteredPrevParts(): array
    {
        $collection = collect($this->prevSessionParts);

        // فیلتر پایه
        if ($this->prevProgramFilterGrade !== '') {
            $collection = $collection->filter(fn($p) => (string)($p['grade'] ?? '') === $this->prevProgramFilterGrade);
        }

        // فیلتر نوع
        if ($this->prevProgramFilterType === 'general') {
            $collection = $collection->filter(fn($p) => $p['lesson_type'] === 'general');
        } elseif ($this->prevProgramFilterType === 'specialized') {
            $collection = $collection->filter(fn($p) => $p['lesson_type'] === 'specialized');
        }

        // فیلتر روز
        if ($this->prevProgramFilterDay !== '') {
            $collection = $collection->filter(fn($p) => (string)($p['day_of_week'] ?? '') === $this->prevProgramFilterDay);
        }

        // مرتب‌سازی
        return match ($this->prevProgramSortField) {
            'rating_asc'  => $collection->sortBy('avg_rating')->values()->toArray(),
            'rating_desc' => $collection->sortByDesc('avg_rating')->values()->toArray(),
            default       => $collection->sortBy('day_of_week')->values()->toArray(),
        };
    }
    public function updatedReadingTypeFilter(): void {} // trigger re-render
    public function getFilteredStudyItems(): array
    {
        if ($this->prevReportType !== 'study' || empty($this->prevReportData['items'])) return [];

        $collection = collect($this->prevReportData['items']);

        if ($this->prevStudyFilterTypeField === 'general') {
            $collection = $collection->filter(fn($i) => ($i['lesson_type'] ?? '') === 'general');
        } elseif ($this->prevStudyFilterTypeField === 'specialized') {
            $collection = $collection->filter(fn($i) => ($i['lesson_type'] ?? '') === 'specialized');
        }

        if ($this->prevStudyFilterDayField !== '') {
            $collection = $collection->filter(fn($i) => (string)($i['day_of_week'] ?? '') === $this->prevStudyFilterDayField);
        }

        return match ($this->prevStudySortField) {
            'rating_desc'    => $collection->sortByDesc('rating')->values()->toArray(),
            'rating_asc'     => $collection->sortBy('rating')->values()->toArray(),
            'not_registered' => $collection->sortBy('is_registered')->values()->toArray(),
            default          => $collection->sortBy('day_of_week')->values()->toArray(),
        };
    }
    // ==================== Render ====================
    public function render()
    {
        $student = Student::with(['user.personalInformation', 'advisor', 'supporter'])->find($this->studentId);
        $weeklyProgram = $this->weeklyProgramId ? WeeklyProgram::with('parts')->find($this->weeklyProgramId) : null;

        $educationLevels = EducationLevel::active()->ordered()->get();

        $weekDays       = [];
        $jalaliDayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
        $startDate      = $this->start_date ? Carbon::parse($this->start_date) : Carbon::tomorrow();

        for ($i = 0; $i < 8; $i++) {
            $date        = $startDate->copy()->addDays($i);
            $jalaliDate  = jdate($date);
            $dayOfWeek   = $jalaliDate->getDayOfWeek();

            $dayParts    = $weeklyProgram
                ? $weeklyProgram->parts()->where('day_of_week', $i)->orderBy('part_order')->get()
                : collect();

            $isRestDay = $weeklyProgram ? $weeklyProgram->isRestDay($i) : false;
            $isExamDay = $weeklyProgram ? $weeklyProgram->isExamDay($i) : false;

            $weekDays[] = [
                'index'       => $i,
                'name'        => $jalaliDayNames[$dayOfWeek],
                'date'        => $date,
                'jalali_date' => $jalaliDate->format('Y/m/d'),
                'parts'       => $dayParts,
                'total_hours' => round($dayParts->sum('duration_minutes') / 60, 1),
                'total_tests' => $dayParts->sum('test_count') ?? 0,
                'is_rest_day' => $isRestDay,
                'is_exam_day' => $isExamDay,
            ];
        }

        $preSessions = AdvisingPreSession::where('student_id', $this->studentId)
            ->when($this->sessionId, fn($q) => $q->where('advising_session_id', $this->sessionId))
            ->with(['advisingSession', 'exams', 'assignments', 'qas', 'miscellaneous', 'requestedParts'])
            ->latest()->get();

        $classScheduleData = $this->getClassScheduleData();

        return view('livewire.admin.student.consultation.weekly-program-upload', [
            'student'           => $student,
            'educationLevels'   => $educationLevels,
            'weeklyProgram'     => $weeklyProgram,
            'weekDays'          => $weekDays,
            'preSessions'       => $preSessions,
            'advisorName'       => $student->advisor?->name ?? '-',
            'supporterName'     => $student->supporter?->name ?? '-',
            'classScheduleData' => $classScheduleData,
            'filteredPrevParts' => $this->getFilteredPrevParts(),
            'filteredStudyItems' => $this->getFilteredStudyItems(),
        ])->layout('layouts.admin.app');
    }
}
