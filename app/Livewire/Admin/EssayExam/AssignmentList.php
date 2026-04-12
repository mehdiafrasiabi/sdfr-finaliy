<?php

namespace App\Livewire\Admin\EssayExam;

use App\Models\EssayExam;
use App\Models\EssayExamAssignment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AssignmentList extends Component
{
    public int $examId;
    public ?EssayExam $exam = null;

    public function mount(int $examId): void
    {
        $admin = Auth::guard('admin')->user();
        $this->exam = EssayExam::where('admin_id', $admin->id)->findOrFail($examId);
        $this->examId = $examId;
    }

    public function render()
    {
        $admin = Auth::guard('admin')->user();

        $assignments = EssayExamAssignment::with(['student.user', 'time', 'latestAttempt'])
            ->where('essay_exam_id', $this->examId)
            ->where('admin_id', $admin->id)
            ->latest()
            ->get();

        return view('livewire.admin.essay-exam.assignment-list', compact('assignments'))
            ->layout('layouts.admin.app');
    }
}
