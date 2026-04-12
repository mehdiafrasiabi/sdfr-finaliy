<?php

namespace App\Livewire\Admin\EssayExam;

use App\Models\EssayExam;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ExamList extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function deleteExam(int $examId): void
    {
        $admin = Auth::guard('admin')->user();
        $exam = EssayExam::where('admin_id', $admin->id)->findOrFail($examId);
        $exam->delete();
        session()->flash('success', 'آزمون با موفقیت حذف شد.');
    }

    public function render()
    {
        $admin = Auth::guard('admin')->user();

        $exams = EssayExam::where('admin_id', $admin->id)
            ->withCount(['questions', 'assignments'])
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.essay-exam.exam-list', compact('exams'))
            ->layout('layouts.admin.app');
    }
}
