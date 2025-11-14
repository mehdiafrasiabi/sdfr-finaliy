<?php

namespace App\Livewire\Manager\Exam;

use App\Models\Exam;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $level = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'level' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingLevel()
    {
        $this->resetPage();
    }

    public function deleteExam(Exam $exam)
    {
        $exam->delete();
        session()->flash('success', 'آزمون با موفقیت حذف شد.');
    }

    public function render()
    {
        $query = Exam::query();

        // Search
        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        // Filter by level
        if ($this->level) {
            $query->where('level', $this->level);
        }

        // Show only exams created by the current admin if they are not super-admin
            $exams = $query->latest()->paginate(10);


        return view('livewire.manager.exam.index',[  'exams' => $exams,])->layout('layouts.manager.app');
    }
}
