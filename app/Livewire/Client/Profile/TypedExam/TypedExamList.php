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

    /** 'typed' | 'essay' - فقط برای مقداردهی اولیه‌ی تب فعال در Alpine استفاده می‌شود؛
     *  سوییچ بین تب‌ها کاملاً سمت کلاینت (Alpine) انجام می‌شود و دیگر رفت‌وبرگشتی به
     *  سرور نمی‌زند (چون هر دو لیست همین الان هم در هر رندر لود می‌شوند - رجوع کنید
     *  به render())، پس این پراپرتی دیگر توسط هیچ متدی آپدیت نمی‌شود. */
    public string $activeTab = 'typed';

    // نوع آزمونی که مودال «ورود به آزمون» برایش باز شده - typed یا essay. قبلاً enterExam()
    // برای تشخیص مسیر ریدایرکت به activeTab (تبِ فعلاً انتخاب‌شده) وابسته بود که چون تب دیگر
    // یک رفت‌وبرگشت لایوایر نیست، دیگر معتبر نبود؛ حالا این مقدار مستقیماً همون لحظه‌ای که
    // روی «ورود به آزمون» کلیک می‌شه ثبت می‌شه و به تب فعال هیچ وابستگی نداره.
    public string $confirmingExamType = 'typed';

    public ?int $viewingAnswerSheetFor = null; // essay assignment id for preview
    public int $typedPendingCount = 0;
    public int $essayPendingCount = 0;

    public function confirmEntry(int $assignmentId, string $type = 'typed'): void
    {
        $this->confirmingExamId = $assignmentId;
        $this->confirmingExamType = $type === 'essay' ? 'essay' : 'typed';
    }

    public function closeModal(): void
    {
        $this->confirmingExamId = null;
        $this->confirmingExamType = 'typed';
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

        if ($this->confirmingExamType === 'essay') {
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
        // نکته‌ی مهم درباره‌ی کارایی: قبلاً با with('typedExam.questions') کل سوال‌های هر آزمون
        // (همه‌ی ستون‌ها، برای هر آزمونی که دانش‌آموز تا حالا داشته) روی هر رندر (حتی فقط سوییچ
        // تب typed/essay) از دیتابیس لود می‌شد، درحالی‌که توی view فقط تعدادشون لازمه.
        // با withCount این تبدیل می‌شه به یک subquery سبک COUNT به‌جای لود کامل جدول سوالات.
        return TypedExamAssignment::with([
                'typedExam.settings',
                'typedExam' => fn($q) => $q->withCount(['questions as questions_total']),
                'time',
                'latestAttempt',
            ])
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
        // همون فیکس کارایی: به‌جای لود کامل سوالات تشریحی، فقط تعدادشون.
        return EssayExamAssignment::with([
                'essayExam' => fn($q) => $q->withCount(['questions as questions_total']),
                'time',
                'latestAttempt',
            ])
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
            $this->typedPendingCount = $assignments
                ->whereIn('computed_status', ['not_started', 'available'])
                ->count();
            $this->essayPendingCount = $essayAssignments
                ->whereIn('computed_status', ['not_started', 'available'])
                ->count();
        }

        return view('livewire.client.profile.typed-exam.typed-exam-list', compact(
            'assignments', 'essayAssignments'
        ))->layout('layouts.client.app');
    }
}
