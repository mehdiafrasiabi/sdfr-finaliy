<?php

namespace App\Livewire\Manager\Advisors;

use App\Exports\AdvisorStudentsExport;
use App\Models\Admin;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class AdvisorStudent extends Component
{
    use WithPagination;

    public Admin $advisor;
    public string $search = '';

    public function mount($advisor)
    {
        $this->advisor = Admin::with('advisedStudents')->findOrFail($advisor);
    }

    public function exportExcel()
    {
        $fileName = $this->advisor->name;
        return Excel::download(new AdvisorStudentsExport($this->advisor->id), $fileName . '-students.xlsx');
    }

    public function render()
    {
        $students = $this->advisor->advisedStudents()
            ->whereHas('user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('mobile', 'like', '%' . $this->search . '%');
            })
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('livewire.manager.advisors.advisor-student', [
            'students' => $students,
        ])->layout('layouts.manager.app');
    }
}
