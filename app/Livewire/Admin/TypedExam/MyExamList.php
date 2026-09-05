<?php

namespace App\Livewire\Admin\TypedExam;

use App\Models\TypedExam;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MyExamList extends Component
{
    use WithPagination;

    public string $filterTitle = '';
    public string $sortOrder = 'desc';

    protected $queryString = [
        'filterTitle' => ['except' => ''],
        'sortOrder' => ['except' => 'desc'],
    ];

    public function updatingFilterTitle(): void
    {
        $this->resetPage();
    }

    public function togglePublish(int $examId): void
    {
        $exam = $this->ownExam($examId);

        if ($exam) {
            $exam->update(['is_published' => !$exam->is_published]);
            $this->dispatch('success', $exam->is_published ? 'آزمون منتشر شد.' : 'آزمون از انتشار خارج شد.');
        }
    }

    public function deleteExam(int $examId): void
    {
        $exam = $this->ownExam($examId);

        if (!$exam) {
            return;
        }

        $hasAttempts = $exam->assignments()->whereHas('attempts')->exists();

        if ($hasAttempts) {
            $this->dispatch('error', 'این آزمون دارای شرکت‌کننده است و قابل حذف نیست.');
            return;
        }

        $exam->delete();
        $this->dispatch('success', 'آزمون با موفقیت حذف شد.');
    }

    protected function ownExam(int $examId): ?TypedExam
    {
        return TypedExam::ownedBy(Auth::guard('admin')->id())->whereKey($examId)->first();
    }

    public function clearFilters(): void
    {
        $this->reset(['filterTitle', 'sortOrder']);
        $this->sortOrder = 'desc';
        $this->resetPage();
    }

    public function render()
    {
        $adminId = Auth::guard('admin')->id();

        $query = TypedExam::ownedBy($adminId)
            ->withCount('questions')
            ->withCount('assignments');

        if ($this->filterTitle) {
            $query->where('title', 'like', "%{$this->filterTitle}%");
        }

        $query->orderBy('created_at', $this->sortOrder);

        $exams = $query->paginate(10);

        $totalOwnExamsCount = TypedExam::ownedBy($adminId)->count();

        $difficulties = [
            'easy' => 'آسان',
            'medium' => 'متوسط',
            'hard' => 'سخت',
            'comprehensive' => 'جامع',
        ];

        return view('livewire.admin.typed-exam.my-exam-list', compact(
            'exams', 'difficulties', 'totalOwnExamsCount'
        ))->layout('layouts.admin.app');
    }
}
