<?php


namespace App\Livewire\Admin\Student\ReportDailyActivities;


use App\Exports\DailyReportExport;

use App\Models\AdvisingSession;

use App\Models\DailyReport;

use App\Models\User;

use App\Models\WeeklyProgram;

use Artesaos\SEOTools\Traits\SEOTools;

use Illuminate\Support\Facades\Validator;

use Livewire\Component;

use Livewire\WithPagination;

use Maatwebsite\Excel\Facades\Excel;

use Morilog\Jalali\Jalalian;


class Detail extends Component

{

    use WithPagination, SEOTools;


    public $studentName;

    public $studentId;

    public $userId;


    // Session Selection

    public ?int $selectedSessionId = null;

    public array $sessions = [];


    // Month Filter (multi-select tags)

    public array $selectedMonths = [];

    public array $monthOptions = [];


    // Status Filter

    public string $statusFilter = 'all'; // all, approved, rejected, not_sent


    // Stats

    public array $stats = [];


    // Comment Modal

    public bool $commentModalOpen = false;

    public ?int $commentReportId = null;

    public string $advisorCommentInput = '';

    public bool $advisorCommentReadonly = false;

    public string $commentStudentName = '';

    public ?string $commentStudentReply = null;


    // Detail Modal

    public bool $detailModalOpen = false;

    public ?int $selectedReportId = null;

    public array $reportPartsDetails = [];

    public array $selectedReportData = [];


    // Excel Export Modal

    public bool $exportModalOpen = false;

    public string $exportStartDate = '';

    public string $exportEndDate = '';


    protected $paginationTheme = 'bootstrap';


    public function mount(User $student)

    {

        $this->userId = $student->id;

        $this->studentId = $student->student->id;

        $this->studentName = $student->personalInformation->name ?? $student->name;

        $this->seo()->setTitle('گزارش‌های ' . $this->studentName);


        $this->monthOptions = $this->buildMonthOptions();

        $this->loadSessions();

        $this->markStudentRepliesAsSeen();

    }


    protected function buildMonthOptions(): array

    {

        return [

            '01' => 'فروردین',

            '02' => 'اردیبهشت',

            '03' => 'خرداد',

            '04' => 'تیر',

            '05' => 'مرداد',

            '06' => 'شهریور',

            '07' => 'مهر',

            '08' => 'آبان',

            '09' => 'آذر',

            '10' => 'دی',

            '11' => 'بهمن',

            '12' => 'اسفند',

        ];

    }


    protected function loadSessions()

    {

        $this->sessions = AdvisingSession::where('student_id', $this->studentId)
            ->where('result_status', 'held')
            ->orderBy('activation_date', 'desc')
            ->get()
            ->map(function ($session) {

                return [

                    'id' => $session->id,

                    'label' => jdate($session->activation_date)->format('Y/m/d') . ' - ' . ($session->title ?? 'جلسه مشاوره'),

                    'activation_date' => $session->activation_date,

                ];

            })
            ->toArray();

    }


    public function toggleMonth($month)

    {

        if (in_array($month, $this->selectedMonths)) {

            $this->selectedMonths = array_values(array_diff($this->selectedMonths, [$month]));

        } else {

            $this->selectedMonths[] = $month;

        }

        $this->resetPage();

    }


    public function clearMonths()

    {

        $this->selectedMonths = [];

        $this->resetPage();

    }


    public function updatedSelectedSessionId()

    {

        // Clear month filter when session changes

        $this->selectedMonths = [];

        $this->resetPage();

        $this->loadStats();

    }


    public function updatedStatusFilter()

    {

        $this->resetPage();

    }


    protected function loadStats()

    {

        // Load stats based on current filters

        $reports = $this->getFilteredReportsQuery()->get();


        $totalReports = $reports->count();

        $approvedReports = $reports->where('status', 'approved')->count();

        $pendingReports = $reports->where('status', 'pending')->count();

        $rejectedReports = $reports->where('status', 'rejected')->count();

        $compensatoryReports = $reports->where('is_compensatory', true)->count();


        $totalParts = 0;

        $readParts = 0;

        $totalTests = 0;

        $doneTests = 0;

        $totalPhoneHours = 0;


        foreach ($reports as $report) {

            $totalPhoneHours += $report->phone_hours;

            foreach ($report->reportParts as $rp) {

                $totalParts++;

                if ($rp->is_read) $readParts++;

                $totalTests += $rp->programPart?->test_count ?? 0;

                $doneTests += $rp->tests_done;

            }

        }


        $this->stats = [

            'total_reports' => $totalReports,

            'approved_reports' => $approvedReports,

            'pending_reports' => $pendingReports,

            'rejected_reports' => $rejectedReports,

            'compensatory_reports' => $compensatoryReports,

            'total_parts' => $totalParts,

            'read_parts' => $readParts,

            'unread_parts' => $totalParts - $readParts,

            'total_tests' => $totalTests,

            'done_tests' => $doneTests,

            'undone_tests' => $totalTests - $doneTests,

            'total_phone_hours' => $totalPhoneHours,

            'read_percentage' => $totalParts > 0 ? round(($readParts / $totalParts) * 100) : 0,

            'test_percentage' => $totalTests > 0 ? round(($doneTests / $totalTests) * 100) : 0,

        ];

    }


