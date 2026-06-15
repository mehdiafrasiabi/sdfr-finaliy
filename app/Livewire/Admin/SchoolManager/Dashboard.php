<?php

namespace App\Livewire\Admin\SchoolManager;

use App\Models\SchoolParentContact;
use App\Models\SchoolReport;
use App\Models\SchoolStudentGrade;
use App\Models\Student;
use App\Support\ExamProgress;
use Livewire\Component;

/**
 * داشبورد مدیر مدرسه در پنل ادمین (نقش school-manager).
 * اسکوپ بر اساس admins.school_id (managedSchool).
 */
class Dashboard extends Component
{
    public function render()
    {
        $admin = auth('admin')->user();
        $schoolId = $admin?->school_id;
        abort_unless($schoolId || $admin?->hasRole('super admin'), 403);

        $school = $admin?->managedSchool;
        $studentIds = $schoolId
            ? Student::where('school_id', $schoolId)->pluck('id')
            : collect();

        $stats = [
            'students' => $studentIds->count(),
            'reports'  => SchoolReport::whereIn('student_id', $studentIds)->count(),
            'grades'   => SchoolStudentGrade::whereIn('student_id', $studentIds)->count(),
            'contacts' => SchoolParentContact::whereIn('student_id', $studentIds)->count(),
        ];

        $schoolTrend = ExamProgress::schoolGradeTrend($studentIds);
        $typedTrend  = ExamProgress::typedExamTrend($studentIds);

        return view('livewire.admin.school-manager.dashboard', [
            'school'      => $school,
            'stats'       => $stats,
            'schoolTrend' => $schoolTrend,
            'typedTrend'  => $typedTrend,
            'schoolDelta' => ExamProgress::latestDelta($schoolTrend),
            'typedDelta'  => ExamProgress::latestDelta($typedTrend),
        ])->layout('layouts.admin.app');
    }
}
