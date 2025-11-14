<?php

namespace App\Livewire\Manager\Supports;

use App\Exports\SupporterStudentsExport;
use App\Models\Admin;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class SupporterStudent extends Component
{
    use WithPagination;
    public $supporter;
    public $search = '';

    public function mount($supporter)
    {
        $this->supporter = Admin::with('supportedStudents')->findOrFail($supporter);
    }
    public function exportExcel()
    {
        $fileName = $this->supporter->name;
        return Excel::download(new SupporterStudentsExport($this->supporter->id), $fileName.'-students.xlsx');
    }

    public function render()
    {
        $students = $this->supporter->supportedStudents()
            ->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('mobile', 'like', '%' . $this->search . '%');
            })
            ->with('user')
            ->latest()
            ->paginate(10);
        return view('livewire.manager.supports.supporter-student',[ 'students' => $students,])->layout('layouts.manager.app');
    }
}
