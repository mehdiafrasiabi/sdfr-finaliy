<?php

namespace App\Exports;

use App\Models\CcField;
use App\Models\CcGrade;
use App\Models\CcSubject;
use App\Models\SchoolStudentGrade;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

/**
 * قالب اکسل ثبت نمرات ماهانه برای دانش‌آموزانِ تحت مشاورهٔ یک مشاور.
 * هر ردیف = یک (دانش‌آموز، درس). نمرات موجودِ همان ماه از قبل پر می‌شوند.
 */
class SchoolGradesTemplateExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected ?int $advisorId,
        protected string $jalaliMonth,
    ) {}

    public function collection()
    {
        $students = Student::with('user')
            ->where('advisor_id', $this->advisorId)
            ->whereNotNull('grade')
            ->get();

        $existing = SchoolStudentGrade::whereIn('student_id', $students->pluck('id'))
            ->where('jalali_month', $this->jalaliMonth)
            ->get()
            ->groupBy('student_id');

        $rows = collect();

        foreach ($students as $student) {
            $subjects = $this->subjectsFor($student);
            $studentGrades = ($existing->get($student->id) ?? collect())->keyBy('cc_subject_id');

            foreach ($subjects as $subject) {
                $g = $studentGrades->get($subject->id);
                $rows->push([
                    'student_id'     => $student->id,
                    'name'           => $student->user?->name,
                    'grade'          => $student->grade,
                    'field'          => $student->field,
                    'subject_id'     => $subject->id,
                    'subject_name'   => $subject->name,
                    'class_activity' => $g && $g->class_activity !== null ? (float) $g->class_activity : null,
                    'exam'           => $g && $g->exam !== null ? (float) $g->exam : null,
                    'teacher_comment'=> $g?->teacher_comment,
                ]);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'شناسه دانش‌آموز',
            'نام دانش‌آموز',
            'پایه',
            'رشته',
            'شناسه درس',
            'نام درس',
            'فعالیت کلاسی (۲۰)',
            'امتحان (۲۰)',
            'نظر دبیر',
        ];
    }

    private function subjectsFor(Student $student)
    {
        $gradeNum = (int) $student->grade;

        $fieldId = null;
        if ($student->field) {
            $field = CcField::where('slug', $student->field)->orWhere('name', $student->field)->first();
            $fieldId = $field?->id;
        }

        $gradeIds = CcGrade::where('is_active', true)
            ->where('grade_number', '<=', $gradeNum)
            ->when($fieldId, fn($q) => $q->where(fn($q) => $q->where('cc_field_id', $fieldId)->orWhereNull('cc_field_id')))
            ->pluck('id')->toArray();

        return CcSubject::whereIn('cc_grade_id', $gradeIds ?: [0])
            ->when($fieldId, fn($q) => $q->where(fn($q) => $q->where('cc_field_id', $fieldId)->orWhereNull('cc_field_id')))
            ->orderBy('cc_grade_id')->orderBy('order')->get();
    }
}
