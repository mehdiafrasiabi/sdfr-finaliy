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
use Carbon\Carbon;

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
        // Priority: user_profiles.full_name > personal_information.name > user.name
        $this->studentName = $student->profile?->full_name
            ?? $student->personalInformation?->name
            ?? $student->name;
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
            $totalPhoneHours += $report->detail->phone_hours ?? 0;
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
        $query = DailyReport::with(['student.user', 'reportParts.programPart', 'detail', 'feedback'])
            ->where('student_id', $this->studentId);
        // Filter by session
        if ($this->selectedSessionId) {
            $weeklyProgram = WeeklyProgram::where('advising_session_id', $this->selectedSessionId)->first();
            if ($weeklyProgram) {
                $query->where('weekly_program_id', $weeklyProgram->id);
            }
        }
        if ($this->statusFilter !== 'all' && $this->statusFilter !== 'not_sent') {
            $query->whereHas('detail', fn($q) => $q->where('status', $this->statusFilter));
        }
        return $query;
    }

    protected function getReportsWithFilters()
    {
        $query = DailyReport::with(['student.user', 'reportParts.programPart', 'detail', 'feedback'])
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
            $reports = $reports->filter(function ($report) {
                return ($report->detail->status ?? 'pending') === $this->statusFilter;
            });
        }
        return $reports;
    }

    /**
     * Get not-sent days for the current session filter.
     * Includes future days with 'future' status.
     * When no session selected but months are active, gathers from all sessions.
     */
    protected function getNotSentDays()
    {
        $dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
        if (!$this->selectedSessionId) {
            // Only gather missing days from all sessions when months are selected
            if (empty($this->selectedMonths)) {
                return collect([]);
            }
            return $this->getAllNotSentDaysForMonths($dayNames);
        }
        $session = AdvisingSession::find($this->selectedSessionId);
        if (!$session) return collect([]);
        $weeklyProgram = WeeklyProgram::where('advising_session_id', $this->selectedSessionId)->first();
        if (!$weeklyProgram) return collect([]);
        $startDate = Carbon::parse($session->activation_date);
        $endDate = $startDate->copy()->addDays(7);
        $today = Carbon::today();
        // Get existing report dates for this program
        $existingReportDates = DailyReport::where('student_id', $this->studentId)
            ->where('weekly_program_id', $weeklyProgram->id)
            ->pluck('report_date')
            ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))
            ->toArray();
        // Generate missing days
        $missingDays = collect([]);
        $period = \Carbon\CarbonPeriod::create($startDate, '1 day', $endDate);
        foreach ($period as $day) {
            $dayString = $day->format('Y-m-d');
            if (!in_array($dayString, $existingReportDates)) {
                $jalaliDate = jdate($day);
                $jalaliMonth = $jalaliDate->format('m');
                // Filter by selected months if any
                if (!empty($this->selectedMonths) && !in_array($jalaliMonth, $this->selectedMonths)) {
                    continue;
                }
                $isFuture = $day->gt($today);
                $missingDays->push([
                    'date' => $day,
                    'jalali_date' => $jalaliDate->format('Y/m/d'),
                    'day_name' => $dayNames[jdate($day)->getDayOfWeek()] ?? '-',
                    'status' => $isFuture ? 'future' : 'not_sent',
                    'is_future' => $isFuture,
                    'future_note' => $isFuture ? 'هنوز به این تاریخ نرسیده‌اید' : null,
                ]);
            }
        }
        return $missingDays->sortByDesc('date');
    }

    /**
     * Collect missing days across all sessions, filtered by selected months.
     */
    protected function getAllNotSentDaysForMonths(array $dayNames): \Illuminate\Support\Collection
    {
        $sessions = AdvisingSession::where('student_id', $this->studentId)
            ->where('result_status', 'held')
            ->get();
        $missingDays = collect([]);
        $today = Carbon::today();
        $seen = []; // avoid duplicate dates
        foreach ($sessions as $session) {
            $weeklyProgram = WeeklyProgram::where('advising_session_id', $session->id)->first();
            if (!$weeklyProgram) continue;
            $startDate = Carbon::parse($session->activation_date);
            $endDate = $startDate->copy()->addDays(7);
            $existingReportDates = DailyReport::where('student_id', $this->studentId)
                ->where('weekly_program_id', $weeklyProgram->id)
                ->pluck('report_date')
                ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))
                ->toArray();
            $period = \Carbon\CarbonPeriod::create($startDate, '1 day', $endDate);
            foreach ($period as $day) {
                $dayString = $day->format('Y-m-d');
                if (isset($seen[$dayString])) continue;
                if (!in_array($dayString, $existingReportDates)) {
                    $jalaliDate = jdate($day);
                    $jalaliMonth = $jalaliDate->format('m');
                    if (!in_array($jalaliMonth, $this->selectedMonths)) {
                        continue;
                    }
                    $seen[$dayString] = true;
                    $isFuture = $day->gt($today);
                    $missingDays->push([
                        'date' => $day,
                        'jalali_date' => $jalaliDate->format('Y/m/d'),
                        'day_name' => $dayNames[jdate($day)->getDayOfWeek()] ?? '-',
                        'status' => $isFuture ? 'future' : 'not_sent',
                        'is_future' => $isFuture,
                        'future_note' => $isFuture ? 'هنوز به این تاریخ نرسیده‌اید' : null,
                    ]);
                }
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
        $report = DailyReport::with('detail')->where('id', $reportId)->firstOrFail();
        if (!$report->detail) {
            $report->detail()->create(['status' => $value]);
        } else {
            $report->detail->update(['status' => $value]);
        }
        $this->resetValidation();
        $this->dispatch('success', 'وضعیت با موفقیت تغییر کرد.');
        $this->loadStats();
    }

    public function openCommentModal(int $reportId)
    {
        $report = DailyReport::with(['student.user.personalInformation', 'student.user.profile', 'detail', 'feedback'])
            ->where('id', $reportId)
            ->firstOrFail();
        $this->commentReportId = $reportId;
        $this->advisorCommentInput = $report->feedback->advisor_comment ?? '';
        $this->advisorCommentReadonly = !empty($report->feedback->advisor_comment);
        $this->commentStudentName = $report->student->user->profile?->full_name
            ?? $report->student->user->personalInformation?->name
            ?? $report->student->user->name
            ?? '';
        $this->commentStudentReply = $report->feedback->student_reply;
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
        $report = DailyReport::with(['feedback'])->where('id', $this->commentReportId)->firstOrFail();
        if (!empty($report->feedback->advisor_comment)) {
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
        if (!$report->feedback) {
            $report->feedback()->create([
                'advisor_comment' => $validated['advisorCommentInput'],
                'advisor_commented_at' => now(),
            ]);
        } else {
            $report->feedback->update([
                'advisor_comment' => $validated['advisorCommentInput'],
                'advisor_commented_at' => now(),
            ]);
        }
        $this->dispatch('success', 'نظر شما ثبت شد.');
        $this->closeCommentModal();
    }

    public function openDetailModal(int $reportId)
    {
        $report = DailyReport::with([
            'student.user.personalInformation',
            'student.user.profile',
            'weeklyProgram',
            'reportParts.programPart.ccSubject',
            'reportParts.programPart.ccTopic',
            'reportParts.programPart.ccChapter',
            'detail',
            'feedback',
        ])->where('id', $reportId)->firstOrFail();
        $this->selectedReportId = $reportId;
        $dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
        // Check if submitted within allowed time window (00:00 of report_date to 06:00 of next day)
        $reportDateStart = Carbon::parse($report->report_date)->startOfDay();
        $reportDateEnd = Carbon::parse($report->report_date)->addDay()->setHour(6)->setMinute(0)->setSecond(0);
        $submittedInTime = $report->created_at
            && $report->created_at->between($reportDateStart, $reportDateEnd);
        $ratingVal = (float)($report->detail->rating ?? 0);
        $this->selectedReportData = [
            'student_name' => $report->student->user->profile?->full_name
                ?? $report->student->user->personalInformation?->name
                    ?? $report->student->user->name
                    ?? 'نامشخص',
            'report_date' => jdate($report->report_date)->format('Y/m/d'),
            'day_name' => $dayNames[jdate($report->report_date)->getDayOfWeek()] ?? '-',
            'phone_hours' => $report->detail->phone_hours ?? 0,
            'description' => $report->detail->description ?? '',
            'missed_parts_reason' => $report->detail->missed_parts_reason ?? '',
            'rating' => $ratingVal,
            'rating_label' => $this->getRatingLabel($ratingVal),
            'is_compensatory' => $report->is_compensatory,
            'status' => $report->detail->status ?? 'pending',
            'advisor_comment' => $report->feedback->advisor_comment ?? '',
            'student_reply' => $report->feedback->student_reply ?? '',
            'created_at' => $report->created_at ? jdate($report->created_at)->format('Y/m/d H:i') : '-',
            'submitted_in_time' => $submittedInTime,
            'submit_window_start' => jdate($reportDateStart)->format('Y/m/d') . ' ۰۰:۰۰',
            'submit_window_end' => jdate($reportDateEnd)->format('Y/m/d') . ' ۰۶:۰۰',
        ];
        // ✅ پارت‌ها همیشه از daily_report_parts بارگذاری می‌شوند (هم عادی هم جبرانی)
        // چون day_of_week در daily_reports روز هفته شمسی است (۰-۶) ولی در program_parts شاخص روز برنامه (۰-۷)
        $reportPartsMap = $report->reportParts->keyBy('program_part_id');
        $reportPartProgramIds = $report->reportParts->pluck('program_part_id')->filter()->toArray();

        $programParts = collect();
        if (!empty($reportPartProgramIds) && $report->weeklyProgram) {
            $programParts = $report->weeklyProgram
                ->parts()
                ->whereIn('id', $reportPartProgramIds)
                ->orderBy('day_of_week')
                ->orderBy('part_order')
                ->with(['ccSubject', 'ccTopic', 'ccChapter'])
                ->get();
        }

        // Fallback: اگر از weekly program پیدا نشد، مستقیم از reportParts بگیر
        if ($programParts->isEmpty()) {
            $programParts = $report->reportParts
                ->map(fn($rp) => $rp->programPart)
                ->filter()
                ->values();
        }

        $this->reportPartsDetails = [];
        $totalTests = 0;
        $doneTests = 0;
        $totalParts = 0;
        $readParts = 0;
        foreach ($programParts as $programPart) {
            if (!$programPart) continue;
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
                'chapter_name' => $programPart->ccChapter->name ?? null,
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
            ->whereNull('student_replied_at');
    }

    public function getStatusColor($status): string
    {
        return match ($status) {
            'pending' => 'primary',
            'approved' => 'success',
            'rejected' => 'danger',
            'not_sent' => 'warning',
            'future' => 'secondary',
            default => 'secondary',
        };
    }
    public function getRatingLabel($rating): string
    {
        $rating = (float) $rating;
        return match (true) {
            $rating >= 9 => 'عالی',
            $rating >= 7 => 'خوب',
            $rating >= 5 => 'متوسط',
            $rating >= 3 => 'ضعیف',
            $rating > 0  => 'خیلی ضعیف',
            default      => 'ثبت نشده',
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
