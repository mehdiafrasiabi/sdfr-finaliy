<?php

namespace App\Livewire\Admin\SchoolManager;

use App\Models\DailyReport;
use App\Models\EssayExamAttempt;
use App\Models\SchoolStudentGrade;
use App\Models\SmartReportCard;
use App\Models\Student;
use App\Models\StudyPartSession;
use App\Models\TypedExamAttempt;
use Illuminate\Support\Collection;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

/**
 * وضعیت تحصیلی (بازطراحی‌شده): نمایش نفرات برتر مدرسه در ماه انتخابی به‌جای طبقه‌بندی ABCD.
 *  - ۱۰ نفر برتر ساعت مطالعه
 *  - ۱۰ نفر برتر ارسال گزارش روزانه (هدف ۲۸ روز از ماه به بالا)
 *  - نفرات برتر معدل کارنامهٔ ماهانه
 *  - نفرات برتر میانگین آزمون تستی و میانگین آزمون تشریحی
 */
class AcademicStatus extends Component
{
    public const TOP_LIMIT = 10;
    public const REPORT_TARGET = 28;

    public const FIELD_LABELS = [
        'math'         => 'ریاضی',
        'experimental' => 'تجربی',
        'human'        => 'انسانی',
    ];

    public int $jalaliYear;
    public int $jalaliMonth;
    public string $gradeFilter = '';
    public string $fieldFilter = '';

    public function mount(): void
    {
        $this->schoolId();
        $now = Jalalian::now();
        $this->jalaliYear  = (int) $now->getYear();
        $this->jalaliMonth = (int) $now->getMonth();
    }

    private function schoolId(): ?int
    {
        $admin = auth('admin')->user();
        abort_unless($admin?->school_id || $admin?->hasRole('super admin'), 403);
        return $admin?->school_id;
    }

    private function monthlyAverages(Collection $studentIds, string $monthKey): Collection
    {
        return SchoolStudentGrade::whereIn('student_id', $studentIds)
            ->where('jalali_month', $monthKey)
            ->get()
            ->groupBy('student_id')
            ->map(function ($rows) {
                $avgs = $rows->map(fn($r) => $r->subject_average)->filter(fn($v) => $v !== null);
                return $avgs->isNotEmpty() ? round($avgs->avg(), 2) : null;
            })
            ->filter(fn($v) => $v !== null);
    }

    public function render()
    {
        $students = Student::with('user')
            ->where('school_id', $this->schoolId())
            ->whereNotNull('grade')
            ->when($this->gradeFilter, fn($q) => $q->where('grade', $this->gradeFilter))
            ->when($this->fieldFilter, fn($q) => $q->where('field', $this->fieldFilter))
            ->get();

        $studentIds = $students->pluck('id');
        $nameById = $students->mapWithKeys(fn($s) => [$s->id => $s->user?->name ?? '—']);
        $userIdById = $students->mapWithKeys(fn($s) => [$s->id => $s->user_id]);

        $monthKey  = sprintf('%04d-%02d', $this->jalaliYear, $this->jalaliMonth);
        $range     = SmartReportCard::jalaliMonthRange($this->jalaliYear, $this->jalaliMonth);
        $monthDays = $range['days'];

        // ۱) برترین ساعت مطالعه (این ماه)
        $studySeconds = StudyPartSession::whereIn('student_id', $studentIds)
            ->whereBetween('started_at', [$range['start'], $range['end']])
            ->selectRaw('student_id, SUM(duration_seconds) as secs')
            ->groupBy('student_id')->pluck('secs', 'student_id');
        $topStudy = $studySeconds->map(fn($secs, $sid) => [
            'name'  => $nameById[$sid] ?? '—',
            'user'  => $userIdById[$sid] ?? null,
            'hours' => round(((int) $secs) / 3600, 1),
        ])->sortByDesc('hours')->take(self::TOP_LIMIT)->values();

        // ۲) برترین ارسال گزارش روزانه (روزهای دارای گزارشِ غیرجبرانی در ماه)
        $reportDays = DailyReport::whereIn('student_id', $studentIds)
            ->where('is_compensatory', false)
            ->whereBetween('report_date', [$range['start']->toDateString(), $range['end']->toDateString()])
            ->get(['student_id', 'report_date'])
            ->groupBy('student_id')
            ->map(fn($rows) => $rows->pluck('report_date')->map(fn($d) => \Illuminate\Support\Carbon::parse($d)->toDateString())->unique()->count());
        $topReports = $reportDays->map(fn($days, $sid) => [
            'name' => $nameById[$sid] ?? '—',
            'days' => (int) $days,
        ])->sortByDesc('days')->take(self::TOP_LIMIT)->values();

        // ۳) برترین معدل کارنامهٔ ماهانه
        $averages = $this->monthlyAverages($studentIds, $monthKey);
        $topGrades = $averages->map(fn($avg, $sid) => [
            'name' => $nameById[$sid] ?? '—',
            'user' => $userIdById[$sid] ?? null,
            'avg'  => $avg,
        ])->sortByDesc('avg')->take(self::TOP_LIMIT)->values();

        // ۴) برترین میانگین آزمون تستی (درصد)
        $typedAvg = TypedExamAttempt::whereIn('student_id', $studentIds)
            ->where('is_finished', true)
            ->whereBetween('submitted_at', [$range['start'], $range['end']])
            ->selectRaw('student_id, AVG(score) as avg_score')
            ->groupBy('student_id')->pluck('avg_score', 'student_id');
        $topTyped = $typedAvg->map(fn($avg, $sid) => [
            'name'    => $nameById[$sid] ?? '—',
            'percent' => round((float) $avg, 1),
        ])->sortByDesc('percent')->take(self::TOP_LIMIT)->values();

        // ۵) برترین میانگین آزمون تشریحی (درصد)
        $essayAttempts = EssayExamAttempt::with('assignment.essayExam')
            ->whereHas('assignment', fn($q) => $q->whereIn('student_id', $studentIds))
            ->where('status', 'graded')
            ->whereBetween('submitted_at', [$range['start'], $range['end']])
            ->get();
        $essayByStudent = [];
        foreach ($essayAttempts as $a) {
            $sid   = $a->assignment?->student_id;
            $total = (float) ($a->assignment?->essayExam?->total_score ?? 0);
            if (!$sid || $total <= 0 || $a->total_score === null) { continue; }
            $essayByStudent[$sid][] = (float) $a->total_score / $total * 100;
        }
        $topEssay = collect($essayByStudent)->map(fn($percents, $sid) => [
            'name'    => $nameById[$sid] ?? '—',
            'percent' => round(array_sum($percents) / max(count($percents), 1), 1),
        ])->sortByDesc('percent')->take(self::TOP_LIMIT)->values();

        return view('livewire.admin.school-manager.academic-status', [
            'monthNames'    => SmartReportCard::MONTH_NAMES,
            'yearOptions'   => range($this->jalaliYear, $this->jalaliYear - 2),
            'fieldLabels'   => self::FIELD_LABELS,
            'topStudy'      => $topStudy,
            'topReports'    => $topReports,
            'topGrades'     => $topGrades,
            'topTyped'      => $topTyped,
            'topEssay'      => $topEssay,
            'monthDays'     => $monthDays,
            'reportTarget'  => self::REPORT_TARGET,
        ])->layout('layouts.admin.app');
    }
}
