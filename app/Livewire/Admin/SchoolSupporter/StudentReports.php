<?php

namespace App\Livewire\Admin\SchoolSupporter;

use App\Models\School;
use App\Models\SchoolReport;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class StudentReports extends Component
{
    use WithPagination;

    public School $school;
    public Student $student;

    public ?int $openReportId = null;
    public string $advisor_comment = '';
    public string $modalStatus = 'approved';
    public bool $modalOpen = false;

    public function mount(School $school, Student $student): void
    {
        $admin = auth('admin')->user();
        if (!$admin->hasRole('super admin')) {
            abort_unless($admin->supportedSchools()->where('schools.id', $school->id)->exists(), 403);
        }
        abort_unless($student->school_id === $school->id, 404);

        $this->school = $school;
        $this->student = $student;
    }

    public function openModal(int $reportId): void
    {
        $report = SchoolReport::find($reportId);
        if (!$report || $report->student_id !== $this->student->id) return;

        $this->openReportId = $report->id;
        $this->advisor_comment = (string) $report->advisor_comment;
        $this->modalStatus = $report->status === SchoolReport::STATUS_PENDING ? 'approved' : $report->status;
        $this->modalOpen = true;
    }

    public function closeModal(): void
    {
        $this->modalOpen = false;
        $this->openReportId = null;
        $this->advisor_comment = '';
    }

    public function saveReview(): void
    {
        Validator::make([
            'status'  => $this->modalStatus,
            'comment' => $this->advisor_comment,
        ], [
            'status'  => 'required|in:approved,rejected,pending',
            'comment' => 'nullable|string|max:2000',
        ])->validate();

        $report = SchoolReport::find($this->openReportId);
        if (!$report || $report->student_id !== $this->student->id) return;

        $report->update([
            'status'                => $this->modalStatus,
            'advisor_comment'       => $this->advisor_comment ?: null,
            'advisor_commented_at'  => now(),
            'reviewed_by_admin_id'  => auth('admin')->id(),
        ]);

        $this->closeModal();
        $this->dispatch('success', 'وضعیت گزارش به‌روزرسانی شد');
    }

    public function render()
    {
        $reports = SchoolReport::where('student_id', $this->student->id)
            ->with('parts.subject', 'parts.chapter')
            ->latest('report_date')
            ->paginate(10);

        return view('livewire.admin.school-supporter.student-reports', [
            'reports' => $reports,
        ])->layout('layouts.admin.app');
    }
}
