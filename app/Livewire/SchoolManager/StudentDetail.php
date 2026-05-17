<?php

namespace App\Livewire\SchoolManager;

use App\Models\SchoolParentContact;
use App\Models\SchoolReport;
use App\Models\SchoolStudentGrade;
use App\Models\Student;
use Livewire\Component;

class StudentDetail extends Component
{
    public Student $student;
    public string $tab = 'grades';

    public function mount(Student $student): void
    {
        $school = auth('school-manager')->user()?->school;
        abort_unless($school && $student->school_id === $school->id, 404);
        $this->student = $student;
    }

    public function switchTab(string $tab): void
    {
        if (in_array($tab, ['grades', 'reports', 'contacts'], true)) {
            $this->tab = $tab;
        }
    }

    public function render()
    {
        $grades = SchoolStudentGrade::where('student_id', $this->student->id)
            ->with('subject', 'chapter', 'recordedBy')
            ->latest('recorded_at')->limit(100)->get();

        $reports = SchoolReport::where('student_id', $this->student->id)
            ->with('parts.subject', 'parts.chapter')
            ->latest('report_date')->limit(50)->get();

        $contacts = SchoolParentContact::where('student_id', $this->student->id)
            ->with('admin')
            ->latest('contacted_at')->limit(100)->get();

        return view('livewire.school-manager.student-detail', [
            'grades'   => $grades,
            'reports'  => $reports,
            'contacts' => $contacts,
        ])->layout('layouts.school-manager.app');
    }
}
