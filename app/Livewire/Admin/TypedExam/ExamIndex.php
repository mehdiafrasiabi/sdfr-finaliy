<?php

namespace App\Livewire\Admin\TypedExam;

use App\Models\TypedExam;
use Livewire\Component;
use Livewire\WithPagination;

class ExamIndex extends Component
{
    use WithPagination;



    public string $search = '';



    public function updatingSearch(): void

    {

        $this->resetPage();

    }



    public function render()

    {

        $exams = TypedExam::with(['settings'])

            ->withCount(['questions', 'assignments'])

            ->where('is_published', true)

            ->when($this->search, function ($q) {

                $q->where('title', 'like', "%{$this->search}%");

            })

            ->latest()

            ->paginate(10);



        $difficulties = [

            'easy' => 'آسان',

            'medium' => 'متوسط',

            'hard' => 'سخت',

            'comprehensive' => 'جامع',

        ];



        return view('livewire.admin.typed-exam.exam-index', compact(

            'exams', 'difficulties'

        ))->layout('layouts.admin.app');

    }
}
