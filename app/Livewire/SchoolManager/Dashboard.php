<?php

namespace App\Livewire\SchoolManager;

use App\Models\SchoolParentContact;
use App\Models\SchoolReport;
use App\Models\SchoolStudentGrade;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $manager = auth('school-manager')->user();
        $school = $manager?->school;

        $studentIds = $school ? $school->students()->pluck('id') : collect();

        $stats = [
            'students' => $studentIds->count(),
            'reports'  => SchoolReport::whereIn('student_id', $studentIds)->count(),
            'grades'   => SchoolStudentGrade::whereIn('student_id', $studentIds)->count(),
            'contacts' => SchoolParentContact::whereIn('student_id', $studentIds)->count(),
        ];

        return view('livewire.school-manager.dashboard', [
            'school' => $school,
            'stats'  => $stats,
        ])->layout('layouts.school-manager.app');
    }
}
