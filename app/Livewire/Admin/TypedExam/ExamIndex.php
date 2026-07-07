<?php

namespace App\Livewire\Admin\TypedExam;

use App\Models\CcSubject;
use App\Models\Student;
use App\Models\TypedExam;
use App\Models\TypedExamAssignment;
use App\Models\TypedExamAttempt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

use Livewire\WithPagination;
class ExamIndex extends Component
{
    use WithPagination;
    public string $search = '';
    public array $dashboardData = [];

    public function mount()
    {
        $this->calculateDashboardData();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function calculateDashboardData(): void
    {
        $admin = Auth::guard('admin')->user();
        $studentIds = Student::where('advisor_id', $admin->id)->pluck('id');

        // 1. تعداد آزمون برگزارشده (منتشر شده)
        $totalExamsHeld = TypedExam::where('is_published', true)->count();

        // 2. تعداد آزمون انجام شده توسط دانش آموز / آزمون انجام ندادند
        $assignments = TypedExamAssignment::whereIn('student_id', $studentIds)->get();
        $totalAssignments = $assignments->count();
        $completedAssignments = $assignments->where('status', 'completed')->count();
        $pendingAssignments = $totalAssignments - $completedAssignments;

        // 3. میانگین درصد پاسخ دانش آموزان
        $assignmentIds = $assignments->pluck('id');
        $averagePercent = TypedExamAttempt::whereIn('assignment_id', $assignmentIds)->avg('score');

        // 4. دروس قوی و ضعیف (بر اساس آزمون مباحث)
        $strengths = collect();
        $weaknesses = collect();

        if ($assignmentIds->isNotEmpty()) {
            $attempts = TypedExamAttempt::whereIn('assignment_id', $assignmentIds)
                ->with(['assignment.typedExam.topic.chapter.subject'])
                ->get();

            $subjectScores = new Collection();

            foreach ($attempts as $attempt) {
                // Ensure we are processing a topic-based exam
                if (
                    $attempt->assignment &&
                    $attempt->assignment->typedExam &&
                    $attempt->assignment->typedExam->cc_topic_id &&
                    $attempt->assignment->typedExam->topic &&
                    $attempt->assignment->typedExam->topic->chapter &&
                    $attempt->assignment->typedExam->topic->chapter->subject
                ) {
                    $subject = $attempt->assignment->typedExam->topic->chapter->subject;
                    if (!$subjectScores->has($subject->id)) {
                        $subjectScores[$subject->id] = [
                            'subject_name' => $subject->name,
                            'scores' => [],
                        ];
                    }
                    $subjectScores[$subject->id]['scores'][] = $attempt->score;
                }
            }

            $subjectPerformance = $subjectScores->map(function ($subjectData) {
                $scores = collect($subjectData['scores']);
                return [
                    'subject_name' => $subjectData['subject_name'],
                    'average_score' => $scores->isNotEmpty() ? $scores->avg() : 0,
                ];
            });

            $strengths = $subjectPerformance->sortByDesc('average_score')->take(5);
            $weaknesses = $subjectPerformance->sortBy('average_score')->take(5);
        }

        $this->dashboardData = [
            'totalExamsHeld' => $totalExamsHeld,
            'completedAssignments' => $completedAssignments,
            'pendingAssignments' => $pendingAssignments,
            'averagePercent' => round($averagePercent ?? 0, 2),
            'strengths' => $strengths,
            'weaknesses' => $weaknesses,
        ];
    }

    public function render()
    {
        $admin = Auth::guard('admin')->user();
        $exams = TypedExam::with(['settings'])
            ->withCount(['questions'])
            ->withCount(['assignments as assignments_count' => function ($query) use ($admin) {
                $query->whereHas('student', function ($q) use ($admin) {
                    $q->where('advisor_id', $admin->id);
                });
            }])
            ->withCount(['assignments as completed_count' => function ($query) use ($admin) {
                $query->whereHas('student', function ($q) use ($admin) {
                    $q->where('advisor_id', $admin->id);
                })
                    ->where('status', 'completed');
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
