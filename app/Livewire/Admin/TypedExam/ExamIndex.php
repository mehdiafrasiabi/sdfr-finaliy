<?php

namespace App\Livewire\Admin\TypedExam;

use App\Models\TypedExam;
use Illuminate\Support\Facades\Auth;

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

        $admin = Auth::guard('admin')->user();


        // Get exams with filtered assignment count (only for this admin's students)

        $exams = TypedExam::with(['settings'])
            ->withCount(['questions'])
            ->withCount(['assignments as assignments_count' => function ($query) use ($admin) {

                $query->whereHas('student', function ($q) use ($admin) {

                    $q->where('advisor_id', $admin->id);

                })->whereNull('deleted_at');

            }])
            ->withCount(['assignments as completed_count' => function ($query) use ($admin) {

                $query->whereHas('student', function ($q) use ($admin) {

                    $q->where('advisor_id', $admin->id);

                })
                    ->where('status', 'completed')
                    ->whereNull('deleted_at');

            }])
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
