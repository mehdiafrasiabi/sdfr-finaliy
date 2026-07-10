<?php

namespace App\Livewire\Admin\EducationalManager\PhoneAcquisition;

use App\Models\Admin;
use App\Models\AdvisingSession;
use App\Models\ClassificationProject;
use App\Models\DailyReport;
use App\Models\EssayExamAssignment;
use App\Models\PhoneCall;
use App\Models\PhoneLead;
use App\Models\PhoneLeadAssignment;
use App\Models\ProgramPart;
use App\Models\RegistrationGoal;
use App\Models\Student;
use App\Models\StudentClassification;
use App\Models\StudentClassificationSubmission;
use App\Models\StudyPartSession;
use App\Models\TypedExamAssignment;
use App\Support\ClassificationProgress;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

/**
 * داشبورد جامع مدیر آموزشی:
 * هم آمار آموزشی دانش‌آموزانِ غیرآزمایشی را نشان می‌دهد
 * و هم آمارهای جذب تلفنی را در همان صفحه نگه می‌دارد.
 */
class Dashboard extends Component
{
    private const DETAIL_LIMIT = 100;

    public function render()
    {
        $regularStudents = Student::query()
            ->where('is_trial', false)
            ->with(['user:id,name', 'advisor:id,name'])
            ->get(['id', 'user_id', 'advisor_id']);

        $studentIds = $regularStudents->pluck('id')->filter()->values();
        $userIds = $regularStudents->pluck('user_id')->filter()->values();

        $studentStats = $this->buildStudentStats($regularStudents);
        $advisorStats = $this->buildAdvisorStats();
        $sessionStats = $this->buildSessionStats($studentIds);
        $reportStats = $this->buildReportStats($studentIds);
        $studyStats = $this->buildStudyStats($studentIds, $regularStudents->count());
        $testStats = $this->buildTestStats($studentIds);
        $classificationStats = $this->buildClassificationStats($regularStudents, $userIds);
        $examStats = $this->buildExamStats($studentIds);
        $phoneStats = $this->buildPhoneStats();

        $registeredTotal = (int) ($phoneStats['successful']['registered'] ?? 0);

        $teamGoal = RegistrationGoal::team()->latest()->first();

        $consultantGoals = RegistrationGoal::whereNotNull('admin_id')
            ->with('admin:id,name')
            ->latest()
            ->get()
            ->map(function (RegistrationGoal $goal) {
                $goal->achieved = PhoneCall::where('admin_id', $goal->admin_id)
                    ->where('result', PhoneCall::RESULT_REGISTERED)
                    ->count();

                return $goal;
            });

        return view('livewire.admin.educational-manager.phone-acquisition.dashboard', [
            'studentStats'      => $studentStats,
            'advisorStats'      => $advisorStats,
            'sessionStats'      => $sessionStats,
            'reportStats'       => $reportStats,
            'studyStats'        => $studyStats,
            'testStats'         => $testStats,
            'classificationStats' => $classificationStats,
            'examStats'         => $examStats,
            'phoneStats'        => $phoneStats,
            'teamGoal'          => $teamGoal,
            'consultantGoals'   => $consultantGoals,
            'registeredTotal'   => $registeredTotal,
        ])->layout('layouts.admin.app');
    }

    protected function buildStudentStats(Collection $students): array
    {
        return [
            'total' => $students->count(),
        ];
    }

    protected function buildAdvisorStats(): array
    {
        return [
            'regular'           => Admin::role('مشاور تحصیلی')->count(),
            'phoneAcquisition'  => Admin::role('مشاور جذب تلفنی')->count(),
            'trialAcquisition'  => Admin::role('site acquisition')->count(),
        ];
    }

