<?php

namespace App\Livewire\Manager\Supports;

use App\Exports\SupporterStudentsExport;
use App\Models\Admin;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

/**
 * لیست دانش‌آموزانی که به یک «پشتیبان جذب» اختصاص داده شده‌اند —
 * بر اساس trial_weeks.acquisition_supporter_id.
 */
class SupporterStudent extends Component
{
    use WithPagination;

    public Admin $supporter;
    public string $search = '';

    public function mount($supporter): void
    {
        $this->supporter = Admin::findOrFail($supporter);
    }

    public function exportExcel()
    {
        $fileName = $this->supporter->name;
        return Excel::download(new SupporterStudentsExport($this->supporter->id), $fileName.'-students.xlsx');
    }

    public function render()
    {
        $students = $this->supporter->acquisitionTrialWeeks()
            ->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('mobile', 'like', '%' . $this->search . '%');
            })
            ->with('user.personalInformation', 'student')
            ->latest()
            ->paginate(10);

        return view('livewire.manager.supports.supporter-student', [
            'students' => $students,
        ])->layout('layouts.manager.app');
    }
}
