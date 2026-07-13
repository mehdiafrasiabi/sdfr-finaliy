<?php

namespace App\Livewire\Admin\TrialAcquisition;

use App\Models\AdvisingPreSession;
use App\Models\DailyReport;
use App\Models\DailyReportDetail;
use App\Models\MakeupSession;
use App\Models\ProgramPart;
use App\Models\Student;
use App\Models\StudyPartSession;
use App\Models\TrialWeek;
use App\Models\WeeklyProgram;
use App\Models\WeeklyProgramRestDay;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Monitor extends Component
{
    use WithPagination;

    const REPORT_CUTOFF_HOUR = 6;

    public string $search = '';
    public string $reportStatus = 'pending';
    public ?int $selectedTrialId = null;
    public ?string $viewDate = null;

    public array $selectedReports = [];
    public bool $selectAll = false;

    public bool $commentModalOpen = false;
    public ?int $commentReportId = null;
    public string $advisorCommentInput = '';
    public string $commentStatusInput = 'pending';
    public bool $advisorCommentReadonly = false;
    public string $commentStudentName = '';
    public ?string $commentStudentReply = null;

    public bool $detailModalOpen = false;
    public ?int $selectedReportId = null;
    public array $reportPartsDetails = [];
    public array $selectedReportData = [];

    public bool $dayDetailModalOpen = false;
    public array $selectedDayData = [];
    public array $dayPartsDetails = [];

    protected $paginationTheme = 'bootstrap';

    public function mount(?TrialWeek $trialWeek = null): void
    {
        if ($trialWeek && $this->canAccessTrial($trialWeek)) {
            $this->selectedTrialId = $trialWeek->id;
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingReportStatus(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function selectTrial(int $trialId): void
    {
        $trial = $this->baseTrialQuery()->find($trialId);
        if (! $trial) {
            $this->dispatch('warning', 'دانش‌آموز برای شما یافت نشد.');
            return;
        }

        $this->selectedTrialId = $trial->id;
        $this->resetPage();
        $this->resetSelection();
    }

    protected function baseTrialQuery(): Builder
    {
        $query = TrialWeek::query()->with(['user.personalInformation', 'student', 'acquisitionSupporter']);

        if (! Auth::guard('admin')->user()?->hasRole('super admin')) {
            $query->where('acquisition_supporter_id', Auth::guard('admin')->id());
        }

        return $query;
    }

    protected function canAccessTrial(TrialWeek $trialWeek): bool
    {
        return Auth::guard('admin')->user()?->hasRole('super admin')
            || (int) $trialWeek->acquisition_supporter_id === (int) Auth::guard('admin')->id();
    }

    protected function scopedStudentIds()
    {
        return $this->baseTrialQuery()
            ->when($this->selectedTrialId, fn ($q) => $q->whereKey($this->selectedTrialId))
            ->whereNotNull('student_id')
            ->pluck('student_id');
    }

    protected function getReportDate(): Carbon
    {
        if ($this->viewDate) {
            return Carbon::parse($this->viewDate);
        }

        return now()->hour < self::REPORT_CUTOFF_HOUR ? Carbon::yesterday() : Carbon::today();
    }

    protected function getEffectiveToday(): Carbon
    {
        return now()->hour < self::REPORT_CUTOFF_HOUR ? Carbon::yesterday() : Carbon::today();
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
            $this->resetSelection();
            return;
        }

        $this->dispatch('warning', 'حداکثر می‌توانید ۲ روز به عقب برگردید.');
    }

    public function goToNextDay(): void
    {
        $effectiveToday = $this->getEffectiveToday();
        $nextDay = $this->getReportDate()->copy()->addDay();

        if ($nextDay->lte($effectiveToday)) {
            $this->viewDate = $nextDay->isSameDay($effectiveToday) ? null : $nextDay->format('Y-m-d');
            $this->resetPage();
            $this->resetSelection();
        }
    }

    public function resetToToday(): void
    {
        $this->viewDate = null;
        $this->resetPage();
        $this->resetSelection();
    }

    protected function reportsQuery(): Builder
    {
        return DailyReport::query()
            ->with([
                'student.user.personalInformation',
                'student.user.profile',
                'weeklyProgram.parts',
                'reportParts.programPart',
                'detail',
                'feedback',
            ])
            ->whereIn('student_id', $this->scopedStudentIds())
            ->whereDate('report_date', $this->getReportDate())
            ->when($this->reportStatus !== 'all', fn ($q) => $q->whereHas('detail', fn ($d) => $d->where('status', $this->reportStatus)))
            ->latest();
    }

    protected function scopedReport(int $reportId): DailyReport
    {
        return DailyReport::with([
            'student.user.personalInformation',
            'student.user.profile',
            'weeklyProgram.parts',
            'reportParts.programPart.ccSubject',
            'reportParts.programPart.ccTopic',
            'reportParts.programPart.ccChapter',
            'detail',
            'feedback',
        ])
            ->whereIn('student_id', $this->scopedStudentIds())
            ->findOrFail($reportId);
    }

    public function updatedSelectAll($value): void
    {
        $currentPageIds = $this->reportsQuery()->paginate(10)->pluck('id')->toArray();
        $this->selectedReports = $value
            ? array_values(array_unique(array_merge($this->selectedReports, $currentPageIds)))
            : array_values(array_diff($this->selectedReports, $currentPageIds));
    }

    public function updatedSelectedReports(): void
    {
        $currentPageIds = $this->reportsQuery()->paginate(10)->pluck('id')->toArray();
        $this->selectAll = ! empty($currentPageIds)
            && count(array_intersect($this->selectedReports, $currentPageIds)) === count($currentPageIds);
    }

    public function bulkAction(string $action): void
    {
        if (empty($this->selectedReports)) {
            $this->dispatch('warning', 'هیچ گزارشی انتخاب نشده است.');
            return;
        }

        if (! in_array($action, ['approved', 'rejected'], true)) {
            $this->dispatch('warning', 'عملیات نامعتبر است.');
            return;
        }

        $reports = DailyReport::with(['student.user.personalInformation', 'student.user.profile'])
            ->whereIn('id', $this->selectedReports)
            ->whereIn('student_id', $this->scopedStudentIds())
            ->get();

        DailyReportDetail::whereIn('daily_report_id', $reports->pluck('id'))->update(['status' => $action]);

        foreach ($reports as $report) {
            $this->sendReportStatusNotification($report, $action);
        }

        $this->resetSelection();
        $this->dispatch('success', 'عملیات گروهی با موفقیت انجام شد.');
    }

    public function setReportStatus(int $reportId, string $status): void
    {
        if (! in_array($status, ['approved', 'rejected'], true)) {
            return;
        }

        $report = $this->scopedReport($reportId);
        $report->detail()->updateOrCreate(['daily_report_id' => $report->id], ['status' => $status]);
        $this->sendReportStatusNotification($report, $status);
        $this->dispatch('success', $status === 'approved' ? 'گزارش تایید شد.' : 'گزارش رد شد.');
    }

    public function openCommentModal(int $reportId): void
    {
        $report = $this->scopedReport($reportId);

        $this->commentReportId = $report->id;
        $this->advisorCommentInput = $report->feedback->advisor_comment ?? '';
        $this->commentStatusInput = $report->detail->status ?? 'pending';
        $this->advisorCommentReadonly = ! empty($report->feedback->advisor_comment);
        $this->commentStudentName = $this->studentName($report->student);
        $this->commentStudentReply = $report->feedback->student_reply;
        $this->commentModalOpen = true;
    }

    public function closeCommentModal(): void
    {
        $this->reset(['commentModalOpen', 'commentReportId', 'advisorCommentInput', 'commentStudentName', 'commentStudentReply']);
        $this->commentStatusInput = 'pending';
        $this->advisorCommentReadonly = false;
    }

    public function saveAdvisorComment(): void
    {
        if (! $this->commentReportId) {
            return;
        }

        $report = $this->scopedReport($this->commentReportId);

        if (! empty($report->feedback->advisor_comment)) {
            $this->dispatch('warning', 'برای این گزارش قبلا نظری ثبت شده است.');
            $this->closeCommentModal();
            return;
        }

        $validated = $this->validate([
            'advisorCommentInput' => ['nullable', 'string', 'max:1000'],
            'commentStatusInput' => ['required', 'in:pending,approved,rejected'],
        ]);

        $report->feedback()->updateOrCreate(
            ['daily_report_id' => $report->id],
            [
                'advisor_comment' => $validated['advisorCommentInput'] ?: null,
                'advisor_commented_at' => $validated['advisorCommentInput'] ? now() : null,
            ]
        );

        $report->detail()->updateOrCreate(
            ['daily_report_id' => $report->id],
            ['status' => $validated['commentStatusInput']]
        );

        if (in_array($validated['commentStatusInput'], ['approved', 'rejected'], true)) {
            $this->sendReportStatusNotification($report, $validated['commentStatusInput']);
        }

        if ($validated['advisorCommentInput']) {
            NotificationService::sendToStudent(
                $report->student_id,
                'پیام مشاور درباره گزارش روزانه',
                $validated['advisorCommentInput'],
                Auth::guard('admin')->id()
            );
        }

        $this->dispatch('success', 'نظر و وضعیت با موفقیت ثبت شد.');
        $this->closeCommentModal();
    }

    public function openDetailModal(int $reportId): void
    {
        $report = $this->scopedReport($reportId);
        $this->selectedReportId = $report->id;

        $this->selectedReportData = [
            'student_name' => $this->studentName($report->student),
            'report_date' => jdate($report->report_date)->format('Y/m/d'),
            'day_name' => $report->day_name,
            'description' => $report->detail?->description ?? '',
            'missed_parts_reason' => $report->detail?->missed_parts_reason ?? '',
            'is_compensatory' => $report->is_compensatory,
            'status' => $report->detail->status ?? 'pending',
            'advisor_comment' => $report->feedback->advisor_comment ?? '',
            'student_reply' => $report->feedback->student_reply ?? '',
            'created_at' => $report->created_at ? jdate($report->created_at)->format('Y/m/d H:i') : '-',
        ];

        $programParts = $report->is_compensatory
            ? $report->reportParts->map(fn ($rp) => $rp->programPart)->filter()->values()
            : $report->getProgramPartsForDay();

        if ($programParts->isEmpty()) {
            $programParts = $report->reportParts->map(fn ($rp) => $rp->programPart)->filter()->values();
        }

        $this->reportPartsDetails = $this->buildPartDetails($report->student_id, $programParts, $report);

        $totalParts = count($this->reportPartsDetails);
        $readParts = collect($this->reportPartsDetails)->where('is_read', true)->count();
        $totalTests = collect($this->reportPartsDetails)->sum('test_count');
        $doneTests = collect($this->reportPartsDetails)->sum('tests_done');
        $ratingSum = collect($this->reportPartsDetails)->sum('session_rating');
        $this->selectedReportData['total_parts'] = $totalParts;
        $this->selectedReportData['read_parts'] = $readParts;
        $this->selectedReportData['unread_parts'] = max(0, $totalParts - $readParts);
        $this->selectedReportData['total_tests'] = $totalTests;
        $this->selectedReportData['done_tests'] = $doneTests;
        $this->selectedReportData['rating'] = $totalParts > 0 ? round($ratingSum / $totalParts, 1) : 0;
        $this->selectedReportData['rating_label'] = $this->getRatingLabel($this->selectedReportData['rating']);
        $this->selectedReportData['makeup_sessions'] = $this->makeupSessionsForReport($report);

        $this->detailModalOpen = true;
    }

    public function closeDetailModal(): void
    {
        $this->detailModalOpen = false;
        $this->selectedReportId = null;
        $this->reportPartsDetails = [];
        $this->selectedReportData = [];
    }

    public function openDayDetailModal(int $dayIndex): void
    {
        $selectedTrial = $this->selectedTrialId
            ? $this->baseTrialQuery()->with(['student.user.personalInformation', 'student.user.profile'])->find($this->selectedTrialId)
            : null;

        if (! $selectedTrial?->student_id) {
            $this->dispatch('warning', 'دانش‌آموز انتخاب نشده است.');
            return;
        }

        $weeklyProgram = WeeklyProgram::with(['parts.ccChapter', 'parts.ccTopic', 'parts.ccSubject', 'restDays', 'examDays'])
            ->where('student_id', $selectedTrial->student_id)
            ->where('is_active', true)
            ->latest('start_date')
            ->first();

        if (! $weeklyProgram || $dayIndex < 0 || $dayIndex > 7) {
            $this->dispatch('warning', 'روز برنامه یافت نشد.');
            return;
        }

        $startDate = Carbon::parse($weeklyProgram->start_date);
        $date = $startDate->copy()->addDays($dayIndex);
        $report = $this->reportForDay($selectedTrial->student_id, $weeklyProgram->id, $date);

        if ($report) {
            $this->openDetailModal($report->id);
            return;
        }

        $dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
        $parts = $weeklyProgram->parts
            ->where('day_of_week', $dayIndex)
            ->sortBy('part_order')
            ->values();

        $this->selectedDayData = [
            'student_name' => $this->studentName($selectedTrial->student),
            'day_index' => $dayIndex,
            'day_name' => $dayNames[jdate($date)->getDayOfWeek()] ?? '-',
            'date' => jdate($date)->format('Y/m/d'),
            'status_label' => $date->gt($this->getEffectiveToday()) ? 'روز فرا نرسیده' : 'گزارش ارسال نشده',
            'status_color' => $date->gt($this->getEffectiveToday()) ? 'secondary' : 'danger',
            'total_parts' => $parts->count(),
            'total_time' => $this->formatMinutes((int) $parts->sum('duration_minutes')),
            'total_tests' => (int) $parts->sum('test_count'),
            'is_rest_day' => $weeklyProgram->restDays->contains('day_index', $dayIndex),
            'is_exam_day' => $weeklyProgram->examDays->contains('day_index', $dayIndex),
        ];

        $this->dayPartsDetails = $this->buildPartDetails($selectedTrial->student_id, $parts, null);
        $this->dayDetailModalOpen = true;
    }

    public function closeDayDetailModal(): void
    {
        $this->dayDetailModalOpen = false;
        $this->selectedDayData = [];
        $this->dayPartsDetails = [];
    }

    protected function reportForDay(int $studentId, int $weeklyProgramId, Carbon $date): ?DailyReport
    {
        return DailyReport::with([
            'student.user.personalInformation',
            'student.user.profile',
            'weeklyProgram.parts',
            'reportParts.programPart.ccSubject',
            'reportParts.programPart.ccTopic',
            'reportParts.programPart.ccChapter',
            'detail',
            'feedback',
        ])
            ->where('student_id', $studentId)
            ->where('weekly_program_id', $weeklyProgramId)
            ->where('is_compensatory', false)
            ->whereDate('report_date', $date)
            ->latest()
            ->first();
    }

    protected function buildPartDetails(int $studentId, $programParts, ?DailyReport $report = null): array
    {
        $partIds = $programParts->pluck('id')->filter()->values()->all();
        $reportPartsMap = $report?->reportParts?->keyBy('program_part_id') ?? collect();

        $studySessionsMap = empty($partIds)
            ? collect()
            : StudyPartSession::where('student_id', $studentId)
                ->whereIn('program_part_id', $partIds)
                ->where('is_completed', true)
                ->with(['timing', 'feedback'])
                ->get()
                ->groupBy('program_part_id');

        return $programParts->map(function ($programPart) use ($reportPartsMap, $studySessionsMap) {
            $reportPart = $reportPartsMap->get($programPart->id);
            $sessions = $studySessionsMap->get($programPart->id, collect());
            $latestSession = $sessions->sortByDesc('started_at')->first();
            $studySeconds = $sessions->sum(fn ($session) => $this->studySessionSeconds($session));
            $isDone = (bool) ($reportPart?->is_read ?? false) || $sessions->isNotEmpty();

            return [
                'id' => $programPart->id,
                'lesson_name' => $programPart->lesson_name,
                'chapter_name' => $programPart->ccChapter->name ?? null,
                'topic_name' => $programPart->ccTopic->name ?? null,
                'duration_minutes' => (int) $programPart->duration_minutes,
                'duration_label' => $this->formatMinutes((int) $programPart->duration_minutes),
                'part_type_label' => $programPart->part_type_label,
                'lesson_type_label' => $programPart->lesson_type_label,
                'source_type_label' => $programPart->source_type_label,
                'is_read' => (bool) ($reportPart?->is_read ?? false),
                'is_done' => $isDone,
                'status_label' => $isDone ? 'انجام داده شده' : 'انجام داده نشده',
                'status_color' => $isDone ? 'success' : 'danger',
                'tests_done' => (int) ($reportPart?->tests_done ?? 0),
                'test_count' => (int) ($programPart->test_count ?? 0),
                'session_rating' => $latestSession?->feedback?->rating ?? 0,
                'has_study_session' => $sessions->isNotEmpty(),
                'study_duration_seconds' => $studySeconds,
                'study_duration_label' => $this->formatSeconds($studySeconds),
                'study_started_at' => $latestSession?->started_at?->format('H:i'),
                'study_ended_at' => $latestSession?->ended_at?->format('H:i'),
                'is_early_finish' => (bool) ($latestSession?->is_early_finish ?? false),
                'extra_seconds' => (int) ($latestSession?->extra_seconds ?? 0),
                'extra_target_seconds' => (int) ($latestSession?->extra_target_seconds ?? 0),
                'is_cheating' => (bool) ($latestSession?->is_cheating ?? false),
                'cheat_status' => $latestSession?->cheat_status,
                'cheat_minutes' => (int) ($latestSession?->cheat_minutes ?? 0),
                'cheat_reason' => $latestSession?->cheat_reason,
            ];
        })->values()->toArray();
    }

    protected function studySessionSeconds(StudyPartSession $session): int
    {
        if ($session->timing?->duration_seconds) {
            return (int) $session->timing->duration_seconds;
        }

        if ($session->started_at && $session->ended_at) {
            return (int) $session->started_at->diffInSeconds($session->ended_at);
        }

        return 0;
    }

    protected function completedStudySeconds(int $studentId, ?WeeklyProgram $weeklyProgram, ?Carbon $from = null, ?Carbon $to = null): int
    {
        if (! $weeklyProgram) {
            return 0;
        }

        return StudyPartSession::where('student_id', $studentId)
            ->where('weekly_program_id', $weeklyProgram->id)
            ->where('is_completed', true)
            ->when($from, fn ($q) => $q->where('started_at', '>=', $from))
            ->when($to, fn ($q) => $q->where('started_at', '<=', $to))
            ->with('timing')
            ->get()
            ->sum(fn ($session) => $this->studySessionSeconds($session));
    }

    public function formatMinutes(int $minutes): string
    {
        return $this->formatSeconds($minutes * 60);
    }

    public function formatSeconds(int $seconds): string
    {
        if ($seconds <= 0) {
            return '0 دقیقه';
        }

        $minutes = intdiv($seconds, 60);
        if ($minutes === 0) {
            return 'کمتر از 1 دقیقه';
        }

        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        if ($hours > 0 && $remainingMinutes > 0) {
            return "{$hours} ساعت و {$remainingMinutes} دقیقه";
        }

        if ($hours > 0) {
            return "{$hours} ساعت";
        }

        return "{$remainingMinutes} دقیقه";
    }

    protected function makeupSessionsForReport(DailyReport $report): array
    {
        $reportDate = Carbon::parse($report->report_date);

        return MakeupSession::where('student_id', $report->student_id)
            ->whereNotNull('ended_at')
            ->whereBetween('ended_at', [
                $reportDate->copy()->startOfDay(),
                $reportDate->copy()->addDay()->setHour(self::REPORT_CUTOFF_HOUR)->setMinute(0)->setSecond(0),
            ])
            ->with(['ccChapter.subject', 'ccTopic.chapter'])
            ->get()
            ->map(fn ($session) => [
                'topic_name' => ($session->ccChapter?->name ?? $session->ccTopic?->chapter?->name ?? $session->ccTopic?->name) ?: 'نامشخص',
                'part_type_label' => $session->part_type_label,
                'duration_minutes' => $session->started_at && $session->ended_at ? (int) $session->started_at->diffInMinutes($session->ended_at) : 0,
                'ended_at' => $session->ended_at ? $session->ended_at->format('H:i') : '-',
            ])
            ->toArray();
    }

    protected function sendReportStatusNotification(DailyReport $report, string $status): void
    {
        $studentName = $this->studentName($report->student);
        $reportDate = jdate($report->report_date)->format('Y/m/d');

        $title = $status === 'approved' ? 'تایید گزارش روزانه' : 'رد گزارش روزانه';
        $message = $status === 'approved'
            ? "{$studentName} عزیز\nگزارش مطالعه شما در تاریخ {$reportDate} تایید شد. به همین روند ادامه بده!\nبا تشکر"
            : "{$studentName} عزیز\nگزارش مطالعه شما در تاریخ {$reportDate} رد شد. لطفا گزارش را بررسی و اصلاح کنید.\nبا تشکر";

        NotificationService::sendToStudent($report->student_id, $title, $message, Auth::guard('admin')->id());
    }

    protected function studentName(?Student $student): string
    {
        $user = $student?->user;

        return $user?->profile?->full_name
            ?? $user?->personalInformation?->name
            ?? $user?->name
            ?? 'نامشخص';
    }

    protected function resetSelection(): void
    {
        $this->selectedReports = [];
        $this->selectAll = false;
    }

    protected function monitorSummary(?TrialWeek $selectedTrial, ?WeeklyProgram $weeklyProgram): array
    {
        if (! $selectedTrial?->student_id || ! $weeklyProgram) {
            return [
                'study_until_now' => '0 دقیقه',
                'study_today' => '0 دقیقه',
                'sent_reports_until_now' => 0,
                'missing_reports_until_now' => 0,
                'total_parts' => 0,
                'done_parts' => 0,
            ];
        }

        $studentId = (int) $selectedTrial->student_id;
        $effectiveToday = $this->getEffectiveToday();
        $startDate = Carbon::parse($weeklyProgram->start_date);
        $endDate = $weeklyProgram->end_date
            ? Carbon::parse($weeklyProgram->end_date)
            : $startDate->copy()->addDays(7);
        $lastDueDate = $effectiveToday->lt($endDate) ? $effectiveToday : $endDate;

        $sentReportDates = DailyReport::where('student_id', $studentId)
            ->where('weekly_program_id', $weeklyProgram->id)
            ->where('is_compensatory', false)
            ->whereDate('report_date', '>=', $startDate)
            ->whereDate('report_date', '<=', $lastDueDate)
            ->pluck('report_date')
            ->map(fn ($date) => Carbon::parse($date)->format('Y-m-d'))
            ->unique()
            ->values();

        $restDayIndices = $weeklyProgram->restDays()->pluck('day_index')->map(fn ($i) => (int) $i)->toArray();
        $dueReportDates = collect();
        if ($lastDueDate->gte($startDate)) {
            $maxIndex = min(7, $startDate->diffInDays($lastDueDate));
            for ($i = 0; $i <= $maxIndex; $i++) {
                if (! in_array($i, $restDayIndices, true)) {
                    $dueReportDates->push($startDate->copy()->addDays($i)->format('Y-m-d'));
                }
            }
        }

        $partIds = $weeklyProgram->parts()->pluck('id');
        $doneByReport = \App\Models\DailyReportPart::whereHas('dailyReport', fn ($q) => $q
                ->where('student_id', $studentId)
                ->where('weekly_program_id', $weeklyProgram->id)
                ->where('is_compensatory', false)
            )
            ->whereIn('program_part_id', $partIds)
            ->where('is_read', true)
            ->pluck('program_part_id');

        $doneByStudy = StudyPartSession::where('student_id', $studentId)
            ->where('weekly_program_id', $weeklyProgram->id)
            ->where('is_completed', true)
            ->pluck('program_part_id');

        $donePartCount = $doneByReport->merge($doneByStudy)->filter()->unique()->count();

        return [
            'study_until_now' => $this->formatSeconds($this->completedStudySeconds($studentId, $weeklyProgram, null, now())),
            'study_today' => $this->formatSeconds($this->completedStudySeconds($studentId, $weeklyProgram, Carbon::today(), now())),
            'sent_reports_until_now' => $sentReportDates->count(),
            'missing_reports_until_now' => $dueReportDates->diff($sentReportDates)->count(),
            'total_parts' => $partIds->count(),
            'done_parts' => $donePartCount,
        ];
    }

    protected function weekDays(?WeeklyProgram $weeklyProgram, ?int $studentId = null): array
    {
        if (! $weeklyProgram || ! $studentId) {
            return [];
        }

        $days = [];
        $dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
        $startDate = Carbon::parse($weeklyProgram->start_date);
        $effectiveToday = $this->getEffectiveToday();
        $restDayIndices = $weeklyProgram->restDays()->pluck('day_index')->toArray();
        $examDayIndices = $weeklyProgram->examDays()->pluck('day_index')->toArray();
        $partsByDay = $weeklyProgram->parts()
            ->with(['ccChapter', 'ccTopic', 'ccSubject'])
            ->orderBy('day_of_week')
            ->orderBy('part_order')
            ->get()
            ->groupBy('day_of_week');

        for ($i = 0; $i < 8; $i++) {
            $date = $startDate->copy()->addDays($i);
            $parts = $partsByDay->get($i, collect());
            $report = $this->reportForDay($studentId, $weeklyProgram->id, $date);
            $isFuture = $date->gt($effectiveToday);
            $statusKey = $isFuture ? 'future' : ($report ? 'sent' : 'missing');
            $partsDetails = $this->buildPartDetails($studentId, $parts, $report);

            $days[] = [
                'index' => $i,
                'name' => $dayNames[jdate($date)->getDayOfWeek()] ?? '-',
                'date' => $date,
                'jalali_date' => jdate($date)->format('Y/m/d'),
                'parts' => $partsDetails,
                'total_minutes' => (int) $parts->sum('duration_minutes'),
                'total_time_label' => $this->formatMinutes((int) $parts->sum('duration_minutes')),
                'total_tests' => (int) $parts->sum('test_count'),
                'is_rest_day' => in_array($i, $restDayIndices),
                'is_exam_day' => in_array($i, $examDayIndices),
                'report_id' => $report?->id,
                'status_key' => $statusKey,
                'status_label' => match ($statusKey) {
                    'sent' => 'گزارش ارسال شده',
                    'missing' => 'گزارش ارسال نشده',
                    default => 'روز فرا نرسیده',
                },
                'status_color' => match ($statusKey) {
                    'sent' => 'success',
                    'missing' => 'danger',
                    default => 'secondary',
                },
                'can_view_details' => ! $isFuture,
            ];
        }

        return $days;
    }

    protected function missingReports(array $studentIds): array
    {
        if (empty($studentIds)) {
            return [];
        }

        $reportDate = $this->getReportDate();
        $studentsWithReports = DailyReport::whereIn('student_id', $studentIds)
            ->whereDate('report_date', $reportDate)
            ->where('is_compensatory', false)
            ->pluck('student_id')
            ->toArray();

        return Student::with(['user.personalInformation', 'user.profile'])
            ->whereIn('id', array_diff($studentIds, $studentsWithReports))
            ->whereHas('weeklyPrograms', fn ($q) => $q->where('is_active', true)->where('start_date', '<=', $reportDate)->where('end_date', '>=', $reportDate))
            ->get()
            ->reject(fn ($student) => $this->isStudentRestDay($student->id, $reportDate))
            ->map(fn ($student) => [
                'student_id' => $student->id,
                'name' => $this->studentName($student),
                'mobile' => $student->user?->mobile ?? '-',
            ])
            ->values()
            ->toArray();
    }

    protected function isStudentRestDay(int $studentId, Carbon $date): bool
    {
        $program = WeeklyProgram::where('student_id', $studentId)
            ->where('is_active', true)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->latest('start_date')
            ->first();

        if (! $program) {
            return false;
        }

        $dayIndex = Carbon::parse($program->start_date)->diffInDays($date);
        return $dayIndex >= 0 && $dayIndex <= 7
            && WeeklyProgramRestDay::where('weekly_program_id', $program->id)->where('day_index', $dayIndex)->exists();
    }

    public function getStatusColor(string $status): string
    {
        return match ($status) {
            'pending' => 'warning',
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
            $rating > 0 => 'خیلی ضعیف',
            default => 'ثبت نشده',
        };
    }

    public function render()
    {
        $trials = $this->baseTrialQuery()
            ->when($this->search, fn ($q) => $q->whereHas('user', fn ($u) => $u
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('mobile', 'like', "%{$this->search}%")
            ))
            ->latest()
            ->limit(30)
            ->get();

        $selectedTrial = $this->selectedTrialId
            ? $this->baseTrialQuery()->with(['user.personalInformation', 'student.user.personalInformation', 'advisingSession'])->find($this->selectedTrialId)
            : $trials->first();

        if (! $this->selectedTrialId && $selectedTrial) {
            $this->selectedTrialId = $selectedTrial->id;
        }

        $weeklyProgram = $selectedTrial?->student_id
            ? WeeklyProgram::with(['parts', 'restDays', 'examDays'])
                ->where('student_id', $selectedTrial->student_id)
                ->where('is_active', true)
                ->latest('start_date')
                ->first()
            : null;

        $preSessions = $selectedTrial?->student_id
            ? AdvisingPreSession::where('student_id', $selectedTrial->student_id)
                ->with(['advisingSession', 'exams', 'assignments', 'qas', 'miscellaneous', 'requestedParts'])
                ->latest()
                ->get()
            : collect();

        $reports = $this->reportsQuery()->paginate(10);
        $studentIds = $this->scopedStudentIds()->map(fn ($id) => (int) $id)->toArray();
        $reportDate = $this->getReportDate();
        $effectiveToday = $this->getEffectiveToday();

        return view('livewire.admin.trial-acquisition.monitor', [
            'trials' => $trials,
            'selectedTrial' => $selectedTrial,
            'weeklyProgram' => $weeklyProgram,
            'monitorSummary' => $this->monitorSummary($selectedTrial, $weeklyProgram),
            'weekDays' => $this->weekDays($weeklyProgram, $selectedTrial?->student_id ? (int) $selectedTrial->student_id : null),
            'preSessions' => $preSessions,
            'reports' => $reports,
            'missingReports' => $this->missingReports($studentIds),
            'reportDateJalali' => jdate($reportDate)->format('Y/m/d'),
            'reportDateDayName' => ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'][jdate($reportDate)->getDayOfWeek()] ?? '-',
            'canGoBack' => $reportDate->copy()->subDay()->gte($effectiveToday->copy()->subDays(2)),
            'canGoForward' => ! is_null($this->viewDate),
            'isViewingPast' => ! is_null($this->viewDate),
        ])->layout('layouts.admin.app');
    }
}
