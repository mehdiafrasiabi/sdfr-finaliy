<?php

namespace App\Livewire\Admin\Dashboard;

use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Models\AdvisingSession;
use App\Models\DailyReport;
use App\Models\PersonalInformation;
use App\Models\ProgramPart;
use App\Models\Student;
use App\Models\StudyPartSession;
class Index extends Component
{
    use SEOTools;

    public function mount()
    {
            $this->seoConfig();
    }
    public function seoConfig()
    {
        $this->seo()
            ->setTitle('پیشخوان');
    }
    public function render()
    {
        $adminId = auth('admin')->id();
        $today = now()->startOfDay();
        $todayDate = $today->toDateString();

        $studentIds = $this->getScopedStudentIds($adminId);

        $pendingReportsQuery = DailyReport::query()
            ->whereIn('student_id', $studentIds)
            ->whereHas('detail', fn($q) => $q->where('status', DailyReport::STATUS_PENDING))
            ->latest('report_date');

        $pendingReportsCount = (clone $pendingReportsQuery)->count('id');

        $pendingReportsByStudent = $pendingReportsQuery
            ->with(['student.user.personalInformation.state', 'detail'])
            ->limit(100)
            ->get()
            ->groupBy('student_id')
            ->map(function ($reports) {
                $latest = $reports->sortByDesc('report_date')->first();
                return [
                    'student' => $latest?->student,
                    'pending_count' => $reports->count(),
                    'latest_report_date' => $latest?->report_date,
                ];
            })
            ->values();

        $studentsWithoutProgramOrSession = $this->getStudentsWithoutProgramOrSession($today, $studentIds);
        $todayCounselingSessions = $this->getTodayCounselingSessions($todayDate, $studentIds);
        $studentsWithoutTwoDaysReport = $this->getStudentsWithoutTwoDaysReport($today, $studentIds);

        [$lowCompletionRows, $noStudyTodayRows, $completionDistribution] = $this->getTodayStudyProgressData($todayDate, $studentIds);

        $weeklyCompletionChart = $this->getWeeklyCompletionChart($studentIds);
        $studentsPerState = $this->getStudentsPerState($studentIds);

        return view('livewire.admin.dashboard.index', [
            'totalStudents' => count($studentIds),
            'totalCounselingSessions' => AdvisingSession::query()->whereIn('student_id', $studentIds)->count(),
            'pendingReportsByStudent' => $pendingReportsByStudent,
            'pendingReportsCount' => $pendingReportsCount,
            'studentsWithoutProgramOrSession' => $studentsWithoutProgramOrSession,
            'todayCounselingSessions' => $todayCounselingSessions,
            'studentsWithoutTwoDaysReport' => $studentsWithoutTwoDaysReport,
            'lowCompletionRows' => $lowCompletionRows,
            'noStudyTodayRows' => $noStudyTodayRows,
            'completionDistribution' => $completionDistribution,
            'weeklyCompletionChart' => $weeklyCompletionChart,
            'studentsPerState' => $studentsPerState,
        ])->layout('layouts.admin.app');
    }

    private function getScopedStudentIds(?int $adminId): array
    {
        if (!$adminId) {
            return [];
        }

        return Student::query()
            ->where(function ($q) use ($adminId) {
                $q->where('advisor_id', $adminId);
            })
            ->pluck('id')
            ->all();
    }

    private function getStudentsWithoutProgramOrSession(Carbon $today, array $studentIds): Collection
    {
        $students = Student::query()
            ->whereIn('id', $studentIds)
            ->with([
                'user.personalInformation.state',
                'weeklyPrograms' => fn($q) => $q->latest('end_date')->limit(1),
                'advisingSessions' => fn($q) => $q->latest('activation_date')->limit(1),
            ])
            ->get();

        return $students->filter(function (Student $student) use ($today) {
            $latestProgram = $student->weeklyPrograms->first();
            $latestSession = $student->advisingSessions->first();

            $hasNoProgram = !$latestProgram;
            $hasNoSession = !$latestSession;
            $sessionOverdue = false;

            if ($latestSession?->activation_date) {
                $nextExpectedSession = Carbon::parse($latestSession->activation_date)->addDays(7);
                $sessionOverdue = $nextExpectedSession->lt($today);
            }

            return $hasNoProgram || $hasNoSession || $sessionOverdue;
        })->values();
    }

    private function getTodayCounselingSessions(string $todayDate, array $studentIds): Collection
    {
        return AdvisingSession::query()
            ->whereIn('student_id', $studentIds)
            ->whereDate('activation_date', $todayDate)
            ->with(['student.user.personalInformation.state', 'advisor.user'])
            ->orderBy('session_time')
            ->get();
    }

    private function getStudentsWithoutTwoDaysReport(Carbon $today, array $studentIds): Collection
    {
        $date1 = $today->copy()->subDay()->toDateString();
        $date2 = $today->copy()->subDays(2)->toDateString();

        $candidateStudents = Student::query()
            ->whereIn('id', $studentIds)
            ->whereHas('advisingSessions')
            ->with(['user.personalInformation.state'])
            ->get();

        return $candidateStudents->filter(function (Student $student) use ($date1, $date2) {
            $reportsCount = DailyReport::query()
                ->where('student_id', $student->id)
                ->whereIn('report_date', [$date1, $date2])
                ->count();

            return $reportsCount === 0;
        })->values();
    }

