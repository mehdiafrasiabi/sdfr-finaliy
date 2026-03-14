<?php


namespace App\Livewire\Client\Profile\TypedExam;


use App\Models\Student;

use App\Models\TypedExamAssignment;

use Illuminate\Support\Facades\Auth;

use Livewire\Component;


class TypedExamList extends Component

{
    public ?int $confirmingExamId = null;
    public ?int $selectedExamId = null;
    public array $expandedExams = [];
    public function confirmEntry(int $assignmentId): void
    {
        $this->confirmingExamId = $assignmentId;
    }


    public function closeModal(): void
    {
        $this->confirmingExamId = null;
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
        if ($this->confirmingExamId) {
            $this->redirectRoute('client.profile.typed-exam.test', [
                'assignmentId' => $this->confirmingExamId,
            ]);
        }
    }
    public function render()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        $assignments = collect();
        if ($student) {
            $assignments = TypedExamAssignment::with(['typedExam.settings', 'typedExam.questions', 'time', 'latestAttempt'])
                ->where('student_id', $student->id)
                ->whereHas('typedExam', function ($q) {
                    $q->where('is_published', true);
                })
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
                        // Format time range for display
                        $timeRange = [
                            'start_date' => verta($assignment->time->start_date)->format('Y/m/d'),
                            'end_date' => verta($assignment->time->end_date)->format('Y/m/d'),
                            'start_time' => $assignment->time->start_time,
                            'end_time' => $assignment->time->end_time,
                        ];
                        if ($now->lt($startDateTime)) {
                            $status = 'not_started';
                        } elseif ($now->gt($endDateTime)) {
                            $status = 'expired';
                        } else {
                            $canStart = true;
                            $status = 'available';
                        }
                    }
                    if ($assignment->latestAttempt?->is_finished) {
                        $status = 'completed';
                    }
                    $assignment->computed_status = $status;
                    $assignment->can_start = $canStart && !$assignment->latestAttempt?->is_finished;
                    $assignment->time_range = $timeRange;
                    return $assignment;
                });
        }
        return view('livewire.client.profile.typed-exam.typed-exam-list', compact('assignments'))
            ->layout('layouts.client.app');
    }
}
