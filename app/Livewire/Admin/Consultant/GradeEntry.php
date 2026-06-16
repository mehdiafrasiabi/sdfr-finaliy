<?php

namespace App\Livewire\Admin\Consultant;

use App\Exports\SchoolGradesTemplateExport;
use App\Imports\SchoolGradesImport;
use App\Models\CcField;
use App\Models\CcGrade;
use App\Models\CcSubject;
use App\Models\SchoolStudentGrade;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Morilog\Jalali\Jalalian;

/**
 * ثبت نمرات کارنامهٔ ماهانه توسط مشاور برای دانش‌آموزانِ تحت مشاورهٔ خودش.
 *  - انتخاب ماه شمسی (هر ماه نمرهٔ مخصوص خودش)
 *  - انتخاب دانش‌آموز از میان advisedStudents
 *  - برای هر درسِ پایه+رشتهٔ دانش‌آموز: فعالیت کلاسی /۲۰، امتحان /۲۰، نظر دبیر (اختیاری)
 *  - خروجی/ورودی اکسل برای ثبت گروهی
 */
class GradeEntry extends Component
{
    use WithFileUploads;

    public int $jalaliYear;
    public int $jalaliMonth;

    public ?int $selectedStudentId = null;

    /** @var array<int,array{class_activity:string,exam:string,teacher_comment:string}> keyed by cc_subject_id */
    public array $rows = [];

    public array $allowedGradeIds = [];
    public ?int $studentFieldId = null;

    public $importFile = null;
    public array $importInvalidRows = [];

    public function mount(): void
    {
        $admin = auth('admin')->user();
        abort_unless($admin?->hasRole('مشاور تحصیلی') || $admin?->hasRole('super admin'), 403);

        $now = Jalalian::now();
        $this->jalaliYear  = (int) $now->getYear();
        $this->jalaliMonth = (int) $now->getMonth();
    }

    public function jalaliMonthKey(): string
    {
        return sprintf('%04d-%02d', $this->jalaliYear, $this->jalaliMonth);
    }

    private function advisorId(): ?int
    {
        return auth('admin')->id();
    }

    private function findAdvisedStudent(int $studentId): Student
    {
        return Student::where('advisor_id', $this->advisorId())->findOrFail($studentId);
    }

    public function updatedJalaliYear(): void
    {
        $this->loadExistingGrades();
    }

    public function updatedJalaliMonth(): void
    {
        $this->loadExistingGrades();
    }

    public function selectStudent(int $studentId): void
    {
        $student = $this->findAdvisedStudent($studentId);
        $this->selectedStudentId = $student->id;
        $this->resolveSubjectScope($student);
        $this->loadExistingGrades();
    }

    private function resolveSubjectScope(Student $student): void
    {
        $gradeNum = (int) $student->grade;

        $this->studentFieldId = null;
        if ($student->field) {
            $field = CcField::where('slug', $student->field)->orWhere('name', $student->field)->first();
            $this->studentFieldId = $field?->id;
        }

        $q = CcGrade::where('is_active', true)->where('grade_number', '<=', $gradeNum);
        if ($this->studentFieldId) {
            $q->where(function ($q) {
                $q->where('cc_field_id', $this->studentFieldId)->orWhereNull('cc_field_id');
            });
        }
        $this->allowedGradeIds = $q->pluck('id')->toArray();
    }

    private function subjectsForSelectedStudent()
    {
        if (!$this->selectedStudentId) {
            return collect();
        }

        return CcSubject::whereIn('cc_grade_id', $this->allowedGradeIds ?: [0])
            ->when($this->studentFieldId, function ($q) {
                $q->where(function ($q) {
                    $q->where('cc_field_id', $this->studentFieldId)->orWhereNull('cc_field_id');
                });
            })
            ->orderBy('cc_grade_id')->orderBy('order')->get();
    }

