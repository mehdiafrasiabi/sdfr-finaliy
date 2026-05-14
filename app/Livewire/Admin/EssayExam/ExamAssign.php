<?php

namespace App\Livewire\Admin\EssayExam;

use App\Models\EssayExam;
use App\Models\EssayExamAssignment;
use App\Models\EssayExamAssignmentTime;
use App\Models\Notification;
use App\Models\NotificationRecipient;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ExamAssign extends Component
{
    public int $examId;
    public ?EssayExam $exam = null;

    public string $search = '';
    /** @var int[] */
    public array $selected = [];

    public string $start_at = '';
    public string $end_at = '';
    public int $duration_minutes = 60;

    public function mount(int $examId): void
    {
        $admin = Auth::guard('admin')->user();
        $this->exam = EssayExam::where('admin_id', $admin->id)
            ->with('questions')
            ->findOrFail($examId);
        $this->examId = $examId;

        $this->start_at = now()->format('Y-m-d\TH:i');
        $this->end_at   = now()->addDay()->format('Y-m-d\TH:i');
    }

    public function assign(): void
    {
        $this->validate([
            'selected'         => 'required|array|min:1',
            'selected.*'       => 'integer|exists:students,id',
            'start_at'         => 'required|date',
            'end_at'           => 'required|date|after:start_at',
            'duration_minutes' => 'required|integer|min:5|max:600',
        ]);

        $admin = Auth::guard('admin')->user();

        DB::transaction(function () use ($admin) {
            foreach ($this->selected as $studentId) {
                $assignment = EssayExamAssignment::create([
                    'essay_exam_id' => $this->exam->id,
                    'student_id'    => $studentId,
                    'admin_id'      => $admin->id,
                    'status'        => EssayExamAssignment::STATUS_PENDING,
                ]);

                EssayExamAssignmentTime::create([
                    'assignment_id'    => $assignment->id,
                    'start_at'         => $this->start_at,
                    'end_at'           => $this->end_at,
                    'duration_minutes' => $this->duration_minutes,
                ]);

                $student = Student::with('user')->find($studentId);
                if ($student?->user) {
                    $notification = Notification::create([
                        'admin_id'         => $admin->id,
                        'category'         => Notification::CATEGORY_ANNOUNCEMENT,
                        'target_type'      => Notification::TARGET_SINGLE,
                        'title'            => 'آزمون تشریحی جدید',
                        'body'             => 'آزمون «' . $this->exam->title . '» برای شما ثبت شد.',
                        'is_from_manager'  => false,
                    ]);
                    NotificationRecipient::create([
                        'notification_id' => $notification->id,
                        'user_id'         => $student->user->id,
                        'is_read'         => false,
                    ]);
                }
            }
        });

        session()->flash('success', 'آزمون با موفقیت برای ' . count($this->selected) . ' دانش‌آموز اختصاص داده شد.');
        $this->selected = [];
    }

    public function render()
    {
        $admin = Auth::guard('admin')->user();

        $students = Student::with('user.personalInformation')
            ->where(function ($q) use ($admin) {
                $q->where('supporter_id', $admin->id)
                    ->orWhere('advisor_id', $admin->id);
            })
            ->when($this->search, function ($q) {
                $q->whereHas('user', function ($qq) {
                    $qq->where('name', 'like', "%{$this->search}%")
                        ->orWhere('mobile', 'like', "%{$this->search}%");
                });
            })
            ->get();

        return view('livewire.admin.essay-exam.exam-assign', compact('students'))
            ->layout('layouts.admin.app');
    }
}