    protected function getFilteredReportsQuery()

    {

        $query = DailyReport::with(['student.user', 'reportParts.programPart'])
            ->where('student_id', $this->studentId);


        // Filter by session

        if ($this->selectedSessionId) {

            $weeklyProgram = WeeklyProgram::where('advising_session_id', $this->selectedSessionId)->first();

            if ($weeklyProgram) {

                $query->where('weekly_program_id', $weeklyProgram->id);

            }

        }


        // Filter by months (Jalali)

        if (!empty($this->selectedMonths)) {

            $query->where(function ($q) {

                foreach ($this->selectedMonths as $month) {

                    $q->orWhereRaw("MONTH(report_date) = ?", [(int)$month]);

                }

            });


            // Actually we need to filter by Jalali month, let's do it differently

            $query = DailyReport::with(['student.user', 'reportParts.programPart'])
                ->where('student_id', $this->studentId);


            if ($this->selectedSessionId) {

                $weeklyProgram = WeeklyProgram::where('advising_session_id', $this->selectedSessionId)->first();

                if ($weeklyProgram) {

                    $query->where('weekly_program_id', $weeklyProgram->id);

                }

            }

        }


        // Filter by status

        if ($this->statusFilter !== 'all') {

            if ($this->statusFilter === 'not_sent') {

                // This will be handled differently - show days without reports

            } else {

                $query->where('status', $this->statusFilter);

            }

        }


        return $query;

    }


    protected function getReportsWithFilters()

    {

        $query = DailyReport::with(['student.user', 'reportParts.programPart'])
            ->where('student_id', $this->studentId);


        // Filter by session

        if ($this->selectedSessionId) {

            $weeklyProgram = WeeklyProgram::where('advising_session_id', $this->selectedSessionId)->first();

            if ($weeklyProgram) {

                $query->where('weekly_program_id', $weeklyProgram->id);

            } else {

                return collect([]);

            }

        }


        // Get all reports first

        $reports = $query->orderBy('report_date', 'desc')->get();


        // Filter by Jalali months

        if (!empty($this->selectedMonths)) {

            $reports = $reports->filter(function ($report) {

                $jalaliMonth = jdate($report->report_date)->format('m');

                return in_array($jalaliMonth, $this->selectedMonths);

            });

        }


        // Filter by status

        if ($this->statusFilter !== 'all' && $this->statusFilter !== 'not_sent') {

            $reports = $reports->where('status', $this->statusFilter);

        }


        return $reports;

    }


    protected function getNotSentDays()

    {

        if (!$this->selectedSessionId) {

            return collect([]);

        }


        $session = AdvisingSession::find($this->selectedSessionId);

        if (!$session) {

            return collect([]);

        }


        $weeklyProgram = WeeklyProgram::where('advising_session_id', $this->selectedSessionId)->first();

        if (!$weeklyProgram) {

            return collect([]);

        }


        // Get the 8-day period for this session

        $startDate = $session->activation_date;

        $endDate = $session->activation_date->copy()->addDays(7);


        // Don't include future dates

        $today = now()->endOfDay();

        if ($endDate->gt($today)) {

            $endDate = $today;

        }


        // Get existing report dates

        $existingReportDates = DailyReport::where('student_id', $this->studentId)
            ->where('weekly_program_id', $weeklyProgram->id)
            ->pluck('report_date')
            ->map(fn($date) => $date->format('Y-m-d'))
            ->toArray();


        // Generate missing days

        $missingDays = collect([]);

        $period = \Carbon\CarbonPeriod::create($startDate, '1 day', $endDate);


        $dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];


        foreach ($period as $day) {

            $dayString = $day->format('Y-m-d');

            if (!in_array($dayString, $existingReportDates)) {

                $jalaliDate = jdate($day);

                $jalaliMonth = $jalaliDate->format('m');


                // Filter by selected months if any

                if (!empty($this->selectedMonths) && !in_array($jalaliMonth, $this->selectedMonths)) {

                    continue;

                }


                $missingDays->push([

                    'date' => $day,

                    'jalali_date' => $jalaliDate->format('Y/m/d'),

                    'day_name' => $dayNames[$day->dayOfWeek == 0 ? 6 : $day->dayOfWeek - 1] ?? '-',

                    'status' => 'not_sent',

                ]);

            }

        }


