<?php

namespace App\Livewire\Admin\EssayExam;

use App\Models\EssayExam;
use App\Models\EssayExamAssignment;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\WithPagination;

class ExamList extends Component
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

    public function deleteExam(int $examId): void
    {
        $admin = Auth::guard('admin')->user();
        $exam = EssayExam::where('admin_id', $admin->id)->findOrFail($examId);
        $exam->delete();
        session()->flash('success', 'آزمون با موفقیت حذف شد.');
    }

    public function calculateDashboardData(): void
    {
        $admin = Auth::guard('admin')->user();
        $studentIds = Student::where('advisor_id', $admin->id)->pluck('id');

        // 1. تعداد آزمون برگزارشده
        $totalExamsHeld = EssayExam::where('admin_id', $admin->id)->count();

        // 2. تعداد آزمون انجام شده توسط دانش آموز / آزمون انجام ندادند
        $assignments = EssayExamAssignment::whereIn('student_id', $studentIds)->get();
        $totalAssignments = $assignments->count();
        $completedAssignments = $assignments->whereIn('status', ['submitted', 'graded'])->count();
        $pendingAssignments = $totalAssignments - $completedAssignments;

        // 3. میانگین نمرات پاسخ دانش آموزان (با بررسی وجود ستون score)
        $averageScore = 0;
        $totalScore = 0;
        if (Schema::hasColumn('essay_exam_assignments', 'score')) {
             $averageScore = $assignments->where('score', '>', 0)->avg('score');
             $totalScore = $assignments->where('score', '>', 0)->avg('total_score'); // فرض وجود total_score
        }
        $averagePercent = ($totalScore > 0) ? ($averageScore / $totalScore) * 100 : 0;


        // 4. دروس قوی و ضعیف
        $strengths = collect();
        $weaknesses = collect();
        if (Schema::hasColumn('essay_exam_assignments', 'score')) {
            $subjectPerformance = EssayExamAssignment::whereIn('student_id', $studentIds)
                ->whereIn('status', ['submitted', 'graded'])
                ->join('essay_exams', 'essay_exam_assignments.essay_exam_id', '=', 'essay_exams.id')
                ->whereNotNull('essay_exams.cc_topic_id')
                ->join('cc_topics', 'essay_exams.cc_topic_id', '=', 'cc_topics.id')
                ->join('cc_chapters', 'cc_topics.cc_chapter_id', '=', 'cc_chapters.id')
                ->join('cc_subjects', 'cc_chapters.cc_subject_id', '=', 'cc_subjects.id')
                ->select('cc_subjects.name as subject_name', DB::raw('AVG(essay_exam_assignments.score / essay_exams.total_score * 100) as average_percent'))
                ->groupBy('cc_subjects.name')
                ->get();

            $strengths = $subjectPerformance->sortByDesc('average_percent')->take(5);
            $weaknesses = $subjectPerformance->sortBy('average_percent')->take(5);
        }


        $this->dashboardData = [
            'totalExamsHeld' => $totalExamsHeld,
            'completedAssignments' => $completedAssignments,
            'pendingAssignments' => $pendingAssignments,
            'averagePercent' => round($averagePercent, 2),
            'strengths' => $strengths,
            'weaknesses' => $weaknesses,
        ];
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