    protected function buildSessionStats(Collection $studentIds): array
    {
        $base = AdvisingSession::query()->whereIn('student_id', $studentIds);
        $heldRegularCount = (clone $base)
            ->where('result_status', AdvisingSession::RESULT_HELD)
            ->where(function ($q) {
                $q->where('is_makeup', false)->orWhereNull('is_makeup');
            })
            ->count();

        $heldMakeupCount = (clone $base)
            ->where('result_status', AdvisingSession::RESULT_HELD)
            ->where('is_makeup', true)
            ->count();

        $overdueQuery = (clone $base)
            ->with(['student.user:id,name', 'advisor:id,name'])
            ->whereNotNull('activation_date')
            ->whereDate('activation_date', '<=', now()->subDays(7)->toDateString())
            ->where(function ($q) {
                $q->whereNull('result_status')
                    ->orWhere('result_status', '!=', AdvisingSession::RESULT_HELD);
            });

        $overdueCount = (clone $overdueQuery)->count();

        $overdueRows = $overdueQuery
            ->orderBy('activation_date')
            ->limit(self::DETAIL_LIMIT)
            ->get()
            ->map(function (AdvisingSession $session) {
                return [
                    'student'   => $session->student?->user?->name ?? '—',
                    'advisor'   => $session->advisor?->name ?? '—',
                    'date'      => $session->activation_date,
                    'result'    => $session->result_label,
                    'is_makeup' => (bool) $session->is_makeup,
                ];
            });

        return [
            'heldRegularCount' => $heldRegularCount,
            'heldMakeupCount'  => $heldMakeupCount,
            'overdueCount'     => $overdueCount,
            'overdueRows'      => $overdueRows,
        ];
    }

    protected function buildReportStats(Collection $studentIds): array
    {
        $totalStudents = $studentIds->count();
        $start = Carbon::today()->subDays(6);
        $end = Carbon::today()->endOfDay();

        $weeklyReports = DailyReport::query()
            ->whereIn('student_id', $studentIds)
            ->where('is_compensatory', false)
            ->whereBetween('report_date', [$start->toDateString(), $end->toDateString()])
            ->get(['student_id', 'report_date']);

        $uniqueReportDays = $weeklyReports
            ->map(fn (DailyReport $report) => $report->student_id . '|' . Carbon::parse($report->report_date)->toDateString())
            ->unique()
            ->count();

        $sentAverageDays = $totalStudents > 0 ? round($uniqueReportDays / $totalStudents, 1) : 0;
        $notSentAverageDays = max(round(7 - $sentAverageDays, 1), 0);

        $pendingReportsQuery = DailyReport::query()
            ->whereIn('student_id', $studentIds)
            ->whereHas('detail', fn ($q) => $q->where('status', DailyReport::STATUS_PENDING));

        $pendingReportsCount = (clone $pendingReportsQuery)->count();

        $pendingByAdvisor = (clone $pendingReportsQuery)
            ->select('admin_id', DB::raw('COUNT(*) as pending_count'))
            ->with('admin:id,name')
            ->groupBy('admin_id')
            ->orderByDesc('pending_count')
            ->get()
            ->map(fn (DailyReport $report) => [
                'advisor' => $report->admin?->name ?? '—',
                'count'   => (int) $report->pending_count,
            ]);

        return [
            'sentAverageDays'      => $sentAverageDays,
            'notSentAverageDays'   => $notSentAverageDays,
            'sentAveragePercent'   => round(($sentAverageDays / 7) * 100, 1),
            'notSentAveragePercent'=> round(($notSentAverageDays / 7) * 100, 1),
            'pendingReportsCount'  => $pendingReportsCount,
            'pendingByAdvisor'     => $pendingByAdvisor,
        ];
    }

    protected function buildStudyStats(Collection $studentIds, int $studentCount): array
    {
        $start = Carbon::today()->subDays(6)->startOfDay();
        $end = Carbon::today()->endOfDay();

        $totalSeconds = StudyPartSession::query()
            ->whereIn('student_id', $studentIds)
            ->where('is_completed', true)
            ->whereBetween('started_at', [$start, $end])
            ->sum('duration_seconds');

        $averagePerDaySeconds = $studentCount > 0
            ? (int) round($totalSeconds / max($studentCount * 7, 1))
            : 0;

        return [
            'totalWeeklySeconds'      => (int) $totalSeconds,
            'averagePerDaySeconds'    => $averagePerDaySeconds,
            'averagePerDayLabel'      => $this->formatDuration($averagePerDaySeconds),
        ];
    }

    protected function buildTestStats(Collection $studentIds): array
    {
        $planned = (int) ProgramPart::query()
            ->whereHas('weeklyProgram', fn ($q) => $q->whereIn('student_id', $studentIds))
            ->sum('test_count');

        $completed = (int) DB::table('daily_report_parts')
            ->join('daily_reports', 'daily_reports.id', '=', 'daily_report_parts.daily_report_id')
            ->whereIn('daily_reports.student_id', $studentIds->all())
            ->sum('daily_report_parts.tests_done');

        return [
            'planned'      => $planned,
            'completed'    => $completed,
            'notCompleted' => max($planned - $completed, 0),
        ];
    }