    private function getTodayStudyProgressData(string $todayDate, array $studentIds): array
    {
        $partsToday = ProgramPart::query()
            ->whereDate('part_date', $todayDate)
            ->whereHas('weeklyProgram', fn(Builder $q) => $q->whereIn('student_id', $studentIds))
            ->select(['id', 'weekly_program_id'])
            ->with('weeklyProgram:id,student_id')
            ->get();

        $byStudentParts = [];
        foreach ($partsToday as $part) {
            if (!$part->weeklyProgram?->student_id) {
                continue;
            }
            $sid = $part->weeklyProgram->student_id;
            $byStudentParts[$sid][] = $part->id;
        }

        if (empty($byStudentParts)) {
            return [collect(), collect(), ['labels' => [], 'values' => []]];
        }

        $studentIds = array_keys($byStudentParts);

        $students = Student::query()
            ->whereIn('id', $studentIds)
            ->with(['user.personalInformation.state'])
            ->get()
            ->keyBy('id');

        $allPartIds = collect($byStudentParts)->flatten()->unique()->values()->all();

        $completedByStudent = StudyPartSession::query()
            ->whereIn('program_part_id', $allPartIds)
            ->whereDate('started_at', $todayDate)
            ->where(function ($q) {
                $q->where('is_completed', true)->orWhere('duration_seconds', '>', 0);
            })
            ->get()
            ->groupBy('student_id')
            ->map(fn($rows) => $rows->pluck('program_part_id')->unique()->count());

        $rows = collect();
        foreach ($byStudentParts as $studentId => $studentPartIds) {
            $planned = count(array_unique($studentPartIds));
            $completed = (int) ($completedByStudent[$studentId] ?? 0);
            $percentage = $planned > 0 ? (int) round(($completed / $planned) * 100) : 0;

            $rows->push([
                'student' => $students[$studentId] ?? null,
                'planned' => $planned,
                'completed' => $completed,
                'percentage' => $percentage,
            ]);
        }

        $distributionBuckets = [
            '10-30' => 0,
            '31-50' => 0,
            '51-70' => 0,
            '71-90' => 0,
            '91-100' => 0,
        ];

        foreach ($rows as $row) {
            $p = $row['percentage'];
            if ($p >= 10 && $p <= 30) $distributionBuckets['10-30']++;
            elseif ($p <= 50) $distributionBuckets['31-50']++;
            elseif ($p <= 70) $distributionBuckets['51-70']++;
            elseif ($p <= 90) $distributionBuckets['71-90']++;
            elseif ($p <= 100) $distributionBuckets['91-100']++;
        }

        return [
            $rows->where('percentage', '<', 50)->values(),
            $rows->where('completed', 0)->values(),
            [
                'labels' => array_keys($distributionBuckets),
                'values' => array_values($distributionBuckets),
            ],
        ];
    }

    private function getWeeklyCompletionChart(array $studentIds): array
    {
        $weeks = collect(range(5, 0))->map(fn($w) => now()->startOfWeek()->subWeeks($w));
        $weeks = $weeks->push(now()->startOfWeek());

        $labels = [];
        $values = [];

        foreach ($weeks as $weekStart) {
            $weekEnd = $weekStart->copy()->endOfWeek();
            $plannedParts = ProgramPart::query()
                ->whereBetween('part_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
                ->join('weekly_programs', 'weekly_programs.id', '=', 'program_parts.weekly_program_id')
                ->whereIn('weekly_programs.student_id', $studentIds)
                ->selectRaw('weekly_programs.student_id, COUNT(program_parts.id) as planned_count')
                ->groupBy('weekly_programs.student_id')
                ->get()
                ->keyBy('student_id');

            $completedParts = StudyPartSession::query()
                ->whereIn('student_id', $studentIds)
                ->whereDate('started_at', '>=', $weekStart->toDateString())
                ->whereDate('started_at', '<=', $weekEnd->toDateString())
                ->where(function ($q) {
                    $q->where('is_completed', true)->orWhere('duration_seconds', '>', 0);
                })
                ->selectRaw('student_id, COUNT(DISTINCT program_part_id) as done_count')
                ->groupBy('student_id')
                ->get()
                ->keyBy('student_id');

            $percentages = [];
            foreach ($plannedParts as $studentId => $plannedRow) {
                $planned = (int) $plannedRow->planned_count;
                $done = (int) ($completedParts[$studentId]->done_count ?? 0);
                if ($planned > 0) {
                    $percentages[] = ($done / $planned) * 100;
                }
            }

            $labels[] = $weekStart->format('Y/m/d');
            $values[] = empty($percentages) ? 0 : round(collect($percentages)->avg(), 1);
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    private function getStudentsPerState(array $studentIds): Collection
    {
        return PersonalInformation::query()
            ->whereIn('personal_information.user_id', Student::query()->whereIn('id', $studentIds)->pluck('user_id'))
            ->join('states', 'states.id', '=', 'personal_information.state_id')
            ->selectRaw('states.name as state_name, COUNT(personal_information.id) as students_count')
            ->groupBy('states.name')
            ->orderByDesc('students_count')
            ->get();
    }
}
