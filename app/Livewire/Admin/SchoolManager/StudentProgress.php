<?php

namespace App\Livewire\Admin\SchoolManager;

use App\Models\Student;
use App\Support\ClassificationProgress;
use App\Support\ExamProgress;
use Livewire\Component;

/**
 * صفحه‌ی پیشرفت یک دانش‌آموز بر اساس طبقه‌بندی (star) و روند نمرات.
 */
class StudentProgress extends Component
{
    public Student $student;

    public function mount(Student $student): void
    {
        $admin = auth('admin')->user();
        abort_unless(
            $admin?->hasRole('super admin') || ($admin?->school_id && $student->school_id === $admin->school_id),
            404
        );
        $this->student = $student;
    }

    public function render()
    {
        $schoolTrend = ExamProgress::schoolGradeTrend([$this->student->id]);
        $typedTrend  = ExamProgress::typedExamTrend([$this->student->id]);

        $subjectTrends = $this->student->user_id
            ? ClassificationProgress::studentSubjectTrends($this->student->user_id)
            : [];

        return view('livewire.admin.school-manager.student-progress', [
            'schoolTrend'    => $schoolTrend,
            'typedTrend'     => $typedTrend,
            'schoolDelta'    => ExamProgress::latestDelta($schoolTrend),
            'typedDelta'     => ExamProgress::latestDelta($typedTrend),
            'subjectTrends'  => $subjectTrends,
        ])->layout('layouts.admin.app');
    }
}
