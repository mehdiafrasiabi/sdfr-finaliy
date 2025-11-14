<?php

namespace App\Livewire\Manager\Student;

use App\Exports\StudentsExport;
use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithPagination;
    public function export()
    {
        return Excel::download(new StudentsExport(), 'students.xlsx');
    }
    public function render()
    {
        $students = Student::with(['user.personalInformation', 'supporterStudent', 'payment'])->latest()->paginate(10);

        return view('livewire.manager.student.index',['students' => $students])->layout('layouts.manager.app');
    }
}
