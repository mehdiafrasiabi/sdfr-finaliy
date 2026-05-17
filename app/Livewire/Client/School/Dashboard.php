<?php

namespace App\Livewire\Client\School;

use App\Models\SchoolParentContact;
use App\Models\SchoolReport;
use App\Models\SchoolStudentGrade;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class Dashboard extends Component
{
    use SEOTools;

    public function mount(): void
    {
        $this->seo()->setTitle('داشبورد دانش‌آموز');
        abort_unless(auth()->user()?->isSchoolStudent(), 403);
    }

    public function render()
    {
        $student = auth()->user()->student;

        $reportStats = SchoolReport::where('student_id', $student->id)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status='pending'  THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status='approved' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status='rejected' THEN 1 ELSE 0 END) as rejected,
                COALESCE(SUM(total_study_minutes),0)  as total_study,
                COALESCE(SUM(total_mobile_minutes),0) as total_mobile
            ")
            ->first();

        $gradesCount = SchoolStudentGrade::where('student_id', $student->id)->count();
        $contactsCount = SchoolParentContact::where('student_id', $student->id)->count();

        $recentReports = SchoolReport::where('student_id', $student->id)
            ->withCount('parts')
            ->latest('report_date')
            ->limit(5)
            ->get();

        $recentGrades = SchoolStudentGrade::where('student_id', $student->id)
            ->with('subject', 'chapter')
            ->latest('recorded_at')
            ->limit(5)
            ->get();

        return view('livewire.client.school.dashboard', [
            'student'       => $student->load('schoolSupporter', 'school'),
            'reportStats'   => $reportStats,
            'gradesCount'   => $gradesCount,
            'contactsCount' => $contactsCount,
            'recentReports' => $recentReports,
            'recentGrades'  => $recentGrades,
        ])->layout('layouts.client.app');
    }
}