    private function loadExistingGrades(): void
    {
        $this->rows = [];

        if (!$this->selectedStudentId) {
            return;
        }

        $existing = SchoolStudentGrade::where('student_id', $this->selectedStudentId)
            ->where('jalali_month', $this->jalaliMonthKey())
            ->get()
            ->keyBy('cc_subject_id');

        foreach ($this->subjectsForSelectedStudent() as $subject) {
            $g = $existing->get($subject->id);
            $this->rows[$subject->id] = [
                'class_activity'  => $g && $g->class_activity !== null ? (string) (float) $g->class_activity : '',
                'exam'            => $g && $g->exam !== null ? (string) (float) $g->exam : '',
                'teacher_comment' => $g?->teacher_comment ?? '',
            ];
        }
    }

    public function save(): void
    {
        $student = $this->findAdvisedStudent((int) $this->selectedStudentId);
        $month   = $this->jalaliMonthKey();

        $subjectIds = $this->subjectsForSelectedStudent()->pluck('id')->all();

        $saved = 0;
        foreach ($this->rows as $subjectId => $row) {
            if (!in_array((int) $subjectId, $subjectIds, true)) {
                continue;
            }

            $activity = trim((string) ($row['class_activity'] ?? ''));
            $exam     = trim((string) ($row['exam'] ?? ''));
            $comment  = trim((string) ($row['teacher_comment'] ?? ''));

            // ردیف کاملاً خالی نادیده گرفته می‌شود.
            if ($activity === '' && $exam === '' && $comment === '') {
                continue;
            }

            Validator::make(
                ['class_activity' => $activity ?: null, 'exam' => $exam ?: null],
                [
                    'class_activity' => 'nullable|numeric|min:0|max:20',
                    'exam'           => 'nullable|numeric|min:0|max:20',
                ],
                [
                    'class_activity.max' => 'نمرهٔ فعالیت کلاسی نباید بیشتر از ۲۰ باشد',
                    'exam.max'           => 'نمرهٔ امتحان نباید بیشتر از ۲۰ باشد',
                    '*.numeric'          => 'نمره باید عدد باشد',
                ]
            )->validate();

            SchoolStudentGrade::updateOrCreate(
                [
                    'student_id'    => $student->id,
                    'cc_subject_id' => (int) $subjectId,
                    'jalali_month'  => $month,
                ],
                [
                    'class_activity'       => $activity !== '' ? $activity : null,
                    'exam'                 => $exam !== '' ? $exam : null,
                    'teacher_comment'      => $comment !== '' ? $comment : null,
                    'recorded_by_admin_id' => $this->advisorId(),
                    'score'                => $exam !== '' ? $exam : ($activity !== '' ? $activity : 0),
                    'scale'                => '20',
                    'recorded_at'          => now()->toDateString(),
                ]
            );
            $saved++;
        }

        $this->dispatch('success', $saved > 0 ? "نمرات ثبت شد ({$saved} درس)" : 'نمره‌ای برای ثبت وجود نداشت');
    }

    public function exportTemplate()
    {
        $month = $this->jalaliMonthKey();
        $name  = 'school-grades-' . $month . '.xlsx';

        return Excel::download(
            new SchoolGradesTemplateExport($this->advisorId(), $month),
            $name
        );
    }

    public function importGrades(): void
    {
        $this->validate([
            'importFile' => 'required|file|mimes:xlsx,xls,csv',
        ], [], ['importFile' => 'فایل اکسل']);

        $import = new SchoolGradesImport($this->advisorId(), $this->jalaliMonthKey());
        Excel::import($import, $this->importFile->getRealPath());
        $count = $import->save();

        $this->importInvalidRows = $import->invalidRows;
        $this->reset('importFile');

        if ($this->selectedStudentId) {
            $this->loadExistingGrades();
        }

        $this->dispatch('success', "ثبت گروهی انجام شد ({$count} نمره)");
    }

    public function render()
    {
        $students = Student::with('user')
            ->where('advisor_id', $this->advisorId())
            ->whereNotNull('grade')
            ->get();

        $selectedStudent = $this->selectedStudentId
            ? Student::with('user')->find($this->selectedStudentId)
            : null;

        return view('livewire.admin.consultant.grade-entry', [
            'students'        => $students,
            'subjects'        => $this->subjectsForSelectedStudent(),
            'selectedStudent' => $selectedStudent,
            'monthNames'      => \App\Models\SmartReportCard::MONTH_NAMES,
            'yearOptions'     => range($this->jalaliYear, $this->jalaliYear - 2),
        ])->layout('layouts.admin.app');
    }
}