    protected function buildClassificationStats(Collection $students, Collection $userIds): array
    {
        $project = ClassificationProject::query()
            ->where('is_active', true)
            ->where('is_trial', false)
            ->where('start_at', '<=', now())
            ->where('end_at', '>=', now())
            ->first()
            ?? ClassificationProject::query()
                ->where('is_active', true)
                ->where('is_trial', false)
                ->where('end_at', '<', now())
                ->latest('end_at')
                ->first();

        $nextProject = ClassificationProject::query()
            ->where('is_active', true)
            ->where('is_trial', false)
            ->where('start_at', '>', now())
            ->orderBy('start_at')
            ->first();

        $stats = [
            'project'              => $project,
            'nextProject'          => $nextProject,
            'classifiedCount'      => 0,
            'unclassifiedCount'    => $students->count(),
            'studentLevelCounts'   => ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0],
            'averageLevelLabel'    => '—',
            'strongSubjects'       => collect(),
            'weakSubjects'         => collect(),
        ];

        if (! $project || $userIds->isEmpty()) {
            return $stats;
        }

        $submittedCount = StudentClassificationSubmission::query()
            ->where('classification_project_id', $project->id)
            ->whereIn('user_id', $userIds)
            ->where('is_completed', true)
            ->count();

        if ($submittedCount === 0) {
            $submittedCount = StudentClassification::query()
                ->where('classification_project_id', $project->id)
                ->whereIn('user_id', $userIds)
                ->distinct('user_id')
                ->count('user_id');
        }

        $studentAverages = StudentClassification::query()
            ->where('classification_project_id', $project->id)
            ->whereIn('user_id', $userIds)
            ->select('user_id', DB::raw('AVG(rating) as avg_rating'))
            ->groupBy('user_id')
            ->get();

