<?php

namespace App\Livewire\Admin\Student\ReportDailyActivities;
use App\Exports\admin\ReportDailyActivitiesSummaryExport;
use App\Models\Student;
use App\Models\AdvisingSession;
use App\Models\DailyReport;
use Carbon\Carbon;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use Morilog\Jalali\Jalalian;
class Index extends Component
{
    use WithPagination,SEOTools;

    public $search = ''; // جستجو در نام دانش‌آموز
    public bool $exportModalOpen = false;
    public string $exportTarget = 'all';
    public string $exportStartDate = '';
    public string $exportEndDate = '';
    public array $selectedStudentIds = [];

    // داده های داشبورد جدید
    public array $dashboardStats = [];

    public function mount()
    {
        $this->seoConfig();
    }
    public function seoConfig()
    {
        $this->seo()
            ->setTitle('گزارشات دانش آموزان');
    }

    public function openExportModal(): void
    {
        $this->resetExportForm();
        $this->exportModalOpen = true;
    }

    public function closeExportModal(): void
    {
        $this->exportModalOpen = false;
        $this->resetExportForm();
    }

    public function updatedExportTarget($value): void
    {
        if ($value === 'all') {
            $this->selectedStudentIds = [];
        }
    }

    public function exportExcel()
    {
        $rules = [
            'exportTarget' => 'required|in:all,selected',
            'exportStartDate' => 'required|string',
            'exportEndDate' => 'required|string',
        ];

        if ($this->exportTarget === 'selected') {
            $rules['selectedStudentIds'] = 'required|array|min:1';
        }

        $this->validate($rules, [
            'selectedStudentIds.required' => 'حداقل یک دانش‌آموز را انتخاب کنید.',
            'selectedStudentIds.min' => 'حداقل یک دانش‌آموز را انتخاب کنید.',
            'exportStartDate.required' => 'تاریخ شروع را وارد کنید.',
            'exportEndDate.required' => 'تاریخ پایان را وارد کنید.',
        ]);

        try {
            $startDate = Jalalian::fromFormat('Y/m/d', $this->exportStartDate)->toCarbon()->startOfDay();
            $endDate = Jalalian::fromFormat('Y/m/d', $this->exportEndDate)->toCarbon()->endOfDay();
        } catch (\Throwable) {
            $this->addError('exportStartDate', 'فرمت تاریخ صحیح نیست. مثال: 1404/09/10');
            return;
        }

        if ($startDate->gt($endDate)) {
            $this->addError('exportStartDate', 'تاریخ شروع نباید بعد از تاریخ پایان باشد.');
            return;
        }

        $studentIds = $this->exportTarget === 'all' ? null : array_map('intval', $this->selectedStudentIds);
        $this->closeExportModal();

        return Excel::download(
            new ReportDailyActivitiesSummaryExport(auth()->id(), $startDate, $endDate, $studentIds),
            'report_daily_activities_summary_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    protected function resetExportForm(): void
    {
        $this->exportTarget = 'all';
        $this->exportStartDate = '';
        $this->exportEndDate = '';
        $this->selectedStudentIds = [];
        $this->resetErrorBag(['selectedStudentIds', 'exportStartDate', 'exportEndDate']);
    }

    protected function studentsBaseQuery(int $adminId): Builder
    {
        $query = Student::query()
            ->with([
                'user.personalInformation',
                'user.profile'
            ]);

        $admin = auth('admin')->user();
        if ($admin?->hasRole('school-manager') && $admin->school_id) {
            return $query->where('school_id', $admin->school_id);
        }

        return $query->where('advisor_id', $adminId);
    }

    protected function computeNotSentCountsForStudents(array $studentIds): array
    {
        if (empty($studentIds)) return [];

        $today = Carbon::today();

        $sessionsByStudent = AdvisingSession::whereIn('student_id', $studentIds)
            ->where('result_status', 'held')
            ->with(['weeklyProgram.restDays'])
            ->get()
            ->groupBy('student_id');

        $reportsByKey = DailyReport::whereIn('student_id', $studentIds)
            ->select(['student_id', 'weekly_program_id', 'report_date'])
            ->get()
            ->groupBy(fn($r) => $r->student_id . '_' . $r->weekly_program_id);

        $counts = [];

        foreach ($studentIds as $studentId) {
            $counts[$studentId] = 0;
            $sessions = $sessionsByStudent->get($studentId, collect());

            foreach ($sessions as $session) {
                $weeklyProgram = $session->weeklyProgram;
                if (!$weeklyProgram) continue;

                $restDayIndices = $weeklyProgram->restDays->pluck('day_index')->toArray();
                $startDate = Carbon::parse($session->activation_date);

                $key = $studentId . '_' . $weeklyProgram->id;
                $existingDates = $reportsByKey->get($key, collect())
                    ->map(fn($r) => Carbon::parse($r->report_date)->format('Y-m-d'))
                    ->toArray();

                for ($idx = 0; $idx <= 7; $idx++) {
                    $day = $startDate->copy()->addDays($idx);
                    if ($day->gt($today)) continue;
                    if (in_array($idx, $restDayIndices)) continue;
                    if (!in_array($day->format('Y-m-d'), $existingDates)) {
                        $counts[$studentId]++;
                    }
                }
            }
        }

        return $counts;
    }

    protected function calculateDashboardData(Builder $studentsQuery): void
    {
        $adminId = auth()->id();
        $studentIds = $studentsQuery->pluck('id')->toArray();
        $totalStudents = count($studentIds);
        $today = Carbon::today()->toDateString();
        $thirtyDaysAgo = Carbon::today()->subDays(30)->toDateString();

        // 1. تعداد گزارش ارسال شده / عدم ارسال
        $reportsSentToday = DailyReport::whereIn('student_id', $studentIds)->whereDate('report_date', $today)->distinct('student_id')->count();
        $reportsNotSentToday = $totalStudents - $reportsSentToday;

        // 3. تعداد گزارشات جبرانی
        $compensatoryReportsCount = DailyReport::whereIn('student_id', $studentIds)->where('is_compensatory', true)->count();

        // 4. میانگین رضایت (rating) - با بررسی وجود ستون
        $averageRating = 0;
        if (Schema::hasColumn('daily_reports', 'rating')) {
            $averageRating = DailyReport::whereIn('student_id', $studentIds)->avg('rating');
        }

        // 6. در انتظار گزارش
        $pendingReportsCount = 0;
        if (Schema::hasColumn('daily_reports', 'status')) {
            $pendingReportsCount = DailyReport::whereIn('student_id', $studentIds)->where('status', 'pending')->count();
        }


        // 7. تعداد تست در نظر گرفته شده / انجام شده / انجام نشده
        $testStats = ['planned' => 0, 'completed' => 0, 'not_completed' => 0];
        // با فرض اینکه این داده ها در ستون lessons هستند
        if (method_exists(DailyReport::class, 'lessons')) {
            $reportsWithLessons = DailyReport::whereIn('student_id', $studentIds)->with('lessons.tests')->get();
            foreach ($reportsWithLessons as $report) {
                foreach($report->lessons as $lesson) {
                    if ($lesson->relationLoaded('tests') && $lesson->tests) {
                         $testStats['planned'] += $lesson->tests->count();
                         $testStats['completed'] += $lesson->tests->where('is_done', true)->count();
                         $testStats['not_completed'] += $lesson->tests->where('is_done', false)->count();
                    }
                }
            }
        }


        // 5. میانگین ارسال گزارشات دانش آموزان
        $dayOfWeekMapping = [1 => 'یکشنبه', 2 => 'دوشنبه', 3 => 'سه‌شنبه', 4 => 'چهارشنبه', 5 => 'پنجشنبه', 6 => 'جمعه', 7 => 'شنبه'];
        $dayOfWeekStats = [];
        if($totalStudents > 0) {
            $dayOfWeekStats = DailyReport::whereIn('student_id', $studentIds)
                ->select(DB::raw('DAYOFWEEK(report_date) as day_of_week'), DB::raw('COUNT(DISTINCT student_id) as student_count'))
                ->groupBy(DB::raw('DAYOFWEEK(report_date)'))
                ->pluck('student_count', 'day_of_week')->mapWithKeys(fn($v, $k) => [$dayOfWeekMapping[$k] => round(($v / $totalStudents) * 100)]);
        }

        // 2. دانش آموزان با بهترین و بدترین ارسال گزارش
        $reportCounts = DailyReport::whereIn('student_id', $studentIds)
            ->whereBetween('report_date', [$thirtyDaysAgo, $today])
            ->select('student_id', DB::raw('COUNT(*) as count'))
            ->groupBy('student_id')
            ->pluck('count', 'student_id');

        $studentReportPercentages = collect($studentIds)->mapWithKeys(function ($id) use ($reportCounts) {
            $daysWithReport = $reportCounts->get($id, 0);
            return [$id => ($daysWithReport / 30) * 100];
        });

        $bestPerformers = $studentReportPercentages->sortDesc()->take(3);
        $worstPerformers = $studentReportPercentages->sort()->take(3);

        $this->dashboardStats = [
            'reportsSentToday' => $reportsSentToday,
            'reportsNotSentToday' => $reportsNotSentToday,
            'totalStudents' => $totalStudents,
            'bestPerformers' => Student::whereIn('id', $bestPerformers->keys())->get(),
            'worstPerformers' => Student::whereIn('id', $worstPerformers->keys())->get(),
            'compensatoryReportsCount' => $compensatoryReportsCount,
            'averageRating' => round($averageRating, 2),
            'dayOfWeekStats' => $dayOfWeekStats,
            'pendingReportsCount' => $pendingReportsCount,
            'testStats' => $testStats,
        ];
    }


    public function render()
    {
        $adminId = auth()->id();
        $baseStudentsQuery = $this->studentsBaseQuery($adminId);

        $this->calculateDashboardData(clone $baseStudentsQuery);

        $studentsQuery = $baseStudentsQuery;

        if ($this->search) {
            $searchTerm = '%' . $this->search . '%';

            $studentsQuery->where(function (Builder $query) use ($searchTerm) {
                $query->whereHas('user.personalInformation', function (Builder $subQuery) use ($searchTerm) {
                    $subQuery->where('name', 'like', $searchTerm);
                })
                    ->orWhereHas('user', function (Builder $subQuery) use ($searchTerm) {
                        $subQuery->where('mobile', 'like', $searchTerm);
                    });
            });
        }

        $students = $studentsQuery->paginate(10);
        $studentIds = $students->pluck('id')->toArray();

        $sentCounts = DailyReport::whereIn('student_id', $studentIds)
            ->selectRaw('student_id, COUNT(*) as total')
            ->groupBy('student_id')
            ->pluck('total', 'student_id');

        $compensatoryCounts = DailyReport::whereIn('student_id', $studentIds)
            ->where('is_compensatory', true)
            ->selectRaw('student_id, COUNT(*) as total')
            ->groupBy('student_id')
            ->pluck('total', 'student_id');

        $notSentCounts = $this->computeNotSentCountsForStudents($studentIds);

        $exportStudents = $this->studentsBaseQuery($adminId)
            ->select(['id', 'user_id'])
            ->with(['user.personalInformation'])
            ->get();

        return view('livewire.admin.student.report-daily-activities.index', [
            'students' => $students,
            'exportStudents' => $exportStudents,
            'sentCounts' => $sentCounts,
            'compensatoryCounts' => $compensatoryCounts,
            'notSentCounts' => $notSentCounts,
        ])->layout('layouts.admin.app');
    }
}