        return $missingDays->sortByDesc('date');

    }


    public function changeStatus($reportId, $value)

    {

        $validator = Validator::make(['status' => $value, 'id' => $reportId], [

            'id' => 'required|exists:daily_reports,id',

            'status' => 'required|in:pending,approved,rejected'

        ], [

            '*.required' => 'فیلد الزامی است.',

            'status.in' => 'وضعیت نامعتبر است.',

            'id.exists' => 'گزارش یافت نشد.'

        ]);


        $validator->validate();


        DailyReport::where('id', $reportId)->update(['status' => $value]);

        $this->resetValidation();

        $this->dispatch('success', 'وضعیت با موفقیت تغییر کرد.');

        $this->loadStats();

    }


    public function openCommentModal(int $reportId)

    {

        $report = DailyReport::with('student.user')
            ->where('id', $reportId)
            ->firstOrFail();


        $this->commentReportId = $reportId;

        $this->advisorCommentInput = $report->advisor_comment ?? '';

        $this->advisorCommentReadonly = !empty($report->advisor_comment);

        $this->commentStudentName = $report->student->user->name ?? '';

        $this->commentStudentReply = $report->student_reply;

        $this->commentModalOpen = true;

    }


    public function closeCommentModal()

    {

        $this->commentModalOpen = false;

        $this->advisorCommentInput = '';

        $this->commentReportId = null;

        $this->advisorCommentReadonly = false;

        $this->commentStudentReply = null;

        $this->commentStudentName = '';

        $this->resetErrorBag('advisorCommentInput');

    }


    public function saveAdvisorComment()

    {

        if (!$this->commentReportId) return;


        $report = DailyReport::where('id', $this->commentReportId)->firstOrFail();


        if (!empty($report->advisor_comment)) {

            $this->dispatch('warning', 'برای این گزارش قبلاً نظری ثبت شده است.');

            $this->closeCommentModal();

            return;

        }


        $validated = $this->validate([

            'advisorCommentInput' => 'required|string|max:1000',

        ], [

            'advisorCommentInput.required' => 'متن نظر را وارد کنید.',

            'advisorCommentInput.max' => 'طول نظر نمی‌تواند بیشتر از ۱۰۰۰ کاراکتر باشد.',

        ]);


        $report->update([

            'advisor_comment' => $validated['advisorCommentInput'],

            'advisor_commented_at' => now(),

        ]);


        $this->dispatch('success', 'نظر شما ثبت شد.');

        $this->closeCommentModal();

    }


    public function openDetailModal(int $reportId)

    {

        $report = DailyReport::with([

            'student.user',

            'weeklyProgram',

            'reportParts.programPart.ccSubject',

            'reportParts.programPart.ccTopic',

        ])->where('id', $reportId)->firstOrFail();


        $this->selectedReportId = $reportId;


        $dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];

        $this->selectedReportData = [

            'student_name' => $report->student->user->name ?? 'نامشخص',

            'report_date' => jdate($report->report_date)->format('Y/m/d'),

            'day_name' => $dayNames[$report->day_of_week] ?? '-',

            'phone_hours' => $report->phone_hours,

            'description' => $report->description,

            'rating' => $report->rating,

            'rating_label' => DailyReport::RATINGS[$report->rating] ?? 'نامشخص',

            'is_compensatory' => $report->is_compensatory,

            'status' => $report->status,

        ];


        $dayOfWeek = $report->day_of_week;

        $programParts = $report->weeklyProgram
            ->parts()
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('part_order')
            ->get();


        $reportPartsMap = $report->reportParts->keyBy('program_part_id');


        $this->reportPartsDetails = [];

        $totalTests = 0;

        $doneTests = 0;

        $totalParts = 0;

        $readParts = 0;


        foreach ($programParts as $programPart) {

            $reportPart = $reportPartsMap->get($programPart->id);

            $isRead = $reportPart?->is_read ?? false;

            $testsDone = $reportPart?->tests_done ?? 0;

            $testCount = $programPart->test_count ?? 0;


            $totalParts++;

            if ($isRead) $readParts++;

            $totalTests += $testCount;

            $doneTests += $testsDone;


            $this->reportPartsDetails[] = [

                'id' => $programPart->id,

                'lesson_name' => $programPart->lesson_name,

                'subject_name' => $programPart->ccSubject->name ?? null,

                'topic_name' => $programPart->ccTopic->name ?? null,

                'duration_minutes' => $programPart->duration_minutes,

                'is_read' => $isRead,

                'tests_done' => $testsDone,

                'test_count' => $testCount,

                'is_compensatory' => $reportPart?->is_compensatory ?? false,

            ];

        }


        $this->selectedReportData['total_parts'] = $totalParts;

        $this->selectedReportData['read_parts'] = $readParts;

        $this->selectedReportData['unread_parts'] = $totalParts - $readParts;

        $this->selectedReportData['total_tests'] = $totalTests;

        $this->selectedReportData['done_tests'] = $doneTests;

        $this->selectedReportData['undone_tests'] = $totalTests - $doneTests;


        $this->detailModalOpen = true;

    }


    public function closeDetailModal()

    {

        $this->detailModalOpen = false;

        $this->selectedReportId = null;

        $this->reportPartsDetails = [];

        $this->selectedReportData = [];

    }


    public function openExportModal()

    {

        $this->exportStartDate = '';

        $this->exportEndDate = '';

        $this->exportModalOpen = true;

    }


    public function closeExportModal()

    {

        $this->exportModalOpen = false;

        $this->exportStartDate = '';

        $this->exportEndDate = '';

    }


    public function exportExcel()

    {

        $this->validate([

            'exportStartDate' => 'required|string',

            'exportEndDate' => 'required|string',

        ], [

            'exportStartDate.required' => 'تاریخ شروع را وارد کنید.',

            'exportEndDate.required' => 'تاریخ پایان را وارد کنید.',

        ]);


        // Convert Jalali dates to Gregorian

        try {

            $startParts = explode('/', $this->exportStartDate);

            $endParts = explode('/', $this->exportEndDate);


            $startDate = Jalalian::fromFormat('Y/m/d', $this->exportStartDate)->toCarbon()->startOfDay();

            $endDate = Jalalian::fromFormat('Y/m/d', $this->exportEndDate)->toCarbon()->endOfDay();

        } catch (\Exception $e) {

            $this->dispatch('warning', 'فرمت تاریخ صحیح نیست. مثال: 1404/09/10');

            return;

        }


        if ($startDate->gt($endDate)) {

            $this->dispatch('warning', 'تاریخ شروع نباید بعد از تاریخ پایان باشد.');

            return;

        }


        $fileName = 'daily_reports_' . str_replace(' ', '_', $this->studentName) . '_' . now()->format('Ymd_His') . '.xlsx';


        $this->closeExportModal();


        return Excel::download(

            new DailyReportExport($this->studentId, null, $startDate, $endDate),

            $fileName

        );

    }


    protected function markStudentRepliesAsSeen(): void

    {

        DailyReport::query()
            ->where('student_id', $this->studentId)
            ->whereNotNull('student_reply')
            ->whereNull('student_replied_at')
            ->update(['student_replied_at' => now()]);

    }


    public function getStatusColor($status): string

    {

        return match ($status) {

            'pending' => 'primary',

            'approved' => 'success',

            'rejected' => 'danger',

            'not_sent' => 'warning',

            default => 'secondary',

        };

    }


    public function render()

    {

        $reports = collect([]);

        $notSentDays = collect([]);


        // Get filtered reports

        $filteredReports = $this->getReportsWithFilters();


        // Get not sent days if needed

        if ($this->statusFilter === 'all' || $this->statusFilter === 'not_sent') {

            $notSentDays = $this->getNotSentDays();

        }


        // Combine based on filter

        if ($this->statusFilter === 'not_sent') {

            // Only show not sent days

            $allItems = $notSentDays;

        } elseif ($this->statusFilter === 'all') {

            // Combine reports with not sent days

            $reportItems = $filteredReports->map(function ($report) {

                return [

                    'type' => 'report',

                    'data' => $report,

                    'date' => $report->report_date,

                ];

            });


            $notSentItems = $notSentDays->map(function ($day) {

                return [

                    'type' => 'not_sent',

                    'data' => $day,

                    'date' => $day['date'],

                ];

            });


            $allItems = $reportItems->concat($notSentItems)->sortByDesc('date');

        } else {

            // Only show filtered reports

            $allItems = $filteredReports->map(function ($report) {

                return [

                    'type' => 'report',

                    'data' => $report,

                    'date' => $report->report_date,

                ];

            })->sortByDesc('date');

        }


        // Manual pagination

        $page = request()->get('page', 1);

        $perPage = 10;

        $total = $allItems->count();

        $items = $allItems->forPage($page, $perPage)->values();


        $paginatedReports = new \Illuminate\Pagination\LengthAwarePaginator(

            $items,

            $total,

            $perPage,

            $page,

            ['path' => request()->url(), 'pageName' => 'page']

        );


        $this->loadStats();


        return view('livewire.admin.student.report-daily-activities.detail', [

            'reports' => $paginatedReports,

            'notSentDays' => $notSentDays,

        ])->layout('layouts.admin.app');

    }

}
