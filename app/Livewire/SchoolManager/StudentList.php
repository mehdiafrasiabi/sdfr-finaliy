<?php

namespace App\Livewire\SchoolManager;

use Livewire\Component;
use Livewire\WithPagination;

class StudentList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $gradeFilter = '';

    public function render()
    {
        $school = auth('school-manager')->user()?->school;

        $students = $school
            ? $school->students()
                ->with('user', 'schoolSupporter')
                ->when($this->gradeFilter, fn($q) => $q->where('grade', $this->gradeFilter))
                ->when($this->search, function ($q) {
                    $q->where(function ($q) {
                        $q->where('national_code', 'like', "%{$this->search}%")
                          ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$this->search}%")
                                                          ->orWhere('mobile', 'like', "%{$this->search}%"));
                    });
                })
                ->paginate(15)
            : null;

        return view('livewire.school-manager.student-list', [
            'students' => $students,
            'school'   => $school,
        ])->layout('layouts.school-manager.app');
    }
}
