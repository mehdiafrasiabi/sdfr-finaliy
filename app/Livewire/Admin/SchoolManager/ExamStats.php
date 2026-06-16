<?php

namespace App\Livewire\Admin\SchoolManager;

use App\Models\EssayExamAttempt;
use App\Models\Student;
use App\Models\TypedExamAttempt;
use Livewire\Component;

/**
 * آمار آزمون‌های دانش‌آموزان مدرسه برای مدیر (M8):
 *  - آزمون‌های تستی (TypedExamAttempt): تعداد، میانگین درصد، و جزئیات هر آزمون
 *    (درست/غلط/بی‌پاسخ، درصد).
 *  - آزمون‌های تشریحی (EssayExamAttempt): تعداد، میانگین درصد، و جزئیات (نمره از کل، درصد).
 */
class ExamStats extends Component
{
    public const FIELD_LABELS = [
        'math'         => 'ریاضی',
        'experimental' => 'تجربی',
        'human'        => 'انسانی',
    ];

    private const DETAIL_LIMIT = 200;

    public string $gradeFilter = '';
    public string $fieldFilter = '';

    public function mount(): void
    {
        $this->schoolId();
    }

    private function schoolId(): ?int
    {
        $admin = auth('admin')->user();
        abort_unless($admin?->school_id || $admin?->hasRole('super admin'), 403);
        return $admin?->school_id;
    }

    private function studentIds(): array
    {
        return Student::where('school_id', $this->schoolId())
            ->when($this->gradeFilter, fn($q) => $q->where('grade', $this->gradeFilter))
            ->when($this->fieldFilter, fn($q) => $q->where('field', $this->fieldFilter))
            ->pluck('id')
            ->all();
    }

    public function render()
    {
        $studentIds = $this->studentIds();

        // ---- آزمون‌های تستی ----
        $typedQuery = TypedExamAttempt::whereIn('student_id', $studentIds)
            ->where('is_finished', true)
            ->whereNotNull('submitted_at');

        $typedCount = (clone $typedQuery)->count();
        $typedAvg   = $typedCount ? round((clone $typedQuery)->avg('score'), 1) : 0;

        $typedDetails = (clone $typedQuery)
            ->with(['student.user', 'assignment.typedExam'])
            ->withCount([
                'answers as total_answers',
                'answers as correct_count' => fn($q) => $q->where('is_correct', true),
                'answers as wrong_count'   => fn($q) => $q->where('is_correct', false),
            ])
            ->latest('submitted_at')
            ->limit(self::DETAIL_LIMIT)
            ->get()
            ->map(function (TypedExamAttempt $a) {
                $unanswered = max(($a->total_answers ?? 0) - ($a->correct_count ?? 0) - ($a->wrong_count ?? 0), 0);
                return [
                    'exam'        => $a->assignment?->typedExam?->title ?? '—',
                    'student'     => $a->student?->user?->name ?? '—',
                    'correct'     => $a->correct_count ?? 0,
                    'wrong'       => $a->wrong_count ?? 0,
                    'unanswered'  => $unanswered,
                    'total'       => $a->total_answers ?? 0,
                    'score'       => $a->score !== null ? (float) $a->score : null,
                    'date'        => $a->submitted_at ? jdate($a->submitted_at)->format('Y/m/d') : '—',
                ];
            });

        // ---- آزمون‌های تشریحی ----
        $essayQuery = EssayExamAttempt::whereHas('assignment', fn($q) => $q->whereIn('student_id', $studentIds))
            ->whereIn('status', ['submitted', 'graded']);

        $gradedQuery = EssayExamAttempt::whereHas('assignment', fn($q) => $q->whereIn('student_id', $studentIds))
            ->where('status', 'graded');

        $essayCount = (clone $essayQuery)->count();

        $essayDetails = (clone $essayQuery)
            ->with(['assignment.student.user', 'assignment.essayExam'])
            ->latest('submitted_at')
            ->limit(self::DETAIL_LIMIT)
            ->get()
            ->map(function (EssayExamAttempt $a) {
                $total  = (float) ($a->assignment?->essayExam?->total_score ?? 0);
                $score  = $a->total_score !== null ? (float) $a->total_score : null;
                $percent = ($total > 0 && $score !== null) ? round($score / $total * 100, 1) : null;
                return [
                    'exam'    => $a->assignment?->essayExam?->title ?? '—',
                    'student' => $a->assignment?->student?->user?->name ?? '—',
                    'score'   => $score,
                    'total'   => $total ?: null,
                    'percent' => $percent,
                    'status'  => $a->status,
                    'date'    => $a->submitted_at ? jdate($a->submitted_at)->format('Y/m/d') : '—',
                ];
            });

        // میانگین درصدِ تشریحیِ نمره‌داده‌شده
        $essayPercents = $essayDetails->where('status', 'graded')->pluck('percent')->filter(fn($v) => $v !== null);
        $essayAvg = $essayPercents->isNotEmpty() ? round($essayPercents->avg(), 1) : 0;

        return view('livewire.admin.school-manager.exam-stats', [
            'typedCount'   => $typedCount,
            'typedAvg'     => $typedAvg,
            'typedDetails' => $typedDetails,
            'essayCount'   => $essayCount,
            'essayGraded'  => (clone $gradedQuery)->count(),
            'essayAvg'     => $essayAvg,
            'essayDetails' => $essayDetails,
            'fieldLabels'  => self::FIELD_LABELS,
            'detailLimit'  => self::DETAIL_LIMIT,
        ])->layout('layouts.admin.app');
    }
}
