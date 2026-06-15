<?php

namespace App\Livewire\SchoolManager;

use App\Models\CcChapter;
use App\Models\CcField;
use App\Models\CcGrade;
use App\Models\CcSubject;
use App\Models\SchoolStudentGrade;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

/**
 * ثبت نمرات ماهانه توسط مدیر مدرسه:
 *  ۱) انتخاب پایه از میان پایه‌های دانش‌آموزان مدرسه
 *  ۲) انتخاب دانش‌آموز آن پایه
 *  ۳) ثبت نمره (درس/فصل/نمره/مقیاس) — بازاستفاده از منطق Admin\SchoolSupporter\StudentGrades
 */
class GradeEntry extends Component
{
    public string $selectedGrade = '';
    public ?int $selectedStudentId = null;

    // فرم نمره
    public ?int $cc_subject_id = null;
    public ?int $cc_chapter_id = null;
    public string $score = '';
    public string $scale = '20';
    public string $note = '';
    public string $recorded_at = '';

    public array $allowedGradeIds = [];
    public ?int $studentFieldId = null;

    public function mount(): void
    {
        $this->recorded_at = now()->toDateString();
    }

    private function school()
    {
        return auth('school-manager')->user()?->school;
    }

    public function selectGrade(string $grade): void
    {
        $this->selectedGrade = $grade;
        $this->reset(['selectedStudentId', 'cc_subject_id', 'cc_chapter_id', 'score', 'note', 'allowedGradeIds', 'studentFieldId']);
        $this->scale = '20';
    }

    public function selectStudent(int $studentId): void
    {
        $school = $this->school();
        $student = Student::where('school_id', $school?->id)->findOrFail($studentId);

        $this->selectedStudentId = $student->id;
        $this->reset(['cc_subject_id', 'cc_chapter_id', 'score', 'note']);
        $this->scale = '20';
        $this->recorded_at = now()->toDateString();

        // فیلتر پایه و رشته (مطابق Admin\SchoolSupporter\StudentGrades)
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

    public function save(): void
    {
        $school = $this->school();
        $student = Student::where('school_id', $school?->id)->findOrFail($this->selectedStudentId);

        $max = $this->scale === '20' ? 20 : 100;

        Validator::make([
            'cc_subject_id' => $this->cc_subject_id,
            'cc_chapter_id' => $this->cc_chapter_id,
            'score'         => $this->score,
            'scale'         => $this->scale,
            'note'          => $this->note,
            'recorded_at'   => $this->recorded_at,
        ], [
            'cc_subject_id' => 'required|exists:cc_subjects,id',
            'cc_chapter_id' => 'nullable|exists:cc_chapters,id',
            'score'         => "required|numeric|min:0|max:{$max}",
            'scale'         => 'required|in:20,100',
            'note'          => 'nullable|string|max:255',
            'recorded_at'   => 'required|date',
        ], [
            '*.required' => 'فیلد ضروری است',
            'score.max'  => "نمره نباید بیشتر از {$max} باشد",
        ])->validate();

        SchoolStudentGrade::create([
            'student_id'                    => $student->id,
            'cc_subject_id'                 => $this->cc_subject_id,
            'cc_chapter_id'                 => $this->cc_chapter_id,
            'recorded_by_school_manager_id' => auth('school-manager')->id(),
            'score'                         => $this->score,
            'scale'                         => $this->scale,
            'note'                          => $this->note ?: null,
            'recorded_at'                   => $this->recorded_at,
        ]);

        $this->reset(['cc_subject_id', 'cc_chapter_id', 'score', 'note']);
        $this->scale = '20';
        $this->recorded_at = now()->toDateString();
        $this->dispatch('success', 'نمره ثبت شد');
    }

    public function render()
    {
        $school = $this->school();
        $studentsQuery = $school ? $school->students() : null;

        // پایه‌های موجود در مدرسه
        $grades = $studentsQuery
            ? (clone $studentsQuery)->whereNotNull('grade')->distinct()->orderBy('grade')->pluck('grade')
            : collect();

        // دانش‌آموزان پایه‌ی انتخاب‌شده
        $students = ($studentsQuery && $this->selectedGrade !== '')
            ? (clone $studentsQuery)->with('user')->where('grade', $this->selectedGrade)->get()
            : collect();

        // درس‌ها و فصل‌های دانش‌آموز انتخاب‌شده
        $subjects = $this->selectedStudentId
            ? CcSubject::whereIn('cc_grade_id', $this->allowedGradeIds ?: [0])
                ->when($this->studentFieldId, function ($q) {
                    $q->where(function ($q) {
                        $q->where('cc_field_id', $this->studentFieldId)->orWhereNull('cc_field_id');
                    });
                })
                ->orderBy('cc_grade_id')->orderBy('order')->get()
            : collect();

        $chapters = $this->cc_subject_id
            ? CcChapter::where('cc_subject_id', $this->cc_subject_id)->where('is_active', true)->orderBy('order')->get()
            : collect();

        $recentGrades = $this->selectedStudentId
            ? SchoolStudentGrade::where('student_id', $this->selectedStudentId)
                ->with('subject', 'chapter')
                ->latest('recorded_at')->limit(15)->get()
            : collect();

        $selectedStudent = $this->selectedStudentId
            ? Student::with('user')->find($this->selectedStudentId)
            : null;

        return view('livewire.school-manager.grade-entry', [
            'grades'          => $grades,
            'students'        => $students,
            'subjects'        => $subjects,
            'chapters'        => $chapters,
            'recentGrades'    => $recentGrades,
            'selectedStudent' => $selectedStudent,
        ])->layout('layouts.school-manager.app');
    }
}
