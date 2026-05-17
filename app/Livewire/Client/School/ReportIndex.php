<?php

namespace App\Livewire\Client\School;

use App\Models\SchoolReport;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use Livewire\WithPagination;

class ReportIndex extends Component
{
    use WithPagination, SEOTools;

    public function mount(): void
    {
        $this->seo()->setTitle('گزارش‌های مطالعه');
        abort_unless(auth()->user()?->isSchoolStudent(), 403);
    }

    public function render()
    {
        $student = auth()->user()->student;

        $reports = SchoolReport::query()
            ->where('student_id', $student->id)
            ->withCount('parts')
            ->latest('report_date')
            ->paginate(10);

        return view('livewire.client.school.report-index', [
            'reports'   => $reports,
            'student'   => $student->load('schoolSupporter', 'school'),
        ])->layout('layouts.client.app');
    }
}
