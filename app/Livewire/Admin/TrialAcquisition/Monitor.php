<?php

namespace App\Livewire\Admin\TrialAcquisition;

use App\Models\DailyReport;
use App\Models\MakeupSession;
use App\Models\ProgramPart;
use App\Models\Student;
use App\Models\StudentExamSchedule;
use App\Models\TrialAcquisitionCall;
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
    public string $reportStatus = 'all';
    public ?int $selectedTrialId = null;
    public ?string $viewDate = null;

    public bool $commentModalOpen = false;
    public ?int $commentReportId = null;
    public string $advisorCommentInput = '';
    public string $commentStatusInput = '';
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

    public ?int $extraCallTrialId = null;
    public ?int $extraTalkSeconds = null;
    public string $extraCallPhase = 'ringing';
    public string $extraCallNotes = '';

    protected $paginationTheme = 'bootstrap';

    public function mount(?TrialWeek $trialWeek = null): void
    {
        if ($trialWeek && $this->baseTrialQuery()->whereKey($trialWeek->id)->exists()) {
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
    }

    protected function baseTrialQuery(): Builder
    {
        $query = TrialWeek::query()->visibleForAcquisition()->with([
            'user.personalInformation',
            'user.profile',
            'student',
            'trialAcquisitionCalls',
            'acquisitionSupporter',
            'student.examSchedules' => fn ($scheduleQuery) => $scheduleQuery
                ->with(['setting', 'days', 'weeklyProgram'])
                ->latest('program_built_at')
                ->latest('updated_at'),
        ]);

        if (! Auth::guard('admin')->user()?->hasRole('super admin')) {
            $query->where('acquisition_supporter_id', Auth::guard('admin')->id());
        }

        return $this->applyStudentTypeScope($query);
    }

    protected function applyStudentTypeScope(Builder $query): Builder
    {
        return $query->whereDoesntHave('student.examSchedules');
    }

    protected function builtExamProgramConstraint(Builder $query): void
    {
        $query->whereNotNull('weekly_program_id')
            ->whereNotNull('program_built_at');
    }

    protected function applySearch(Builder $query): Builder
    {
        $term = trim($this->search);

        if ($term === '') {
            return $query;
        }

        return $query->where(function (Builder $searchQuery) use ($term) {
            $searchQuery
                ->whereHas('user', function (Builder $userQuery) use ($term) {
                    $userQuery->where('name', 'like', "%{$term}%")
                        ->orWhere('mobile', 'like', "%{$term}%");
                })
                ->orWhereHas('user.personalInformation', function (Builder $infoQuery) use ($term) {
                    $infoQuery->where('name', 'like', "%{$term}%")
                        ->orWhere('name_full', 'like', "%{$term}%")
                        ->orWhere('father_mobile', 'like', "%{$term}%")
                        ->orWhere('mother_mobile', 'like', "%{$term}%");
                });
        });
    }

    protected function canAccessTrial(TrialWeek $trialWeek): bool
    {
        return Auth::guard('admin')->user()?->hasRole('super admin')
            || (int) $trialWeek->acquisition_supporter_id === (int) Auth::guard('admin')->id();
    }

    protected function hasSuccessfulCall(?TrialWeek $trialWeek): bool
    {
        if (! $trialWeek) {
            return false;
        }

        return $trialWeek->trialAcquisitionCalls
            ->contains(fn ($call) => (bool) $call->answered && $call->stage !== TrialAcquisitionCall::STAGE_EXTRA);
    }

    protected function examProgramSummary(?TrialWeek $trialWeek): ?array
    {
        $schedule = $this->currentExamSchedule($trialWeek);

        if (! $schedule) {
            return null;
        }

        $stage = $this->examProgramStage($trialWeek);

        return [
            'title' => $schedule->setting?->title ?? 'برنامه امتحانی',
            'days_count' => $schedule->days->count(),
            'exam_range' => $schedule->exam_starts_at && $schedule->exam_ends_at
                ? jdate($schedule->exam_starts_at)->format('Y/m/d') . ' تا ' . jdate($schedule->exam_ends_at)->format('Y/m/d')
                : 'بازه ثبت نشده',
            'program_built_at' => $schedule->program_built_at ? jdate($schedule->program_built_at)->format('Y/m/d H:i') : '-',
            'stage_label' => $stage['label'] ?? 'نامشخص',
            'stage_color' => $stage['color'] ?? 'secondary',
        ];
    }

    public function examProgramStage(?TrialWeek $trialWeek): ?array
    {
        $schedule = $this->scheduleFromTrial($trialWeek);

        if (! $schedule) {
            return null;
        }

        if (! $schedule->exam_starts_at || ! $schedule->exam_ends_at) {
            return [
                'label' => 'در انتظار انتخاب بازه امتحانات',
                'color' => 'warning',
            ];
        }

        if ($schedule->days->isEmpty()) {
            return [
                'label' => 'در انتظار اضافه کردن دروس امتحانات',
                'color' => 'info',
            ];
        }

        if (! $schedule->program_built_at || ! $schedule->weekly_program_id) {
            return [
                'label' => 'در انتظار ساخت برنامه',
                'color' => 'primary',
            ];
        }

        return [
            'label' => 'برنامه ساخته شده',
            'color' => 'success',
        ];
    }

    protected function currentExamSchedule(?TrialWeek $trialWeek): ?StudentExamSchedule
    {
        if (! $trialWeek?->student_id) {
            return null;
        }

        return StudentExamSchedule::query()
            ->with([
                'setting',
                'days.subject',
                'weeklyProgram.parts.ccChapter',
                'weeklyProgram.parts.ccTopic',
                'weeklyProgram.parts.ccSubject',
                'weeklyProgram.restDays',
                'weeklyProgram.examDays',
            ])
            ->where('student_id', $trialWeek->student_id)
            ->orderByRaw('program_built_at IS NULL')
            ->latest('program_built_at')
            ->latest('updated_at')
            ->first();
    }

    protected function scheduleFromTrial(?TrialWeek $trialWeek): ?StudentExamSchedule
    {
        $loadedSchedule = $trialWeek?->student?->examSchedules?->sortByDesc(fn ($item) => $item->program_built_at?->timestamp ?? 0)->first();

        if ($loadedSchedule) {
            if (! $loadedSchedule->relationLoaded('days')) {
                $loadedSchedule->load('days');
            }

            return $loadedSchedule;
        }

        return $this->currentExamSchedule($trialWeek);
    }

    protected function weeklyProgramForTrial(?TrialWeek $trialWeek): ?WeeklyProgram
    {
        return $trialWeek?->student_id
            ? WeeklyProgram::with(['parts.ccChapter', 'parts.ccTopic', 'parts.ccSubject', 'restDays', 'examDays'])
                ->where('student_id', $trialWeek->student_id)
                ->where('is_active', true)
                ->latest('start_date')
                ->first()
            : null;
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
        }
    }

    public function resetToToday(): void
    {
        $this->viewDate = null;
        $this->resetPage();
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

    public function sendReportReminder(int $studentId, string $reportDate): void
    {
        $allowedStudentIds = $this->scopedStudentIds()->map(fn ($id) => (int) $id)->toArray();
        if (! in_array($studentId, $allowedStudentIds, true)) {
            $this->dispatch('warning', 'دانش‌آموز برای شما یافت نشد.');
            return;
        }

        $student = Student::with(['user.personalInformation', 'user.profile'])->find($studentId);
        if (! $student) {
            $this->dispatch('warning', 'دانش‌آموز برای شما یافت نشد.');
            return;
        }

        $reportDateLabel = jdate(Carbon::parse($reportDate))->format('Y/m/d');
        $studentName = $this->studentName($student);

        NotificationService::sendToStudent(
            $studentId,
            'یادآوری ارسال گزارش روزانه',
            "{$studentName} عزیز\nلطفا گزارش مطالعه تاریخ {$reportDateLabel} را هرچه زودتر ارسال کن.\nبا تشکر",
            Auth::guard('admin')->id()
        );

        $this->dispatch('success', 'اعلان یادآوری گزارش ارسال شد.');
    }

    public function openCommentModal(int $reportId): void
    {
        $report = $this->scopedReport($reportId);

        $this->detailModalOpen = false;
        $this->dayDetailModalOpen = false;
        $this->commentReportId = $report->id;
        $this->advisorCommentInput = $report->feedback->advisor_comment ?? '';
        $this->commentStatusInput = in_array($report->detail?->status, ['approved', 'rejected'], true)
            ? $report->detail->status
            : '';
        $this->advisorCommentReadonly = ! empty($report->feedback->advisor_comment);
        $this->commentStudentName = $this->studentName($report->student);
        $this->commentStudentReply = $report->feedback->student_reply;
        $this->commentModalOpen = true;
    }

    public function closeCommentModal(): void
    {
        $this->reset(['commentModalOpen', 'commentReportId', 'advisorCommentInput', 'commentStudentName', 'commentStudentReply']);
        $this->commentStatusInput = '';
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
            'advisorCommentInput' => ['required', 'string', 'min:3', 'max:1000'],
            'commentStatusInput' => ['required', 'in:approved,rejected'],
        ], [
            'advisorCommentInput.required' => 'نوشتن نظر مشاور الزامی است.',
            'advisorCommentInput.min' => 'نظر مشاور باید حداقل ۳ کاراکتر باشد.',
            'commentStatusInput.required' => 'وضعیت گزارش را انتخاب کنید.',
            'commentStatusInput.in' => 'وضعیت انتخاب‌شده معتبر نیست.',
        ]);

        $report->feedback()->updateOrCreate(
            ['daily_report_id' => $report->id],
            [
                'advisor_comment' => $validated['advisorCommentInput'],
                'advisor_commented_at' => now(),
            ]
        );

        $report->detail()->updateOrCreate(
            ['daily_report_id' => $report->id],
            ['status' => $validated['commentStatusInput']]
        );

        $this->sendReportStatusNotification($report, $validated['commentStatusInput']);

        NotificationService::sendToStudent(
            $report->student_id,
            'پیام مشاور درباره گزارش روزانه',
            $validated['advisorCommentInput'],
            Auth::guard('admin')->id()
        );

        $this->dispatch('success', 'نظر و وضعیت گزارش با موفقیت ثبت شد.');
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

        $weeklyProgram = $this->weeklyProgramForTrial($selectedTrial);

        if (! $weeklyProgram || $dayIndex < 0 || $dayIndex > $this->maxProgramDayIndex($weeklyProgram)) {
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

    public function openExtraCall(int $trialId): void
    {
        $trial = $this->baseTrialQuery()->find($trialId);
        if (! $trial) {
            $this->dispatch('warning', 'دانش‌آموز برای شما یافت نشد.');
            return;
        }

        $this->resetExtraCallForm();
        $this->extraCallTrialId = $trial->id;
        $this->extraCallPhase = 'ringing';
    }

    public function markExtraCallAnswered(): void
    {
        if (! $this->extraCallTrialId || ! $this->baseTrialQuery()->whereKey($this->extraCallTrialId)->exists()) {
            $this->dispatch('warning', 'دانش‌آموز برای شما یافت نشد.');
            $this->closeExtraCall();
            return;
        }

        $this->extraTalkSeconds = 0;
        $this->extraCallPhase = 'talking';
        $this->resetErrorBag();
    }

    public function endExtraConversation(int $seconds): void
    {
        $this->extraTalkSeconds = max(0, $seconds);
        $this->extraCallPhase = 'form';
    }

    public function closeExtraCall(): void
    {
        $this->resetExtraCallForm();
    }

    protected function resetExtraCallForm(): void
    {
        $this->reset(['extraCallTrialId', 'extraTalkSeconds', 'extraCallNotes']);
        $this->extraCallPhase = 'ringing';
        $this->resetErrorBag();
    }

    public function saveExtraCall(): void
    {
        if (! $this->extraCallTrialId) {
            return;
        }

        $trial = $this->baseTrialQuery()->find($this->extraCallTrialId);
        if (! $trial) {
            $this->dispatch('warning', 'دانش‌آموز برای شما یافت نشد.');
            $this->closeExtraCall();
            return;
        }

        if ($this->extraCallPhase !== 'form') {
            $this->dispatch('warning', 'ابتدا مکالمه را ثبت کنید.');
            return;
        }

        $validated = $this->validate([
            'extraCallNotes' => ['required', 'string', 'max:5000'],
        ], [
            'extraCallNotes.required' => 'توضیحات تماس اضافه را بنویسید.',
        ]);

        $attempt = TrialAcquisitionCall::where('trial_week_id', $trial->id)
            ->where('stage', TrialAcquisitionCall::STAGE_EXTRA)
            ->count() + 1;

        TrialAcquisitionCall::create([
            'trial_week_id' => $trial->id,
            'admin_id' => Auth::guard('admin')->id(),
            'stage' => TrialAcquisitionCall::STAGE_EXTRA,
            'attempt_number' => $attempt,
            'answered' => true,
            'talk_duration_seconds' => (int) ($this->extraTalkSeconds ?? 0),
            'notes' => $validated['extraCallNotes'],
            'called_at' => now(),
        ]);

        $this->dispatch('success', 'تماس اضافه با موفقیت ثبت شد.');
        $this->closeExtraCall();
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

    protected function makeupSessionsForMonitor(?TrialWeek $trial, ?WeeklyProgram $weeklyProgram): array
    {
        if (! $trial?->student_id) {
            return [];
        }

        $query = MakeupSession::query()
            ->where('student_id', $trial->student_id)
            ->whereNotNull('ended_at')
            ->with(['ccChapter.subject', 'ccTopic.chapter.subject']);

        if ($weeklyProgram?->start_date && $weeklyProgram?->end_date) {
            $query->whereBetween('ended_at', [
                Carbon::parse($weeklyProgram->start_date)->startOfDay(),
                Carbon::parse($weeklyProgram->end_date)->addDay()->setHour(self::REPORT_CUTOFF_HOUR)->startOfHour(),
            ]);
        }

        return $query->latest('ended_at')
            ->limit(50)
            ->get()
            ->map(function (MakeupSession $session) {
                $chapter = $session->ccChapter ?? $session->ccTopic?->chapter;
                $durationSeconds = (int) ($session->duration_seconds ?? 0);

                if ($durationSeconds <= 0 && $session->started_at && $session->ended_at) {
                    $durationSeconds = (int) $session->started_at->diffInSeconds($session->ended_at);
                }

                return [
                    'subject_name' => $chapter?->subject?->name ?: 'درس نامشخص',
                    'chapter_name' => $chapter?->name ?: ($session->ccTopic?->name ?: 'فصل نامشخص'),
                    'part_type_label' => $session->part_type_label,
                    'duration_label' => $this->formatSeconds($durationSeconds),
                    'date_label' => $session->ended_at ? jdate($session->ended_at)->format('Y/m/d') : '—',
                    'time_label' => $session->started_at && $session->ended_at
                        ? $session->started_at->format('H:i') . ' تا ' . $session->ended_at->format('H:i')
                        : ($session->ended_at?->format('H:i') ?? '—'),
                    'note' => $session->note,
                ];
            })
            ->values()
            ->toArray();
    }

    protected function sendReportStatusNotification(DailyReport $report, string $status): void
    {
        $studentName = $this->studentName($report->student);
        $reportDate = jdate($report->report_date)->format('Y/m/d');
        $approved = $status === 'approved';

        NotificationService::sendToStudent(
            $report->student_id,
            $approved ? 'تأیید گزارش روزانه' : 'رد گزارش روزانه',
            $approved
                ? "{$studentName} عزیز\nگزارش مطالعه شما در تاریخ {$reportDate} تأیید شد. به همین روند ادامه بده!\nبا تشکر"
                : "{$studentName} عزیز\nگزارش مطالعه شما در تاریخ {$reportDate} رد شد. لطفاً گزارش را بررسی و اصلاح کنید.\nبا تشکر",
            Auth::guard('admin')->id()
        );
    }

    public function studentName(?Student $student): string
    {
        $user = $student?->user;

        return $this->fullNameFromUser($user);
    }

    public function trialStudentFullName(?TrialWeek $trialWeek): string
    {
        return $this->fullNameFromUser($trialWeek?->user);
    }

    public function trialStudentMobile(?TrialWeek $trialWeek): string
    {
        return $trialWeek?->user?->mobile ?: '—';
    }

    public function trialFatherMobile(?TrialWeek $trialWeek): string
    {
        return $trialWeek?->father_mobile
            ?: $trialWeek?->user?->personalInformation?->father_mobile
            ?: $trialWeek?->student?->father_mobile
            ?: '—';
    }

    public function trialMotherMobile(?TrialWeek $trialWeek): string
    {
        return $trialWeek?->mother_mobile
            ?: $trialWeek?->user?->personalInformation?->mother_mobile
            ?: $trialWeek?->student?->mother_mobile
            ?: '—';
    }

    protected function fullNameFromUser($user): string
    {
        $info = $user?->personalInformation;
        $personalName = trim(implode(' ', array_filter([
            $info?->name,
            $info?->name_full,
        ], fn ($value) => filled($value))));

        if ($personalName !== '') {
            return $personalName;
        }

        $profileName = trim((string) ($user?->profile?->full_name ?? ''));

        return $profileName !== '' ? $profileName : ($user?->name ?: 'نامشخص');
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
            ->whereDate('report_date', '>=', $startDate->copy()->subDay())
            ->whereDate('report_date', '<=', $lastDueDate)
            ->pluck('report_date')
            ->map(fn ($date) => Carbon::parse($date)->format('Y-m-d'))
            ->unique()
            ->values();

        $restDayIndices = $weeklyProgram->restDays()->pluck('day_index')->map(fn ($i) => (int) $i)->toArray();
        $dueReportDates = collect();
        if ($lastDueDate->gte($startDate)) {
            $maxIndex = min($this->maxProgramDayIndex($weeklyProgram), $startDate->diffInDays($lastDueDate));
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

    protected function expectedReportRows(?TrialWeek $selectedTrial, ?WeeklyProgram $weeklyProgram): array
    {
        if (! $selectedTrial?->student_id || ! $weeklyProgram) {
            return [];
        }

        $student = $selectedTrial->student ?: Student::with(['user.personalInformation', 'user.profile'])->find($selectedTrial->student_id);
        $studentId = (int) $selectedTrial->student_id;
        $startDate = Carbon::parse($weeklyProgram->start_date)->startOfDay();
        $endDate = $weeklyProgram->end_date
            ? Carbon::parse($weeklyProgram->end_date)->startOfDay()
            : $this->lastProgramDate($weeklyProgram, $startDate);

        if ($endDate->lt($startDate)) {
            return [];
        }

        $reportsByDate = DailyReport::with(['student.user.personalInformation', 'student.user.profile', 'weeklyProgram.parts', 'reportParts.programPart', 'detail', 'feedback'])
            ->where('student_id', $studentId)
            ->where('weekly_program_id', $weeklyProgram->id)
            ->where('is_compensatory', false)
            ->whereDate('report_date', '>=', $startDate)
            ->whereDate('report_date', '<=', $endDate)
            ->latest()
            ->get()
            ->unique(fn (DailyReport $report) => $report->report_date->toDateString())
            ->keyBy(fn (DailyReport $report) => $report->report_date->toDateString());

        $restDayIndices = $weeklyProgram->restDays->pluck('day_index')->map(fn ($index) => (int) $index)->toArray();
        $partsByDay = $weeklyProgram->parts->groupBy('day_of_week');
        $rows = [];
        $now = now();
        $dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dayIndex = (int) $startDate->diffInDays($date);
            $parts = $partsByDay->get($dayIndex, collect());

            if (in_array($dayIndex, $restDayIndices, true) || $parts->isEmpty()) {
                continue;
            }

            $dateKey = $date->toDateString();
            $report = $reportsByDate->get($dateKey);
            $deadline = $date->copy()->addDay()->setTime(self::REPORT_CUTOFF_HOUR, 0, 0);
            $statusKey = $report ? 'sent' : ($now->gt($deadline) ? 'missing' : 'waiting');

            $rows[] = [
                'student_id' => $studentId,
                'student_name' => $this->studentName($student),
                'report_date' => $dateKey,
                'report_date_label' => jdate($date)->format('Y/m/d'),
                'day_name' => $dayNames[jdate($date)->getDayOfWeek()] ?? '-',
                'deadline_label' => jdate($deadline)->format('Y/m/d H:i'),
                'status_key' => $statusKey,
                'status_label' => match ($statusKey) {
                    'sent' => 'ارسال کرده',
                    'missing' => 'عدم ارسال',
                    default => 'در انتظار ارسال',
                },
                'status_color' => match ($statusKey) {
                    'sent' => 'success',
                    'missing' => 'danger',
                    default => 'warning',
                },
                'report' => $report,
                'parts_total' => $parts->count(),
                'parts_done' => $report ? $report->reportParts->where('is_read', true)->count() : 0,
                'tests_total' => (int) $parts->sum('test_count'),
                'rating' => $report ? (float) $report->calculated_rating : 0,
            ];
        }

        $rows = array_reverse($rows);

        if (in_array($this->reportStatus, ['sent', 'missing', 'waiting'], true)) {
            return array_values(array_filter($rows, fn (array $row) => $row['status_key'] === $this->reportStatus));
        }

        return $rows;
    }

    protected function lastProgramDate(WeeklyProgram $weeklyProgram, Carbon $startDate): Carbon
    {
        return $startDate->copy()->addDays($this->maxProgramDayIndex($weeklyProgram));
    }

    protected function maxProgramDayIndex(WeeklyProgram $weeklyProgram): int
    {
        $startDate = Carbon::parse($weeklyProgram->start_date)->startOfDay();
        $endIndex = $weeklyProgram->end_date
            ? (int) $startDate->diffInDays(Carbon::parse($weeklyProgram->end_date)->startOfDay())
            : 7;
        $lastPartIndex = (int) ($weeklyProgram->parts->max('day_of_week') ?? 0);
        $lastRestIndex = (int) ($weeklyProgram->restDays->max('day_index') ?? 0);
        $lastExamIndex = (int) ($weeklyProgram->examDays->max('day_index') ?? 0);

        return max(0, $endIndex, $lastPartIndex, $lastRestIndex, $lastExamIndex);
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

        for ($i = 0; $i <= $this->maxProgramDayIndex($weeklyProgram); $i++) {
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
            ->where('is_compensatory', false)
            ->where(function ($query) use ($reportDate) {
                $query->whereDate('report_date', $reportDate)
                    ->orWhereDate('report_date', $reportDate->copy()->subDay());
            })
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
        $trials = $this->applySearch($this->baseTrialQuery())
            ->latest()
            ->limit(30)
            ->get();

        $selectedTrial = $this->selectedTrialId
            ? $this->baseTrialQuery()->with(['user.personalInformation', 'student.user.personalInformation', 'advisingSession'])->find($this->selectedTrialId)
            : $trials->first();

        if (! $selectedTrial && $trials->isNotEmpty()) {
            $selectedTrial = $trials->first();
            $this->selectedTrialId = $selectedTrial->id;
        } elseif (! $this->selectedTrialId && $selectedTrial) {
            $this->selectedTrialId = $selectedTrial->id;
        }

        $weeklyProgram = $this->weeklyProgramForTrial($selectedTrial);

        $reports = $this->reportsQuery()->paginate(10);
        $studentIds = $this->scopedStudentIds()->map(fn ($id) => (int) $id)->toArray();
        $reportDate = $this->getReportDate();
        $effectiveToday = $this->getEffectiveToday();
        $monitorLocked = ! $this->hasSuccessfulCall($selectedTrial);
        $extraCallTrial = $this->extraCallTrialId
            ? $this->baseTrialQuery()->with('user.personalInformation')->find($this->extraCallTrialId)
            : null;

        return view('livewire.admin.trial-acquisition.monitor', [
            'trials' => $trials,
            'selectedTrial' => $selectedTrial,
            'extraCallTrial' => $extraCallTrial,
            'weeklyProgram' => $weeklyProgram,
            'monitorLocked' => $monitorLocked,
            'examProgramSummary' => $this->examProgramSummary($selectedTrial),
            'monitorSummary' => $this->monitorSummary($selectedTrial, $weeklyProgram),
            'weekDays' => $this->weekDays($weeklyProgram, $selectedTrial?->student_id ? (int) $selectedTrial->student_id : null),
            'expectedReportRows' => $this->expectedReportRows($selectedTrial, $weeklyProgram),
            'makeupSessions' => $this->makeupSessionsForMonitor($selectedTrial, $weeklyProgram),
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