        $studentLevelCounts = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0];
        foreach ($studentAverages as $row) {
            $label = ClassificationProgress::ratingLabel((float) $row->avg_rating);
            if (isset($studentLevelCounts[$label])) {
                $studentLevelCounts[$label]++;
            }
        }

        $overallAverage = $studentAverages->avg('avg_rating');
        $subjectSummaries = $this->buildClassificationSubjectSummaries($userIds->all());

        $stats['classifiedCount'] = $submittedCount;
        $stats['unclassifiedCount'] = max($students->count() - $submittedCount, 0);
        $stats['studentLevelCounts'] = $studentLevelCounts;
        $stats['averageLevelLabel'] = $overallAverage ? ClassificationProgress::ratingLabel((float) $overallAverage) : '—';
        $stats['strongSubjects'] = $subjectSummaries->sortByDesc('avg')->take(5)->values();
        $stats['weakSubjects'] = $subjectSummaries
            ->sortByDesc(fn (array $row) => ($row['C'] + $row['D']) * 1000 - $row['avg'])
            ->take(5)
            ->values();

        return $stats;
    }

    protected function buildClassificationSubjectSummaries(array $userIds): Collection
    {
        $ratings = ClassificationProgress::subjectRatings($userIds);
        $subjects = $ratings['subjects'];
        $data = $ratings['data'];

        $summary = [];

        foreach ($data as $subjectSeriesByUser) {
            foreach ($subjectSeriesByUser as $subjectId => $series) {
                $latest = ClassificationProgress::latestPrevious($series)['latest'];
                if ($latest === null) {
                    continue;
                }

                $rounded = max(1, min(4, (int) round($latest)));
                $label = StudentClassification::RATINGS[$rounded] ?? '—';

                if (! isset($summary[$subjectId])) {
                    $summary[$subjectId] = [
                        'subject' => $subjects[$subjectId] ?? '—',
                        'avg'     => 0,
                        'total'   => 0,
                        'A'       => 0,
                        'B'       => 0,
                        'C'       => 0,
                        'D'       => 0,
                    ];
                }

                $summary[$subjectId]['avg'] += $latest;
                $summary[$subjectId]['total']++;

                if (isset($summary[$subjectId][$label])) {
                    $summary[$subjectId][$label]++;
                }
            }
        }

        return collect($summary)
            ->filter(fn (array $row) => $row['total'] > 0)
            ->map(function (array $row) {
                $row['avg'] = round($row['avg'] / $row['total'], 2);
                $row['avg_label'] = ClassificationProgress::ratingLabel($row['avg']);

                return $row;
            })
            ->values();
    }

    protected function buildExamStats(Collection $studentIds): array
    {
        $typedAssignments = TypedExamAssignment::query()->whereIn('student_id', $studentIds);
        $essayAssignments = EssayExamAssignment::query()->whereIn('student_id', $studentIds);

        $typedTotal = (clone $typedAssignments)->count();
        $typedDone = (clone $typedAssignments)->where('status', 'completed')->count();

        $essayTotal = (clone $essayAssignments)->count();
        $essayDone = (clone $essayAssignments)->whereIn('status', ['submitted', 'graded'])->count();
        $essayGraded = (clone $essayAssignments)->where('status', 'graded')->count();
        $essaySubmittedUngraded = (clone $essayAssignments)->where('status', 'submitted')->count();

        return [
            'typedTotal'              => $typedTotal,
            'essayTotal'              => $essayTotal,
            'doneTotal'               => $typedDone + $essayDone,
            'notDoneTotal'            => max(($typedTotal - $typedDone), 0) + max(($essayTotal - $essayDone), 0),
            'essayGraded'             => $essayGraded,
            'essaySubmittedUngraded'  => $essaySubmittedUngraded,
        ];
    }

    protected function buildPhoneStats(): array
    {
        $totalLeads = PhoneLead::count();

        $resultCounts = PhoneCall::where('connected', true)
            ->selectRaw('result, COUNT(*) as c')
            ->groupBy('result')
            ->pluck('c', 'result');

        $failCounts = PhoneCall::where('connected', false)
            ->selectRaw('fail_reason, COUNT(*) as c')
            ->groupBy('fail_reason')
            ->pluck('c', 'fail_reason');

        $successful = [
            'registered'  => (int) ($resultCounts[PhoneCall::RESULT_REGISTERED] ?? 0),
            'follow_up'   => (int) ($resultCounts[PhoneCall::RESULT_FOLLOW_UP] ?? 0),
            'no_interest' => (int) ($resultCounts[PhoneCall::RESULT_NO_INTEREST] ?? 0),
        ];

        $unsuccessful = [
            'off'       => (int) ($failCounts[PhoneCall::FAIL_OFF] ?? 0),
            'no_answer' => (int) ($failCounts[PhoneCall::FAIL_NO_ANSWER] ?? 0),
            'rejected'  => (int) ($failCounts[PhoneCall::FAIL_REJECTED] ?? 0),
            'wrong'     => (int) ($failCounts[PhoneCall::FAIL_WRONG] ?? 0),
        ];

        $successfulTotal = array_sum($successful);
        $unsuccessfulTotal = array_sum($unsuccessful);

        $notCalledQuery = PhoneLead::query()
            ->with('activeAssignment.consultant:id,name')
            ->whereHas('assignments', fn ($q) => $q->where('status', PhoneLeadAssignment::STATUS_ACTIVE))
            ->whereDoesntHave('calls')
            ->latest();

        $notCalledCount = (clone $notCalledQuery)->count();

        return [
            'totalLeads'         => $totalLeads,
            'successful'         => $successful,
            'unsuccessful'       => $unsuccessful,
            'successfulTotal'    => $successfulTotal,
            'unsuccessfulTotal'  => $unsuccessfulTotal,
            'totalCalls'         => $successfulTotal + $unsuccessfulTotal,
            'totalTalkMinutes'   => (int) round((PhoneCall::sum('talk_duration_seconds') ?? 0) / 60),
            'answeredCount'      => PhoneCall::where('connected', true)->count(),
            'unansweredCount'    => PhoneCall::where('connected', false)->count(),
            'notCalledCount'     => $notCalledCount,
            'notCalled'          => $notCalledQuery->limit(self::DETAIL_LIMIT)->get(),
        ];
    }

    protected function formatDuration(int $seconds): string
    {
        if ($seconds <= 0) {
            return '0 دقیقه';
        }

        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);

        if ($hours > 0) {
            return sprintf('%d ساعت و %02d دقیقه', $hours, $minutes);
        }

        return sprintf('%d دقیقه', max($minutes, 1));
    }
}
