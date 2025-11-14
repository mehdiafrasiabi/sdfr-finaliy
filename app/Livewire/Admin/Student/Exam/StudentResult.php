<?php

namespace App\Livewire\Admin\Student\Exam;

use App\Models\Exam;
use App\Models\ExamAttemp;
use Livewire\Component;
use Livewire\WithPagination;

class StudentResult extends Component
{
    use WithPagination;

    public Exam $exam;

    public function mount(Exam $exam)
    {
        $this->exam = $exam;
    }

    public function render()
    {
        $attempts = ExamAttemp::where('exam_id', $this->exam->id)
            ->whereNotNull('submitted_at')
            ->with('student')
            ->paginate(10);

        return view('livewire.admin.student.exam.student-result', [
            'attempts' => $attempts,
            'exam' => $this->exam,
        ])->layout('layouts.admin.app');
    }
}

