<?php

namespace App\Livewire\Client\Profile\TypedExam;

use App\Models\EssayExamAssignment;
use App\Models\Student;
use App\Models\TypedExamAssignment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TypedExamList extends Component
{
    public ?int $confirmingExamId = null;
    public ?int $selectedExamId = null;
    public array $expandedExams = [];

    /** 'typed' | 'essay' */
    public string $activeTab = 'typed';

    public ?int $viewingAnswerSheetFor = null; // essay assignment id for preview

    public function setTab(string $tab): void
    {
        $this->activeTab = in_array($tab, ['typed', 'essay']) ? $tab : 'typed';
        $this->confirmingExamId = null;
        $this->viewingAnswerSheetFor = null;
    }

    public function confirmEntry(int $assignmentId): void
    {
        $this->confirmingExamId = $assignmentId;
    }

    public function closeModal(): void
    {
        $this->confirmingExamId = null;
        $this->viewingAnswerSheetFor = null;
    }

    public function toggleDetails(int $assignmentId): void
    {
        if (in_array($assignmentId, $this->expandedExams)) {
            $this->expandedExams = array_filter($this->expandedExams, fn($id) => $id !== $assignmentId);
        } else {
            $this->expandedExams[] = $assignmentId;
        }
    }

    public function enterExam(): void
    {
        if (!$this->confirmingExamId) return;

        if ($this->activeTab === 'essay') {
            $this->redirectRoute('client.profile.essay-exam.test', [
                'assignmentId' => $this->confirmingExamId,
            ]);
        } else {
            $this->redirectRoute('client.profile.typed-exam.test', [
                'assignmentId' => $this->confirmingExamId,
            ]);
        }
    }

    public function viewAnswerSheet(int $assignmentId): void
    {
        $this->viewingAnswerSheetFor = $assignmentId;
    }

    protected function loadTypedAssignments(Student $student)
    {
        return TypedExamAssignment::with(['typedExam.settings', 'typedExam.questions', 'time', 'latestAttempt'])
            ->where('student_id', $student->id)
            ->whereHas('typedExam', fn($q) => $q->where('is_published', true))
            ->latest()
            ->get()
            ->map(function ($assignment) {
                $now = now();
                $canStart = false;
                $status = 'pending';
                $timeRange = null;
                if ($assignment->time) {
                    $startDateTime = $assignment->time->start_date->format('Y-m-d') . ' ' . $assignment->time->start_time;
                    $endDateTime = $assignment->time->end_date->format('Y-m-d') . ' ' . $assignment->time->end_time;
                    $timeRange = [
                        'start_date' => verta($assignment->time->start_date)->format('Y/m/d'),
                        'end_date'   => verta($assignment->time->end_date)->format('Y/m/d'),
                        'start_time' => $assignment->time->start_time,
                        'end_time'   => $assignment->time->end_time,
                    ];
                    if ($now->lt($startDateTime))      $status = 'not_started';
                    elseif ($now->gt($endDateTime))    $status = 'expired';
                    else { $canStart = true; $status = 'available'; }
                }
                if ($assignment->latestAttempt?->is_finished) {
                    $status = 'completed';
                }
                $assignment->computed_status = $status;
                $assignment->can_start       = $canStart && !$assignment->latestAttempt?->is_finished;
                $assignment->time_range      = $timeRange;
                return $assignment;
            });
    }

    protected function loadEssayAssignments(Student $student)
    {
        return EssayExamAssignment::with(['essayExam.questions', 'time', 'latestAttempt'])
            ->where('student_id', $student->id)
            ->latest()
            ->get()
            ->map(function ($a) {
                $now = now();
                $status = 'pending';
                $canStart = false;
                $timeRange = null;
                if ($a->time) {
                    $timeRange = [
                        'start_date' => verta($a->time->start_at)->format('Y/m/d'),
                        'end_date'   => verta($a->time->end_at)->format('Y/m/d'),
                        'start_time' => $a->time->start_at->format('H:i'),
                        'end_time'   => $a->time->end_at->format('H:i'),
                    ];
                    if ($now->lt($a->time->start_at))     $status = 'not_started';
                    elseif ($now->gt($a->time->end_at))   $status = 'expired';
                    else { $canStart = true; $status = 'available'; }
                }
                // اگر دانش‌آموز شروع کرده ولی تایمر شخصی‌اش تمام شده، ورود مجدد ممنوع
                if ($canStart && $a->latestAttempt && $a->latestAttempt->started_at && $a->time && $a->time->duration_minutes) {
                    $individualDeadline = $a->latestAttempt->started_at->copy()->addMinutes($a->time->duration_minutes);
                    $effectiveEnd = $individualDeadline->lt($a->time->end_at) ? $individualDeadline : $a->time->end_at;
                    if ($now->gte($effectiveEnd)) {
                        $canStart = false;
                        $status = 'expired';
                    }
                }
                if (in_array($a->status, ['submitted', 'graded'])) {
                    $status = $a->status === 'graded' ? 'completed' : 'submitted';
                    $canStart = false;
                }
                $a->computed_status = $status;
                $a->can_start       = $canStart && !in_array($a->status, ['submitted', 'graded']);
                $a->time_range      = $timeRange;
                return $a;
            });
    }

    public function render()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        $assignments = collect();
        $essayAssignments = collect();

        if ($student) {
            $assignments      = $this->loadTypedAssignments($student);
            $essayAssignments = $this->loadEssayAssignments($student);
        }

        return view('livewire.client.profile.typed-exam.typed-exam-list', compact(
            'assignments', 'essayAssignments'
        ))->layout('layouts.client.app');
    }
}
