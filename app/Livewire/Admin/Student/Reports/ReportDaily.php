<?php

namespace App\Livewire\Admin\Student\Reports;

use App\Models\DailyReport;
use App\Models\DailyReportDetail;
use App\Models\DailyReportFeedback;
use App\Models\Student;
use App\Models\WeeklyProgram;
use App\Models\WeeklyProgramRestDay;
use App\Models\AdvisingSession;
use App\Models\StudyPartSession;
use App\Models\PersonalInformation;
use App\Services\NotificationService;
use App\Models\SessionFeedback;
use Carbon\Carbon;
use App\Models\MakeupSession;

use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class ReportDaily extends Component
{
    use WithPagination;

    const REPORT_CUTOFF_HOUR = 6;

    public $selectedReports = [];
    public $selectAll = false;
    public $studentsWithoutReports = [];
    public $studentsOnRestDay = [];
    // Date navigation
    public ?string $viewDate = null; // null = auto-detect
    public array $notificationSentStudents = []; // track sent notifications per session


    // Comment Modal

    public bool $commentModalOpen = false;
    public ?int $commentReportId = null;
    public string $advisorCommentInput = '';
    public string $commentStatusInput = 'pending';
    public bool $advisorCommentReadonly = false;
    public string $commentStudentName = '';
    public ?string $commentStudentReply = null;

    // Detail Modal
    public bool $detailModalOpen = false;
    public ?int $selectedReportId = null;
    public array $reportPartsDetails = [];
    public array $selectedReportData = [];

    // Student Info Modal
    public bool $studentInfoModalOpen = false;
    public string $studentInfoModalTitle = '';
    public array $studentInfoList = [];

    // All unconfirmed reports
    public bool $allReportsModalOpen = false;
    public bool $allReportsConfirmed = false;
    public array $allUnconfirmedReports = [];

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->loadStudentsWithoutReports();
    }


    protected function getReportDate(): Carbon
    {
        if ($this->viewDate) {
            return Carbon::parse($this->viewDate);
        }

        $now = Carbon::now();
        if ($now->hour < self::REPORT_CUTOFF_HOUR) {
            return Carbon::yesterday();
        }
        return Carbon::today();
    }

    protected function getEffectiveToday(): Carbon
    {
        $now = Carbon::now();
        if ($now->hour < self::REPORT_CUTOFF_HOUR) {
            return Carbon::yesterday();
        }
        return Carbon::today();
    }

    protected function getReportTimeRange(): array
    {
        $reportDate = $this->getReportDate();

        return [
            'start' => $reportDate->copy()->startOfDay(),
            'end' => $reportDate->copy()->addDay()->setHour(self::REPORT_CUTOFF_HOUR)->setMinute(0)->setSecond(0),
        ];
    }


    protected function getReportDateJalali(): string
    {
        return jdate($this->getReportDate())->format('Y/m/d');
    }

    public function goToPrevDay(): void
    {
        $effectiveToday = $this->getEffectiveToday();
        $current = $this->getReportDate();
        $minDate = $effectiveToday->copy()->subDays(2);

        $prevDay = $current->copy()->subDay();

        if ($prevDay->gte($minDate)) {
            $this->viewDate = $prevDay->format('Y-m-d');
            $this->resetPage();
            $this->loadStudentsWithoutReports();
        } else {
            $this->dispatch('warning', 'حداکثر می‌توانید ۲ روز به عقب برگردید.');
        }
    }

    public function goToNextDay(): void
    {
        $effectiveToday = $this->getEffectiveToday();
        $current = $this->getReportDate();

        $nextDay = $current->copy()->addDay();

        if ($nextDay->lte($effectiveToday)) {
            if ($nextDay->isSameDay($effectiveToday)) {
                $this->viewDate = null;
            } else {
                $this->viewDate = $nextDay->format('Y-m-d');
            }
            $this->resetPage();
            $this->loadStudentsWithoutReports();
        }
    }

    public function resetToToday(): void
    {
        $this->viewDate = null;
        $this->resetPage();
        $this->loadStudentsWithoutReports();
    }

    protected function getReportDateDayName(): string
    {
        $dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
        return $dayNames[jdate($this->getReportDate())->getDayOfWeek()];
    }

    /**
     * Get full name with priority: user_profiles.full_name > personal_information.name > user.name
     */
    private function getStudentFullName(\App\Models\User $user): string
    {
        return $user->profile?->full_name
            ?? $user->personalInformation?->name
            ?? $user->name
            ?? 'نامشخص';
    }

    protected function loadStudentsWithoutReports()
    {
        $reportDate = $this->getReportDate();
        $eligibleStudents = Student::with(['user.personalInformation', 'user.profile'])
            ->where('advisor_id', auth()->id())
            ->whereHas('advisingSessions', function ($query) {
                $query->where('status', 'completed')
                    ->where('result_status', 'held');
            })
            ->whereHas('weeklyPrograms', function ($query) use ($reportDate) {

                $query->where('is_active', true)
                    ->where('start_date', '<=', $reportDate)
                    ->where('end_date', '>=', $reportDate);
            })
            ->get();

        // ✅ فقط گزارش‌های عادی (غیرجبرانی) به عنوان "دارای گزارش" حساب می‌شوند
        // گزارش جبرانی مربوط به پارت‌های گذشته است و نباید جای گزارش روزانه را بگیرد
        $studentsWithReports = DailyReport::where('admin_id', auth()->id())
            ->whereDate('report_date', $reportDate)
            ->where('is_compensatory', false)
            ->pluck('student_id')
            ->toArray();

        $studentsOnRestDay = [];
        $studentsWithoutReportsFiltered = [];

        foreach ($eligibleStudents->whereNotIn('id', $studentsWithReports) as $student) {
            $user = $student->user;
            $personalInfo = $user?->personalInformation;

            if (!$user || !$personalInfo) continue;

            $studentData = [
                'student_id' => $student->id,
                'name' => $this->getStudentFullName($user),
                'grade' => $personalInfo->grade ?? '-',
                'field' => $this->getFieldLabel($personalInfo->field ?? ''),
                'mobile' => $user->mobile ?? '-',
                'father_mobile' => $personalInfo->father_mobile ?? '-',
                'mother_mobile' => $personalInfo->mother_mobile ?? '-',
            ];

            // بررسی روز استراحت
            if ($this->isStudentRestDay($student->id, $reportDate)) {
                $studentsOnRestDay[] = $studentData;
            } else {
                $studentsWithoutReportsFiltered[] = $studentData;
            }
        }

        $this->studentsWithoutReports = $studentsWithoutReportsFiltered;
        $this->studentsOnRestDay = $studentsOnRestDay;
    }

    public function sendMissingReportNotification(int $studentId): void
    {
        if (in_array($studentId, $this->notificationSentStudents)) {
            $this->dispatch('warning', 'نوتیفیکیشن قبلاً برای این دانش‌آموز ارسال شده است.');
            return;
        }

        $student = Student::with(['user.personalInformation', 'user.profile'])->find($studentId);
        if (!$student) {
            $this->dispatch('warning', 'دانش‌آموز یافت نشد.');
            return;
        }

        $user = $student->user;
        $name = $this->getStudentFullName($user);

        $message = "{$name} عزیز،\nشما تا الان گزارش امروز خودرا ارسال نکرده اید لطفا هرچه سریع تر اقدام کنید.\nبا تشکر";
        $title = 'یادآوری ارسال گزارش روزانه';

        NotificationService::sendToStudent($studentId, $title, $message);

        $this->notificationSentStudents[] = $studentId;
        $this->dispatch('success', "نوتیفیکیشن برای {$name} با موفقیت ارسال شد.");
    }


    protected function getFieldLabel(string $field): string
    {
        return match ($field) {
            'math' => 'ریاضی',
            'experimental' => 'تجربی',
            'human' => 'انسانی',
            default => '-',
        };
    }

    protected function isStudentRestDay(int $studentId, Carbon $date): bool
    {
        $program = WeeklyProgram::where('student_id', $studentId)
            ->where('is_active', true)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->latest('start_date')
            ->first();

        if (!$program) {
            return false;
        }

        $startDate = Carbon::parse($program->start_date);
        $dayIndex = $startDate->diffInDays($date);

        if ($dayIndex < 0 || $dayIndex > 7) {
            return false;
        }

        return WeeklyProgramRestDay::where('weekly_program_id', $program->id)
            ->where('day_index', $dayIndex)
            ->exists();
    }

    private function resetSelection()
    {
        $this->selectedReports = [];
        $this->selectAll = false;
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $currentPageIds = $this->getCurrentPageReportIds();
            $this->selectedReports = array_unique(array_merge($this->selectedReports, $currentPageIds));
        } else {
            $currentPageIds = $this->getCurrentPageReportIds();
            $this->selectedReports = array_diff($this->selectedReports, $currentPageIds);
        }
    }

    public function updatedSelectedReports()
    {
        $currentPageIds = $this->getCurrentPageReportIds();

        if (empty($currentPageIds)) {
            $this->selectAll = false;
            return;
        }

        $selectedInCurrentPage = array_intersect($this->selectedReports, $currentPageIds);
        $this->selectAll = count($selectedInCurrentPage) === count($currentPageIds);
    }

    private function getCurrentPageReportIds()
    {
        $reportDate = $this->getReportDate();

        return DailyReport::where('admin_id', auth()->id())
            ->whereDate('report_date', $reportDate)
            ->whereHas('detail', fn($q) => $q->where('status', 'pending'))
            ->latest()
            ->paginate(10)
            ->pluck('id')
            ->toArray();
    }

    public function updatingPage()
    {
        $this->selectAll = false;
    }

    public function bulkAction($action)
    {
        if (empty($this->selectedReports)) {
            $this->dispatch('warning', 'هیچ گزارشی انتخاب نشده است.');
            return;
        }

        if (!in_array($action, ['approved', 'rejected'])) {
            $this->dispatch('warning', 'عملیات نامعتبر است.');
            return;
        }

        DailyReportDetail::whereIn('daily_report_id', $this->selectedReports)->update(['status' => $action]);

        $reports = DailyReport::with(['student.user.personalInformation', 'student.user.profile'])
            ->whereIn('id', $this->selectedReports)
            ->get();

        foreach ($reports as $report) {
            $studentName = $this->getStudentFullName($report->student->user);
            $reportDate = jdate($report->report_date)->format('Y/m/d');

            if ($action === 'approved') {
                $message = "{$studentName} عزیز\nگزارش مطالعه شما در تاریخ {$reportDate} تایید شد. به همین روند ادامه بده!\nبا تشکر";
                $title = 'تایید گزارش روزانه';
            } else {
                $message = "{$studentName} عزیز\nگزارش مطالعه شما در تاریخ {$reportDate} رد شد. لطفاً گزارش را بررسی و اصلاح کنید.\nبا تشکر";
                $title = 'رد گزارش روزانه';
            }

            NotificationService::sendToStudent($report->student_id, $title, $message);
        }

        $this->resetSelection();
        $this->dispatch('success', 'عملیات گروهی با موفقیت انجام شد.');
    }


    public function delete($reportId)
    {
        DailyReport::query()
            ->where('id', $reportId)
            ->where('admin_id', auth()->id())
            ->delete();

        $this->selectedReports = array_diff($this->selectedReports, [$reportId]);
        $this->dispatch('success', 'گزارش با موفقیت حذف شد.');
    }

    public function openCommentModal(int $reportId)
    {
        $report = DailyReport::with(['student.user.personalInformation', 'student.user.profile', 'feedback', 'detail'])
            ->where('id', $reportId)
            ->where('admin_id', auth()->id())
            ->firstOrFail();

        $this->commentReportId = $reportId;
        $this->advisorCommentInput = $report->feedback->advisor_comment ?? '';
        $this->commentStatusInput = $report->detail->status ?? 'pending';
        $this->advisorCommentReadonly = !empty($report->feedback->advisor_comment);
        $this->commentStudentName = $this->getStudentFullName($report->student->user);
        $this->commentStudentReply = $report->feedback->student_reply;
        $this->commentModalOpen = true;
    }

    public function closeCommentModal()
    {
        $this->commentModalOpen = false;
        $this->advisorCommentInput = '';
        $this->commentStatusInput = 'pending';
        $this->commentReportId = null;
        $this->advisorCommentReadonly = false;
        $this->commentStudentReply = null;
        $this->commentStudentName = '';
        $this->resetErrorBag(['advisorCommentInput', 'commentStatusInput']);
    }

    public function saveAdvisorComment()
    {
        if (!$this->commentReportId) return;

        $report = DailyReport::with(['feedback', 'detail', 'student.user.personalInformation', 'student.user.profile'])
            ->where('id', $this->commentReportId)
            ->where('admin_id', auth()->id())
            ->firstOrFail();

        if (!empty($report->feedback->advisor_comment)) {
            $this->dispatch('warning', 'برای این گزارش قبلاً نظری ثبت شده است.');
            $this->closeCommentModal();
            return;
        }

        $validated = $this->validate([
            'advisorCommentInput' => 'nullable|string|max:1000',
            'commentStatusInput' => 'required|in:pending,approved,rejected',
        ], [
            'advisorCommentInput.max' => 'طول نظر نمی‌تواند بیشتر از ۱۰۰۰ کاراکتر باشد.',
            'commentStatusInput.required' => 'انتخاب وضعیت الزامی است.',
            'commentStatusInput.in' => 'وضعیت نامعتبر است.',
        ]);

        if (!empty($validated['advisorCommentInput'])) {
            $report->feedback->update([
                'advisor_comment' => $validated['advisorCommentInput'],
                'advisor_commented_at' => now(),
            ]);
        }

        $report->detail->update([
            'status' => $validated['commentStatusInput'],
        ]);

        if (in_array($validated['commentStatusInput'], ['approved', 'rejected'])) {
            $studentName = $this->getStudentFullName($report->student->user);
            $reportDate = jdate($report->report_date)->format('Y/m/d');

            if ($validated['commentStatusInput'] === 'approved') {
                $message = "{$studentName} عزیز\nگزارش مطالعه شما در تاریخ {$reportDate} تایید شد. به همین روند ادامه بده!\nبا تشکر";
                $title = 'تایید گزارش روزانه';
            } else {
                $message = "{$studentName} عزیز\nگزارش مطالعه شما در تاریخ {$reportDate} رد شد. لطفاً گزارش را بررسی و اصلاح کنید.\nبا تشکر";
                $title = 'رد گزارش روزانه';
            }

            NotificationService::sendToStudent($report->student_id, $title, $message);
        }

        $this->dispatch('success', 'نظر و وضعیت با موفقیت ثبت شد.');
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
        ])
            ->where('id', $reportId)
            ->where('admin_id', auth()->id())
            ->firstOrFail();

        $this->selectedReportId = $reportId;

        $dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];

        $personalInfo = $report->student->user->personalInformation;


        $this->selectedReportData = [
            'student_name' => $this->getStudentFullName($report->student->user),
            'student_grade' => $personalInfo->grade ?? '-',
            'student_field' => $this->getFieldLabel($personalInfo->field ?? ''),
            'report_date' => jdate($report->report_date)->format('Y/m/d'),
            'day_name' => $dayNames[jdate($report->report_date)->getDayOfWeek()] ?? '-',
            'description' => $report->detail?->description ?? '',
            'missed_parts_reason' => $report->detail?->missed_parts_reason ?? '',
            'rating' => 0,
            'rating_label' => 'ثبت نشده',
            'is_compensatory' => $report->is_compensatory,
            'status' => $report->detail->status ?? 'pending',
            'advisor_comment' => $report->feedback->advisor_comment ?? '',
            'student_reply' => $report->feedback->student_reply ?? '',
            'created_at' => $report->created_at ? jdate($report->created_at)->format('Y/m/d H:i') : '-',
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

        $partIds = $programParts->pluck('id')->filter()->toArray();

        // Get study sessions with timing and feedback for all parts (only if we have part IDs)
        $studySessionsMap = collect();
        if (!empty($partIds)) {
            $studySessionsMap = StudyPartSession::where('student_id', $report->student_id)
                ->whereIn('program_part_id', $partIds)
                ->where('is_completed', true)
                ->with(['timing', 'feedback'])
                ->get()->groupBy('program_part_id')->map(fn($sessions) => $sessions->sortByDesc('started_at')->first());
        }
        $this->reportPartsDetails = [];
        $totalTests = 0;
        $doneTests = 0;
        $totalParts = 0;
        $readParts = 0;
        $ratingSum = 0;

        foreach ($programParts as $programPart) {
            if (!$programPart) continue;
            $reportPart = $reportPartsMap->get($programPart->id);
            $studySession = $studySessionsMap->get($programPart->id);
            $isRead = $reportPart?->is_read ?? false;
            $testsDone = $reportPart?->tests_done ?? 0;
            $testCount = $programPart->test_count ?? 0;

            $totalParts++;
            if ($isRead) $readParts++;
            $totalTests += $testCount;
            $doneTests += $testsDone;
            $studyDuration = 0;
            if ($studySession) {
                $studyDuration = $studySession->timing?->duration_seconds
                    ?? ($studySession->started_at && $studySession->ended_at
                        ? $studySession->started_at->diffInSeconds($studySession->ended_at)
                        : 0);
            }

            // Rating from session_feedbacks (1-10), 0 if no feedback
            $sessionRating = $studySession?->feedback?->rating ?? 0;
            $ratingSum += $sessionRating;

            $this->reportPartsDetails[] = [
                'id' => $programPart->id,
                'lesson_name' => $programPart->lesson_name,
                'subject_name' => $programPart->ccSubject->name ?? null,
                'chapter_name' => $programPart->ccChapter->name ?? null,
                'topic_name' => $programPart->ccTopic->name ?? null,
                'duration_minutes' => $programPart->duration_minutes,
                'part_type' => $programPart->part_type ?? null,
                'part_type_label' => $programPart->part_type_label ?? '-',
                'lesson_type_label' => $programPart->lesson_type_label ?? '-',
                'is_read' => $isRead,
                'tests_done' => $testsDone,
                'test_count' => $testCount,
                'session_rating' => $sessionRating,
                'has_report' => $reportPart !== null,
                'is_compensatory' => $reportPart?->is_compensatory ?? false,
                'has_study_session' => $studySession !== null,
                'study_duration_seconds' => $studyDuration,
                'study_started_at' => $studySession?->started_at?->format('H:i') ?? null,
                'study_ended_at' => $studySession?->ended_at?->format('H:i') ?? null,
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

        // ✅ بارگذاری پارت‌های اضافه بر سازمان در بازه این روز
        $reportDate = Carbon::parse($report->report_date);
        $windowStart = $reportDate->copy()->startOfDay();
        $windowEnd   = $reportDate->copy()->addDay()->setHour(self::REPORT_CUTOFF_HOUR)->setMinute(0)->setSecond(0);

        $makeupSessions = MakeupSession::where('student_id', $report->student_id)
            ->whereNotNull('ended_at')
            ->whereBetween('ended_at', [$windowStart, $windowEnd])
            ->with('ccTopic')
            ->get();

        $this->selectedReportData['makeup_sessions'] = $makeupSessions->map(fn($ms) => [
            'topic_name'       => $ms->ccTopic?->name ?? 'نامشخص',
            'part_type_label'  => $ms->part_type_label,
            'duration_minutes' => $ms->started_at && $ms->ended_at
                ? (int) $ms->started_at->diffInMinutes($ms->ended_at)
                : 0,
            'ended_at'         => $ms->ended_at ? $ms->ended_at->format('H:i') : '-',
        ])->toArray();

        $this->detailModalOpen = true;

    }

    public function closeDetailModal()
    {
        $this->detailModalOpen = false;
        $this->selectedReportId = null;
        $this->reportPartsDetails = [];
        $this->selectedReportData = [];
    }

    public function openStudentInfoModal(string $type)
    {
        if ($type === 'rest') {
            $this->studentInfoModalTitle = 'دانش‌آموزان با روز استراحت';
            $this->studentInfoList = $this->studentsOnRestDay;
        } else {
            $this->studentInfoModalTitle = 'دانش‌آموزان بدون گزارش';
            $this->studentInfoList = $this->studentsWithoutReports;
        }
        $this->studentInfoModalOpen = true;
    }

    public function closeStudentInfoModal()
    {
        $this->studentInfoModalOpen = false;
        $this->studentInfoList = [];
        $this->studentInfoModalTitle = '';
    }

    public function showAllReportsConfirmation()
    {
        $this->allReportsModalOpen = true;
        $this->allReportsConfirmed = false;
        $this->allUnconfirmedReports = [];
    }

    public function confirmLoadAllReports()
    {
        $this->allUnconfirmedReports = DailyReport::with([
            'student.user.personalInformation',
            'student.user.profile',
            'detail',
            'reportParts.programPart',
            'weeklyProgram.parts',
            'feedback',
        ])
            ->where('admin_id', auth()->id())
            ->whereHas('detail', fn($q) => $q->where('status', 'pending'))
            ->latest()
            ->get()
            ->map(function ($report) {
                $reportPartsMap = $report->reportParts->keyBy('program_part_id');

                // Compensatory reports span multiple days; use reportParts directly
                if ($report->is_compensatory) {
                    $allDayParts = collect();
                } else {
                    // Get ALL program parts for this day from weekly program
                    $allDayParts = $report->getProgramPartsForDay();
                }

                if ($allDayParts->isNotEmpty()) {
                    $totalParts = $allDayParts->count();
                    $readParts = $allDayParts->filter(fn($p) => $reportPartsMap->get($p->id)?->is_read ?? false)->count();
                    $totalTests = (int) $allDayParts->sum('test_count');
                    $doneTests = $allDayParts->sum(fn($p) => $reportPartsMap->get($p->id)?->tests_done ?? 0);
                } else {
                    $readParts = $report->reportParts->where('is_read', true)->count();
                    $totalParts = $report->reportParts->count();
                    $totalTests = $report->reportParts->sum(fn($p) => $p->programPart?->test_count ?? 0);
                    $doneTests = $report->reportParts->sum('tests_done');
                }

                $ratingVal = (float) $report->calculated_rating;

                return [
                    'id' => $report->id,
                    'student_name' => $this->getStudentFullName($report->student->user),
                    'report_date' => jdate($report->report_date)->format('Y/m/d'),
                    'day_name' => $report->day_name,
                    'read_parts' => $readParts,
                    'total_parts' => $totalParts,
                    'done_tests' => $doneTests,
                    'total_tests' => $totalTests,
                    'rating' => $ratingVal,
                    'rating_label' => $this->getRatingLabel($ratingVal),
                    'is_compensatory' => $report->is_compensatory,
                    'description' => $report->detail->description ?? '',
                    'created_at' => $report->created_at ? jdate($report->created_at)->format('Y/m/d H:i') : '-',
                ];
            })
            ->toArray();

        $this->allReportsConfirmed = true;
    }

    public function closeAllReportsModal()
    {
        $this->allReportsModalOpen = false;
        $this->allReportsConfirmed = false;
        $this->allUnconfirmedReports = [];
    }

    public function getStatusColor($status): string
    {
        return match ($status) {
            'pending' => 'primary',
            'approved' => 'success',
            'rejected' => 'danger',
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

    public function getRatingBadgeClass($rating): string
    {
        $rating = (float) $rating;
        return match (true) {
            $rating >= 9 => 'bg-success',
            $rating >= 7 => 'bg-primary',
            $rating >= 5 => 'bg-warning text-dark',
            $rating >= 3 => 'bg-orange text-dark',
            $rating > 0  => 'bg-danger',
            default      => 'bg-secondary',
        };    }

    public function render()
    {
        $reportDate = $this->getReportDate();
        $effectiveToday = $this->getEffectiveToday();
        $minDate = $effectiveToday->copy()->subDays(2);
        $reports = DailyReport::with([
            'student.user.personalInformation',
            'student.user.profile',
            'weeklyProgram',
            'reportParts.programPart',
            'weeklyProgram.parts',
            'detail',
            'feedback',
        ])
            ->where('admin_id', auth()->id())
            ->whereDate('report_date', $reportDate)
            ->whereHas('detail', fn($q) => $q->where('status', 'pending'))
            ->latest()
            ->paginate(10);

        $this->loadStudentsWithoutReports();

        return view('livewire.admin.student.reports.report-daily', [
            'reports' => $reports,
            'reportDateJalali' => $this->getReportDateJalali(),
            'reportDateDayName' => $this->getReportDateDayName(),
            'studentsWithoutReports' => $this->studentsWithoutReports,
            'studentsOnRestDay' => $this->studentsOnRestDay,
            'canGoBack' => $reportDate->copy()->subDay()->gte($minDate),
            'canGoForward' => !is_null($this->viewDate),
            'isViewingPast' => !is_null($this->viewDate),
        ])->layout('layouts.admin.app');
    }
}
