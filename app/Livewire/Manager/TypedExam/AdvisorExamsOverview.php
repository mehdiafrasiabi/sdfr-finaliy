<?php

namespace App\Livewire\Manager\TypedExam;

use App\Models\Admin;
use App\Models\TypedExam;
use Livewire\Component;
use Livewire\WithPagination;

class AdvisorExamsOverview extends Component
{
    use WithPagination;

    public ?int $selectedAdvisorId = null;
    public ?string $selectedAdvisorName = null;

    public string $advisorSearch = '';

    public function updatingAdvisorSearch(): void
    {
        $this->resetPage('advisors');
    }

    public function viewAdvisor(int $adminId): void
    {
        $advisor = Admin::find($adminId);

        if (!$advisor) {
            return;
        }

        $this->selectedAdvisorId = $adminId;
        $this->selectedAdvisorName = $advisor->name;
        $this->resetPage('exams');
    }

    public function backToOverview(): void
    {
        $this->selectedAdvisorId = null;
        $this->selectedAdvisorName = null;
    }

    public function togglePublish(int $examId): void
    {
        $exam = TypedExam::whereKey($examId)->whereNotNull('admin_id')->first();

        if ($exam) {
            $exam->update(['is_published' => !$exam->is_published]);
            $this->dispatch('success', $exam->is_published ? 'آزمون منتشر شد.' : 'آزمون از انتشار خارج شد.');
        }
    }

    public function deleteExam(int $examId): void
    {
        $exam = TypedExam::whereKey($examId)->whereNotNull('admin_id')->first();

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

    public function render()
    {
        $advisors = null;
        $exams = null;

        if ($this->selectedAdvisorId) {
            $exams = TypedExam::ownedBy($this->selectedAdvisorId)
                ->withCount('questions')
                ->withCount('assignments')
                ->latest()
                ->paginate(10, pageName: 'exams');
        } else {
            $advisors = Admin::whereHas('advisedStudents')
                ->withCount(['typedExams as typed_exams_count', 'typedExams as published_exams_count' => function ($q) {
                    $q->where('is_published', true);
                }])
                ->when($this->advisorSearch, function ($q) {
                    $q->where('name', 'like', "%{$this->advisorSearch}%");
                })
                ->orderByDesc('typed_exams_count')
                ->paginate(15, pageName: 'advisors');
        }

        $difficulties = [
            'easy' => 'آسان',
            'medium' => 'متوسط',
            'hard' => 'سخت',
            'comprehensive' => 'جامع',
        ];

        return view('livewire.manager.typed-exam.advisor-exams-overview', compact(
            'advisors', 'exams', 'difficulties'
        ))->layout('layouts.manager.app');
    }
}
