<?php

namespace App\Livewire\Admin\SchoolSupporter;

use App\Models\CcChapter;
use App\Models\CcField;
use App\Models\CcGrade;
use App\Models\CcSubject;
use App\Models\School;
use App\Models\SchoolStudentGrade;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class StudentGrades extends Component
{
    use WithPagination;

    public School $school;
    public Student $student;

    public ?int $cc_subject_id = null;
    public ?int $cc_chapter_id = null;
    public string $score = '';
    public string $scale = '20';
    public string $note = '';
    public string $recorded_at = '';

    public array $allowedGradeIds = [];
    public ?int $studentFieldId = null;

    public function mount(School $school, Student $student): void
    {
        $admin = auth('admin')->user();
        if (!$admin->hasRole('super admin')) {
            abort_unless($admin->supportedSchools()->where('schools.id', $school->id)->exists(), 403);
        }
        abort_unless($student->school_id === $school->id, 404);

        $this->school = $school;
        $this->student = $student;
        $this->recorded_at = now()->toDateString();

        // فیلتر پایه و رشته
        $gradeNum = (int) $student->grade;
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
            '*.required'  => 'فیلد ضروری است',
            'score.max'   => "نمره نباید بیشتر از {$max} باشد",
        ])->validate();

        SchoolStudentGrade::create([
            'student_id'           => $this->student->id,
            'cc_subject_id'        => $this->cc_subject_id,
            'cc_chapter_id'        => $this->cc_chapter_id,
            'recorded_by_admin_id' => auth('admin')->id(),
            'score'                => $this->score,
            'scale'                => $this->scale,
            'note'                 => $this->note ?: null,
            'recorded_at'          => $this->recorded_at,
        ]);

        $this->reset(['cc_subject_id', 'cc_chapter_id', 'score', 'note']);
        $this->scale = '20';
        $this->recorded_at = now()->toDateString();
        $this->dispatch('success', 'نمره ثبت شد');
    }

    public function render()
    {
        $subjects = CcSubject::whereIn('cc_grade_id', $this->allowedGradeIds ?: [0])
            ->when($this->studentFieldId, function ($q) {
                $q->where(function ($q) {
                    $q->where('cc_field_id', $this->studentFieldId)->orWhereNull('cc_field_id');
                });
            })
            ->orderBy('cc_grade_id')
            ->orderBy('order')
            ->get();

        $chapters = $this->cc_subject_id
            ? CcChapter::where('cc_subject_id', $this->cc_subject_id)->where('is_active', true)->orderBy('order')->get()
            : collect();

        $grades = SchoolStudentGrade::where('student_id', $this->student->id)
            ->with('subject', 'chapter', 'recordedBy')
            ->latest('recorded_at')
            ->paginate(15);

        return view('livewire.admin.school-supporter.student-grades', [
            'subjects' => $subjects,
            'chapters' => $chapters,
            'grades'   => $grades,
        ])->layout('layouts.admin.app');
    }
}
