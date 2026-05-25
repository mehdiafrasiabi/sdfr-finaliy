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
    // Jalali date range filter
    public string $filterStartDate = '';
    public string $filterEndDate = '';
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

    public function updatedFilterStartDate()
    {
        $this->resetPage();
        $this->loadStats();
    }

    public function updatedFilterEndDate()
    {
        $this->resetPage();
        $this->loadStats();
    }

    public function clearDateFilter()
    {
        $this->filterStartDate = '';
        $this->filterEndDate = '';
        $this->resetPage();
        $this->loadStats();
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
        // --- Report-level stats (respect the current session/status filter) ---

        $reports = $this->getFilteredReportsQuery()->get();
        $totalReports = $reports->count();
        $approvedReports  = $reports->where('status', 'approved')->count();
        $pendingReports   = $reports->where('status', 'pending')->count();
        $rejectedReports  = $reports->where('status', 'rejected')->count();
        $compensatoryReports = $reports->where('is_compensatory', true)->count();
        $totalPhoneHours = $reports->sum(fn($r) => $r->detail->phone_hours ?? 0);

        // Rating: average over filtered reports
        $ratingSum = 0;
        $ratingDayCount = 0;
        foreach ($reports as $report) {
            $allDayParts = $report->getProgramPartsForDay();

            if ($allDayParts->isNotEmpty()) {
                // Rating: average of session feedback ratings for all parts of this day
                $partIds = $allDayParts->pluck('id')->filter()->toArray();
                $studySessions = \App\Models\StudyPartSession::where('student_id', $report->student_id)
                    ->whereIn('program_part_id', $partIds)
                    ->where('is_completed', true)
                    ->with('feedback')
                    ->get()
                    ->groupBy('program_part_id')
                    ->map(fn($sessions) => $sessions->sortByDesc('started_at')->first());

                $dayRatingSum = 0;
                foreach ($allDayParts as $part) {
                    $session = $studySessions->get($part->id);
                    $dayRatingSum += $session?->feedback?->rating ?? 0;
                }
                $ratingSum += $allDayParts->count() > 0 ? $dayRatingSum / $allDayParts->count() : 0;
                $ratingDayCount++;

            }
        }
        $avgRating = $ratingDayCount > 0 ? round($ratingSum / $ratingDayCount, 1) : 0;
        // --- Cumulative parts & tests across ALL programs of this student ---
        $totalParts = \App\Models\ProgramPart::whereHas(
            'weeklyProgram', fn($q) => $q->where('student_id', $this->studentId)
        )->count();

        $totalTests = (int) \App\Models\ProgramPart::whereHas(
            'weeklyProgram', fn($q) => $q->where('student_id', $this->studentId)
        )->sum('test_count');

        $readParts = \App\Models\DailyReportPart::whereHas(
            'dailyReport', fn($q) => $q->where('student_id', $this->studentId)
        )->where('is_read', true)->count();

        $doneTests = (int) \App\Models\DailyReportPart::whereHas(
            'dailyReport', fn($q) => $q->where('student_id', $this->studentId)
        )->sum('tests_done');

        // Cap to avoid negative values when compensatory reports push counts past totals
        $unreadParts  = max(0, $totalParts - $readParts);
        $undoneTests  = max(0, $totalTests - $doneTests);

        $this->stats = [
            'total_reports'       => $totalReports,
            'approved_reports'    => $approvedReports,
            'pending_reports'     => $pendingReports,
            'rejected_reports'    => $rejectedReports,
            'compensatory_reports' => $compensatoryReports,
            'total_parts'         => $totalParts,
            'read_parts'          => $readParts,
            'unread_parts'        => $unreadParts,
            'total_tests'         => $totalTests,
            'done_tests'          => $doneTests,
            'undone_tests'        => $undoneTests,
            'total_phone_hours'   => $totalPhoneHours,
            'read_percentage'     => $totalParts > 0 ? min(100, round(($readParts / $totalParts) * 100)) : 0,
            'test_percentage'     => $totalTests > 0 ? min(100, round(($doneTests / $totalTests) * 100)) : 0,
            'avg_rating'          => $avgRating,

        ];

    }


    protected function parseDateFilter(string $jalali): ?Carbon
    {
        if (empty(trim($jalali))) return null;
        try {
            return \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', trim($jalali))->toCarbon();
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function getFilteredReportsQuery()
    {
        $query = DailyReport::with(['student.user', 'reportParts.programPart', 'detail', 'feedback', 'weeklyProgram.parts'])
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
        // Jalali date range filter
        $start = $this->parseDateFilter($this->filterStartDate);
        $end   = $this->parseDateFilter($this->filterEndDate);
        if ($start) $query->whereDate('report_date', '>=', $start);
        if ($end)   $query->whereDate('report_date', '<=', $end);
        return $query;
    }

    protected function getReportsWithFilters()
    {
        $query = DailyReport::with(['student.user', 'reportParts.programPart', 'detail', 'feedback', 'weeklyProgram.parts'])
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
        // Jalali date range filter
        $start = $this->parseDateFilter($this->filterStartDate);
        $end   = $this->parseDateFilter($this->filterEndDate);
        if ($start) $query->whereDate('report_date', '>=', $start);
        if ($end)   $query->whereDate('report_date', '<=', $end);
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
     * Get non-report days for the current session filter.
     * * Returns rest days ('rest_day'), future days ('future'), and not-sent past days ('not_sent').
     * * When no session is selected but months are active, gathers from all sessions.
     **/
    protected function getNotSentDays()
    {
        $dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
        if (!$this->selectedSessionId) {
            if (empty($this->selectedMonths)) {
                return collect([]);
            }
            return $this->getAllNotSentDaysForMonths($dayNames);
        }
        $session = AdvisingSession::find($this->selectedSessionId);
        if (!$session) return collect([]);
        $weeklyProgram = WeeklyProgram::where('advising_session_id', $this->selectedSessionId)
            ->with('restDays')
            ->first();
        if (!$weeklyProgram) return collect([]);
        $restDayIndices = $weeklyProgram->restDays->pluck('day_index')->toArray();
        $startDate = Carbon::parse($session->activation_date);
        $today = Carbon::today();
        $existingReportDates = DailyReport::where('student_id', $this->studentId)
            ->where('weekly_program_id', $weeklyProgram->id)
            ->pluck('report_date')
            ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))
            ->toArray();
        $missingDays = collect([]);
        for ($dayIndex = 0; $dayIndex <= 7; $dayIndex++) {
            $day = $startDate->copy()->addDays($dayIndex);
            $dayString = $day->format('Y-m-d');
            $jalaliDate = jdate($day);
            $jalaliMonth = $jalaliDate->format('m');

            if (!empty($this->selectedMonths) && !in_array($jalaliMonth, $this->selectedMonths)) {
                continue;
            }

            if (in_array($dayIndex, $restDayIndices)) {
                // This is a rest day - show it explicitly
                $missingDays->push([
                    'date' => $day,
                    'jalali_date' => $jalaliDate->format('Y/m/d'),
                    'day_name' => $dayNames[jdate($day)->getDayOfWeek()] ?? '-',
                    'status' => 'rest_day',
                    'is_future' => $day->gt($today),
                    'future_note' => null,
                ]);
            } elseif (!in_array($dayString, $existingReportDates)) {
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
            ->with(['weeklyProgram.restDays'])
            ->get();
        $missingDays = collect([]);
        $today = Carbon::today();
        $seen = [];
        foreach ($sessions as $session) {
            $weeklyProgram = $session->weeklyProgram;
            if (!$weeklyProgram) continue;
            $restDayIndices = $weeklyProgram->restDays->pluck('day_index')->toArray();
            $startDate = Carbon::parse($session->activation_date);
            $existingReportDates = DailyReport::where('student_id', $this->studentId)
                ->where('weekly_program_id', $weeklyProgram->id)
                ->pluck('report_date')
                ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))
                ->toArray();
            for ($dayIndex = 0; $dayIndex <= 7; $dayIndex++) {
                $day = $startDate->copy()->addDays($dayIndex);
                $dayString = $day->format('Y-m-d');
                if (isset($seen[$dayString])) continue;

                $jalaliDate = jdate($day);
                $jalaliMonth = $jalaliDate->format('m');
                if (!in_array($jalaliMonth, $this->selectedMonths)) continue;

                $seen[$dayString] = true;

                if (in_array($dayIndex, $restDayIndices)) {
                    $missingDays->push([
                        'date' => $day,
                        'jalali_date' => $jalaliDate->format('Y/m/d'),
                        'day_name' => $dayNames[jdate($day)->getDayOfWeek()] ?? '-',
                        'status' => 'rest_day',
                        'is_future' => $day->gt($today),
                        'future_note' => null,
                    ]);
                } elseif (!in_array($dayString, $existingReportDates)) {
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
            'rating' => 0,
            'rating_label' => 'ثبت نشده',
            'is_compensatory' => $report->is_compensatory,
            'status' => $report->detail->status ?? 'pending',
            'advisor_comment' => $report->feedback->advisor_comment ?? '',
            'student_reply' => $report->feedback->student_reply ?? '',
            'created_at' => $report->created_at ? jdate($report->created_at)->format('Y/m/d H:i') : '-',
            'submitted_in_time' => $submittedInTime,
            'submit_window_start' => jdate($reportDateStart)->format('Y/m/d') . ' ۰۰:۰۰',
            'submit_window_end' => jdate($reportDateEnd)->format('Y/m/d') . ' ۰۶:۰۰',
        ];
        $reportPartsMap = $report->reportParts->keyBy('program_part_id');
        $programParts = collect();

        if ($report->is_compensatory) {
            // Compensatory reports: use the reported parts directly (they span multiple days)
            $programParts = $report->reportParts
                ->map(fn($rp) => $rp->programPart)
                ->filter()
                ->values();
        } else {
            // Normal reports: use the program parts for this specific day
            if ($report->weeklyProgram) {
                $startDate = Carbon::parse($report->weeklyProgram->start_date);
                $dayIndex = $startDate->diffInDays(Carbon::parse($report->report_date));
                $programParts = $report->weeklyProgram
                    ->parts()
                    ->where('day_of_week', $dayIndex)
                    ->orderBy('part_order')
                    ->with(['ccSubject', 'ccTopic', 'ccChapter'])
                    ->get();
            }

            // Fallback: if no parts found from weekly program, use reportParts
            if ($programParts->isEmpty()) {
                $reportPartProgramIds = $report->reportParts->pluck('program_part_id')->filter()->toArray();
                if (!empty($reportPartProgramIds) && $report->weeklyProgram) {
                    $programParts = $report->weeklyProgram
                        ->parts()
                        ->whereIn('id', $reportPartProgramIds)
                        ->orderBy('day_of_week')
                        ->orderBy('part_order')
                        ->with(['ccSubject', 'ccTopic', 'ccChapter'])
                        ->get();
                }
                if ($programParts->isEmpty()) {
                    $programParts = $report->reportParts
                        ->map(fn($rp) => $rp->programPart)
                        ->filter()
                        ->values();
                }
            }
        }

        // Get study session feedback ratings for rating calculation
        $partIds = $programParts->pluck('id')->filter()->toArray();
        $studySessionsMap = \App\Models\StudyPartSession::where('student_id', $report->student_id)
            ->whereIn('program_part_id', $partIds)
            ->where('is_completed', true)
            ->with('feedback')
            ->get()
            ->groupBy('program_part_id')
            ->map(fn($sessions) => $sessions->sortByDesc('started_at')->first());

        $this->reportPartsDetails = [];
        $totalTests = 0;
        $doneTests = 0;
        $totalParts = 0;
        $readParts = 0;
        $ratingSum = 0;
        foreach ($programParts as $programPart) {
            if (!$programPart) continue;
            $reportPart = $reportPartsMap->get($programPart->id);
            $isRead = $reportPart?->is_read ?? false;
            $testsDone = $reportPart?->tests_done ?? 0;
            $testCount = $programPart->test_count ?? 0;
            $studySession = $studySessionsMap->get($programPart->id);
            $sessionRating = $studySession?->feedback?->rating ?? 0;
            $totalParts++;
            if ($isRead) $readParts++;
            $totalTests += $testCount;
            $doneTests += $testsDone;
            $ratingSum += $sessionRating;
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
                'session_rating' => $sessionRating,
                'is_compensatory' => $reportPart?->is_compensatory ?? false,
                'has_report' => $reportPart !== null,
                'is_early_finish' => (bool) ($studySession?->is_early_finish ?? false),
                'extra_seconds' => (int) ($studySession?->extra_seconds ?? 0),
                'extra_target_seconds' => (int) ($studySession?->extra_target_seconds ?? 0),
                'extra_started_at' => $studySession?->extra_started_at?->format('H:i'),
                'extra_ended_at' => $studySession?->extra_ended_at?->format('H:i'),
                'is_cheating' => (bool) ($studySession?->is_cheating ?? false),
                'cheat_minutes' => (int) ($studySession?->cheat_minutes ?? 0),
                'cheat_status' => $studySession?->cheat_status,
                'cheat_reason' => $studySession?->cheat_reason,
            ];
        }
        $avgRating = $totalParts > 0 ? round($ratingSum / $totalParts, 1) : 0;

        $this->selectedReportData['total_parts'] = $totalParts;
        $this->selectedReportData['read_parts'] = $readParts;
        $this->selectedReportData['unread_parts'] = $totalParts - $readParts;
        $this->selectedReportData['total_tests'] = $totalTests;
        $this->selectedReportData['done_tests'] = $doneTests;
        $this->selectedReportData['undone_tests'] = $totalTests - $doneTests;
        $this->selectedReportData['rating'] = $avgRating;
        $this->selectedReportData['rating_label'] = $this->getRatingLabel($avgRating);
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
        $notSentDays = collect([]);
        // Get filtered reports
        $filteredReports = $this->getReportsWithFilters();
        // Get non-report days (not_sent / rest_day / future) when needed
        if ($this->statusFilter === 'all' || $this->statusFilter === 'not_sent') {
            $notSentDays = $this->getNotSentDays();
        }
        // Helper: normalise any date value to a comparable string
        $dateKey = function ($item) {
            $d = $item['date'];
            return $d instanceof Carbon ? $d->format('Y-m-d') : (string) $d;
        };

        if ($this->statusFilter === 'not_sent') {
            // Show only non-report days (includes rest_day, not_sent, future)
            $allItems = $notSentDays->map(fn($day) => [
                'type' => 'not_sent',
                'data' => $day,
                'date' => $day['date'],
            ])->sortByDesc($dateKey)->values();
        } elseif ($this->statusFilter === 'all') {
            $reportItems = $filteredReports->map(fn($report) => [
                'type' => 'report',
                'data' => $report,
                'date' => $report->report_date,
            ]);
            $notSentItems = $notSentDays->map(fn($day) => [
                'type' => 'not_sent',
                'data' => $day,
                'date' => $day['date'],
            ]);
            $allItems = $reportItems->concat($notSentItems)
                ->sortByDesc($dateKey)
                ->values();
        } else {
            // Only show filtered reports (approved / rejected / pending)
            $allItems = $filteredReports->map(fn($report) => [
                'type' => 'report',
                'data' => $report,
                'date' => $report->report_date,
            ])->sortByDesc($dateKey)->values();
        }
        // Manual pagination
        $page = $this->getPage();
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
