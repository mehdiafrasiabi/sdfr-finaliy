<?php

namespace App\Livewire\Admin\Student\ReportDailyActivities;
use App\Exports\admin\ReportDailyActivitiesSummaryExport;
use App\Models\Student;
use App\Models\AdvisingSession;
use App\Models\DailyReport;
use Carbon\Carbon;
use Artesaos\SEOTools\Traits\SEOTools;
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
    public function mount()
    {
        // دریافت پارامتر course_id از URL
        $this->seoConfig();
    }
    public function seoConfig()
    {
        $this->seo()
            ->setTitle('دانش آموزان');
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
        return Student::query()
            ->with([
                'payment.order.orderItems.product',
                'payment.order.user',
                'user.personalInformation',
                'user.profile'
            ])
            ->withCount([
                'reportdaily as unread_student_replies_count' => function (Builder $query) {
                    $query->whereNotNull('student_reply')
                        ->whereNull('student_reply_seen_at');
                },
            ])
            ->withMax('reportdaily as latest_student_reply_at', 'student_replied_at')
            ->where('supporter_id', $adminId);
    }
    /**
     * Batch-compute not-sent report counts for a set of student IDs.
     * Not-sent = past days in held sessions that have no report, excluding rest days.
     */
    protected function computeNotSentCountsForStudents(array $studentIds): array
    {
        if (empty($studentIds)) return [];

        $today = Carbon::today();

        // Load all held sessions with weekly programs + rest days for these students
        $sessionsByStudent = AdvisingSession::whereIn('student_id', $studentIds)
            ->where('result_status', 'held')
            ->with(['weeklyProgram.restDays'])
            ->get()
            ->groupBy('student_id');

        // Load all report dates grouped by student_id + weekly_program_id key
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
                    if ($day->gt($today)) continue;            // future days don't count yet
                    if (in_array($idx, $restDayIndices)) continue; // rest days are not missing
                    if (!in_array($day->format('Y-m-d'), $existingDates)) {
                        $counts[$studentId]++;
                    }
                }
            }
        }

        return $counts;
    }


    public function render()
    {
        $adminId = auth()->id();

        $studentsQuery = $this->studentsBaseQuery($adminId);

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
        // Compute per-student report counts for the current page
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
