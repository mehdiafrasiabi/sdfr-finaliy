<?php

namespace App\Livewire\Manager\Supports;

use App\Exports\BarnamehExport;
use App\Exports\ReportDailyExport;
use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class SupporterStudentDetail extends Component
{
    use WithPagination;

    public $student;

    public function mount($student)
    {
        $this->student = Student::with('user')->findOrFail($student);
    }
    public function exportBarnamehs()
    {
        $student = $this->student;
        $filename = 'برنامه‌های_مشاوره‌ای_' . $student->personalInformation->name . '.xlsx';

        return Excel::download(new BarnamehExport($student), $filename);
    }

    public function exportReportDaily()
    {
        $student = $this->student;
        $filename = 'گزارش‌های_روزانه_' . $student->personalInformation->name . '.xlsx';

        return Excel::download(new ReportDailyExport($student), $filename);
    }

    public function render()
    {
        $details = $this->student->barnamehs()
            ->with('supporter')
            ->latest()
            ->paginate(10);

        $reportMonthlies = $this->student->reportMonthlies()
            ->latest()
            ->paginate(10);
        $reportDaily = $this->student->reportdaily()
            ->latest()
            ->paginate(10);

        return view('livewire.manager.supports.supporter-student-detail', [
            'details' => $details,
            'reportMonthlies' => $reportMonthlies,
            'reportDaily' => $reportDaily,
        ])->layout('layouts.manager.app');
    }

}
