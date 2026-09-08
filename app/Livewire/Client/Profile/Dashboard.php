<?php

namespace App\Livewire\Client\Profile;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\AdvisingSession;
use App\Models\NotificationRecipient;
use App\Models\WeeklyProgram;
use App\Models\DailyReport;
use App\Models\DailyReportPart;
use App\Models\StudyPartSession;
use App\Models\ClassSchedule;
use App\Models\TrialWeek;
use App\Models\ProgramPart;
use App\Models\SmartReportCard;
use App\Services\ExamPlanningService;
use App\Services\StudentExamDayFeedbackService;
use Carbon\Carbon;

class Dashboard extends Component

{

    public $student;

    public $user;

    public bool $startDashboardTour = false;

    public bool $showTrialWeekPanelNotice = false;
    public ?string $examFeedbackDifficulty = null;
    public ?string $examFeedbackNote = null;

    /** کش برنامه فعال تا در یک درخواست چند بار کوئری نزنیم */
    protected $activeProgramResolved = false;
    protected $activeProgramCache = null;
    protected array $examProgramFlags = [];

    /** نام روزهای هفته (0=شنبه تا 6=جمعه) */
    protected array $weekDayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];


    use SEOTools;


    public function mount()

    {
        $this->user = Auth::user();

        $this->seoConfig();

        $this->student = $this->user?->student ?? null;

        $this->startDashboardTour = (bool) session()->pull('start_dashboard_tour', false);
        $this->showTrialWeekPanelNotice = $this->shouldShowTrialWeekPanelNotice();

    }


    public function seoConfig()

    {

        $this->seo()->setTitle('پیشخوان');

    }

    private function shouldShowTrialWeekPanelNotice(): bool
    {
        if (!$this->user || !$this->student || !$this->student->is_trial) {
            return false;
        }

        if (method_exists($this->user, 'isSchoolStudent') && $this->user->isSchoolStudent()) {
            return false;
        }

        return TrialWeek::where('user_id', $this->user->id)
            ->whereNull('dashboard_notice_acknowledged_at')
            ->exists();
    }

    private function isExamProgram(?WeeklyProgram $program = null): bool
    {
        $program = $program ?: $this->getActiveWeeklyProgram();

        if (! $program) {
            return false;
        }

        $programId = (int) $program->id;
        if (! array_key_exists($programId, $this->examProgramFlags)) {
            $this->examProgramFlags[$programId] = $program->examDays()->exists();
        }

        return $this->examProgramFlags[$programId];
    }

    public function getDashboardPeriodContext(): array
    {
        $program = $this->getActiveWeeklyProgram();
        $isExamProgram = $this->isExamProgram($program);

        return [
            'is_exam_program' => $isExamProgram,
            'short_label' => $isExamProgram ? 'کل امتحانات' : 'این هفته',
            'study_hint' => $isExamProgram
                ? 'مجموع ساعت مطالعه‌ات نسبت به هدف کل بازه امتحانات'
                : 'مجموع ساعت مطالعه‌ات نسبت به هدف این هفته',
            'report_hint' => $isExamProgram
                ? 'روزهایی که در بازه امتحانات گزارش روزانه ثبت کرده‌ای'
                : 'تعداد روزهایی که این هفته گزارش روزانه ثبت کرده‌ای',
            'extra_hint' => $isExamProgram
                ? 'میزان مطالعهٔ اضافه بر سازمان که در بازه امتحانات ثبت کرده‌ای'
                : 'میزان مطالعهٔ اضافه بر سازمان که این هفته ثبت کرده‌ای',
            'daily_chart_title' => $isExamProgram ? 'مطالعه روزانه امتحانات' : 'مطالعه روزانه',
            'daily_chart_hint' => $isExamProgram
                ? 'ساعت برنامه‌ریزی‌شده در برابر ساعت واقعی مطالعه در هر روز بازه امتحانات'
                : 'ساعت برنامه‌ریزی‌شده در برابر ساعت واقعی مطالعه در هر روز هفته',
            'subject_progress_title' => $isExamProgram ? 'پیشرفت دروس امتحانی' : 'پیشرفت دروس این هفته',
        ];
    }

    public function acknowledgeTrialWeekPanelNotice(): void
    {
        if (!$this->user || !$this->student || !$this->student->is_trial) {
            $this->showTrialWeekPanelNotice = false;
            return;
        }

        $trialWeek = TrialWeek::where('user_id', $this->user->id)
            ->whereNull('dashboard_notice_acknowledged_at')
            ->latest()
            ->first();

        $trialWeek?->update(['dashboard_notice_acknowledged_at' => now()]);

        $this->showTrialWeekPanelNotice = false;
    }

    public function submitExamDayFeedback(): void
    {
        $feedbackService = app(StudentExamDayFeedbackService::class);
        $pending = $feedbackService->pendingFeedback($this->user);
        if (! $pending) {
            $this->examFeedbackDifficulty = null;
            $this->examFeedbackNote = null;
            $this->dispatch('success', 'بازخورد امتحان ثبت شده است.');
            return;
        }

        $validated = $this->validate([
            'examFeedbackDifficulty' => ['required', 'in:easy,medium,hard,failed'],
            'examFeedbackNote' => ['nullable', 'string', 'max:2000'],
        ], [
            'examFeedbackDifficulty.required' => 'وضعیت آزمون امروز را انتخاب کنید.',
            'examFeedbackDifficulty.in' => 'وضعیت آزمون امروز معتبر نیست.',
            'examFeedbackNote.max' => 'یادداشت نمی‌تواند بیشتر از ۲۰۰۰ کاراکتر باشد.',
        ]);

        $feedbackService->submit(
            $this->user,
            (int) $pending['schedule_id'],
            $pending['exam_date'],
            $validated['examFeedbackDifficulty'],
            $validated['examFeedbackNote'] ?? null
        );

        $this->examFeedbackDifficulty = null;
        $this->examFeedbackNote = null;
        $this->dispatch('success', 'خسته نباشی! بازخورد امتحان امروز ثبت شد.');
    }


    /**
     * تعداد پیام‌های خوانده نشده
     */

    public function getUnreadNotificationsCount(): int

    {

        return NotificationRecipient::where('user_id', $this->user->id)
            ->where('is_read', false)
            ->count();

    }

    /**
     * دریافت جلسه مشاوره فعال (آخرین جلسه برگزار شده)
     */
    public function getActiveAdvisingSession()
    {
        if (!$this->student) {
            return null;
        }
        return AdvisingSession::where('student_id', $this->student->id)
            ->where('result_status', 'held')
            ->orderBy('activation_date', 'desc')
            ->orderBy('id', 'desc') // تساوی تاریخ: جدیدترین جلسهٔ برگزارشده انتخاب شود
            ->first();
    }
    /**
     * دریافت برنامه فعال هفتگی (مرتبط با آخرین جلسه مشاوره برگزار شده)
     */
    public function getActiveWeeklyProgram()
    {
        if ($this->activeProgramResolved) {
            return $this->activeProgramCache;
        }
        $this->activeProgramResolved = true;

        if (!$this->student) {
            return $this->activeProgramCache = null;
        }

        // برای دانش‌آموز آزمایشی: برنامه‌ای که در trial ساخته شده
        if ($this->student->is_trial) {
            $trial = TrialWeek::where('user_id', $this->user->id)->latest()->first();
            if ($trial && $trial->advising_session_id) {
                $prog = WeeklyProgram::where('advising_session_id', $trial->advising_session_id)
                    ->latest()->first();
                if ($prog) return $this->activeProgramCache = $prog;
            }
            return $this->activeProgramCache = WeeklyProgram::where('student_id', $this->student->id)->latest()->first();
        }

        $activeSession = $this->getActiveAdvisingSession();
        if (!$activeSession) {
            return $this->activeProgramCache = WeeklyProgram::where('student_id', $this->student->id)->latest()->first();
        }

        // اگر برنامه برای همین جلسه بازسازی شده باشد، جدیدترین نسخه نمایش داده شود (نه قدیمی‌ترین)
        return $this->activeProgramCache = WeeklyProgram::where('advising_session_id', $activeSession->id)
            ->latest('id')->first();
    }

    // ====================================================================
    // ====================== Shared aggregation helper ===================
    // ====================================================================

    /**
     * نگاشت ثانیه‌های مطالعه و امتیازهای کیفیت برای مجموعه‌ای از پارت‌ها.
     * منطق دقیقاً مطابق کارنامه هوشمند است.
     *
     * @return array{seconds: array<int,int>, ratings: array<int,array<int>>}
     */
    private function studySecondsMap(array $partIds, Carbon $start, Carbon $end): array
    {
        if (empty($partIds) || !$this->student) {
            return ['seconds' => [], 'ratings' => []];
        }

        $rows = StudyPartSession::query()
            ->where('student_id', $this->student->id)
            ->whereIn('program_part_id', $partIds)
            ->whereBetween('started_at', [$start, $end])
            ->with('feedback:id,sps_id,rating')
            ->get(['id', 'program_part_id', 'started_at', 'ended_at', 'duration_seconds']);

        $seconds = [];
        $ratings = [];
        foreach ($rows as $row) {
            $sec = (int) ($row->duration_seconds ?? 0);
            if ($sec <= 0 && $row->started_at && $row->ended_at) {
                $sec = $row->started_at->diffInSeconds($row->ended_at);
            }
            if ($sec > 0) {
                $seconds[$row->program_part_id] = ($seconds[$row->program_part_id] ?? 0) + $sec;
            }
            if ($row->feedback && $row->feedback->rating !== null) {
                $ratings[$row->program_part_id][] = (int) $row->feedback->rating;
            }
        }

        return ['seconds' => $seconds, 'ratings' => $ratings];
    }

    private function partTypeLabel($source, $partType): string
    {
        $source = $source ?: 'normal';
        if ($source !== 'normal') {
            return match ($source) {
                'class_qa' => 'پرسش و پاسخ کلاسی',
                'exam' => 'امتحانات',
                'homework' => 'تکالیف',
                'daily_reading' => 'روزخوانی',
                'pre_reading' => 'پیش‌خوانی',
                'classification' => 'طبقه‌بندی',
                'comprehensive_exam' => 'آزمون جامع',
                default => $this->basePartType($partType),
            };
        }
        return $this->basePartType($partType);
    }

    private function basePartType($partType): string
    {
        return match ($partType) {
            'test' => 'تستی',
            'descriptive' => 'تشریحی',
            'video' => 'ویدئو',
            'topic_exam' => 'آزمون مبحثی',
            'comprehensive_exam' => 'آزمون جامع',
            'exam_analysis' => 'تحلیل آزمون',
            default => 'سایر',
        };
    }

    private function qualityTier(array $ratings): ?string
    {
        if (empty($ratings)) return null;
        $avg = array_sum($ratings) / count($ratings);
        if ($avg >= 8) return 'عالی';
        if ($avg >= 5) return 'با کیفیت';
        return 'بی‌کیفیت';
    }

    public function formatHoursAndMinutes(float $hours): string
    {
        if ($hours < 0) {
            $hours = 0;
        }
        $totalMinutes = floor($hours * 60);
        $h = floor($totalMinutes / 60);
        $m = $totalMinutes % 60;

        if ($h > 0 && $m > 0) {
            return sprintf('%d ساعت و %d دقیقه', $h, $m);
        }
        if ($h > 0) {
            return sprintf('%d ساعت', $h);
        }
        return sprintf('%d دقیقه', $m);
    }

    // ====================================================================
    // ====================== Weekly insights =============================
    // ====================================================================

    /**
     * تمام داده‌های تحلیلی هفتهٔ جاری: آمار کلی، شکست روزانه،
     * پیشرفت دروس، توزیع نوع پارت و کیفیت مطالعه.
     */
    public function getWeeklyInsights(): array
    {
        $empty = [
            'has_data' => false,
            'parts_total' => 0,
            'parts_studied' => 0,
            'tests_planned' => 0,
            'tests_done' => 0,
            'study_seconds' => 0,
            'planned_minutes' => 0,
            'completion_percent' => 0,
            'quality' => ['عالی' => 0, 'با کیفیت' => 0, 'بی‌کیفیت' => 0],
            'part_type_distribution' => [],
            'daily' => [],
            'subjects' => [],
        ];

        $program = $this->getActiveWeeklyProgram();
        if (!$program || !$this->student) {
            return $empty;
        }

        $isExamProgram = $this->isExamProgram($program);
        $start = Carbon::parse($program->start_date)->startOfDay();
        $end = Carbon::parse($program->end_date)->endOfDay();
        $dayCount = (int) $start->diffInDays(Carbon::parse($program->end_date)->startOfDay()) + 1;

        if ($this->student?->is_trial && ! $isExamProgram) {
            $dayCount = min($dayCount, TrialWeek::PROGRAM_DAYS);
        }

        $parts = $program->parts()->with(['ccSubject'])->get();
        if ($parts->isEmpty()) {
            return $empty;
        }

        $partIds = $parts->pluck('id')->all();
        $map = $this->studySecondsMap($partIds, $start, $end);
        $seconds = $map['seconds'];
        $ratings = $map['ratings'];

        $restIdx = $program->restDays->pluck('day_index')->map(fn($v) => (int) $v)->all();

        // ---- شکست روزانه (Dynamic Days) ----
        $daily = [];
        for ($i = 0; $i < $dayCount; $i++) {
            $date = $start->copy()->addDays($i);
            $dayOfWeek = jdate($date)->getDayOfWeek(); // Get Jalali day of week
            $dayParts = $parts->where('day_of_week', $i);
            $plannedMin = (int) $dayParts->sum('duration_minutes');
            $studiedSec = 0;
            foreach ($dayParts as $p) {
                $studiedSec += $seconds[$p->id] ?? 0;
            }
            $isRest = in_array($i, $restIdx, true) || ($plannedMin === 0 && $dayParts->isEmpty());
            $daily[] = [
                'label' => $this->weekDayNames[$dayOfWeek],
                'chart_label' => $isExamProgram
                    ? $this->weekDayNames[$dayOfWeek] . ' ' . jdate($date)->format('m/d')
                    : $this->weekDayNames[$dayOfWeek],
                'date' => jdate($date)->format('j'),
                'planned_hours' => round($plannedMin / 60, 2),
                'studied_hours' => round($studiedSec / 3600, 2),
                'is_rest' => $isRest,
            ];
        }

        // ---- توزیع نوع پارت + کیفیت ----
        $partTypeDist = [];
        foreach ($parts as $p) {
            $label = $this->partTypeLabel($p->source_type, $p->part_type);
            $partTypeDist[$label] = ($partTypeDist[$label] ?? 0) + 1;
        }

        $quality = ['عالی' => 0, 'با کیفیت' => 0, 'بی‌کیفیت' => 0];
        foreach ($ratings as $r) {
            $tier = $this->qualityTier($r);
            if ($tier) $quality[$tier]++;
        }

        // ---- پیشرفت دروس ----
        $bySubject = [];
        foreach ($parts as $p) {
            $key = $p->cc_subject_id ?: ('lesson_' . ($p->lesson_name ?? '—'));
            if (!isset($bySubject[$key])) {
                $bySubject[$key] = [
                    'name' => $p->ccSubject->name ?? ($p->lesson_name ?? 'درس'),
                    'planned_minutes' => 0,
                    'studied_seconds' => 0,
                    'parts_total' => 0,
                    'parts_studied' => 0,
                ];
            }
            $bySubject[$key]['planned_minutes'] += (int) ($p->duration_minutes ?? 0);
            $sec = $seconds[$p->id] ?? 0;
            $bySubject[$key]['studied_seconds'] += $sec;
            $bySubject[$key]['parts_total']++;
            if ($sec > 0) $bySubject[$key]['parts_studied']++;
        }

        $subjects = [];
        foreach ($bySubject as $s) {
            $studiedMin = (int) floor($s['studied_seconds'] / 60);
            $subjects[] = [
                'name' => $s['name'],
                'planned_minutes' => $s['planned_minutes'],
                'studied_minutes' => $studiedMin,
                'parts_total' => $s['parts_total'],
                'parts_studied' => $s['parts_studied'],
                'percent' => $s['planned_minutes'] > 0
                    ? round(min(100, ($studiedMin / $s['planned_minutes']) * 100), 1)
                    : 0,
            ];
        }
        usort($subjects, fn($a, $b) => $b['percent'] <=> $a['percent']);

        // ---- آمار کلی ----
        $studySeconds = array_sum($seconds);
        $partsStudied = count(array_filter($seconds, fn($s) => $s > 0));
        $plannedMinutes = (int) $parts->sum('duration_minutes');
        $testsPlanned = (int) $parts->sum('test_count');

        $reportIds = DailyReport::where('student_id', $this->student->id)
            ->where('weekly_program_id', $program->id)
            ->whereBetween('report_date', [$start, $end])
            ->pluck('id')->all();
        $testsDone = !empty($reportIds)
            ? (int) DailyReportPart::whereIn('daily_report_id', $reportIds)->sum('tests_done')
            : 0;

        return [
            'has_data' => true,
            'parts_total' => $parts->count(),
            'parts_studied' => $partsStudied,
            'tests_planned' => $testsPlanned,
            'tests_done' => $testsDone,
            'study_seconds' => $studySeconds,
            'planned_minutes' => $plannedMinutes,
            'completion_percent' => $parts->count() > 0
                ? round(($partsStudied / $parts->count()) * 100)
                : 0,
            'quality' => $quality,
            'part_type_distribution' => $partTypeDist,
            'daily' => $daily,
            'subjects' => $subjects,
            'is_exam_program' => $isExamProgram,
        ];
    }

    // ====================================================================
    // ====================== Monthly insights ============================
    // ====================================================================

    /**
     * پیشرفت ماه جاری (تقویم شمسی): مطالعه به تفکیک هفته،
     * مجموع ساعت مطالعه، پارت‌های انجام‌شده و گزارش‌های ارسالی.
     */
    public function getMonthlyInsights(): array
    {
        $empty = [
            'has_data' => false,
            'month_label' => '',
            'weeks' => [],
            'total_study_hours' => '0 دقیقه',
            'total_planned_hours' => '0 دقیقه',
            'total_parts_studied' => 0,
            'reports_sent' => 0,
            'completion_percent' => 0,
        ];

        if (!$this->student) {
            return $empty;
        }

        $jNow = jdate(Carbon::now());
        $curYear = (int) $jNow->format('Y');
        $curMonth = (int) $jNow->format('m');
        $range = SmartReportCard::jalaliMonthRange($curYear, $curMonth);
        $mStart = $range['start'];
        $mEnd = $range['end'];

        $programs = WeeklyProgram::query()
            ->where('student_id', $this->student->id)
            ->where('start_date', '<=', $mEnd->toDateString())
            ->where('end_date', '>=', $mStart->toDateString())
            ->orderBy('start_date')
            ->with('parts:id,weekly_program_id,duration_minutes')
            ->get();

        $weeks = [];
        $totalDoneSec = 0;
        $totalPlannedMin = 0;
        $totalPartsStudied = 0;
        $idx = 1;

        foreach ($programs as $prog) {
            $plannedMin = (int) $prog->parts->sum('duration_minutes');
            $pids = $prog->parts->pluck('id')->all();
            $map = $this->studySecondsMap($pids, $mStart, $mEnd);
            $doneSec = array_sum($map['seconds']);
            $studiedParts = count(array_filter($map['seconds'], fn($s) => $s > 0));

            $weeks[] = [
                'label' => 'هفته ' . $idx,
                'date' => jdate($prog->start_date)->format('m/d'),
                'planned_hours' => round($plannedMin / 60, 2),
                'done_hours' => round($doneSec / 3600, 2),
            ];

            $totalPlannedMin += $plannedMin;
            $totalDoneSec += $doneSec;
            $totalPartsStudied += $studiedParts;
            $idx++;
        }

        $reportsSent = DailyReport::where('student_id', $this->student->id)
            ->where('is_compensatory', false)
            ->whereBetween('report_date', [$mStart, $mEnd])
            ->count();

        $doneMin = $totalDoneSec / 60;

        return [
            'has_data' => !empty($weeks),
            'month_label' => (SmartReportCard::MONTH_NAMES[$curMonth] ?? '') . ' ' . $curYear,
            'weeks' => $weeks,
            'total_study_hours' => $this->formatHoursAndMinutes($totalDoneSec / 3600),
            'total_planned_hours' => $this->formatHoursAndMinutes($totalPlannedMin / 60),
            'total_parts_studied' => $totalPartsStudied,
            'reports_sent' => $reportsSent,
            'completion_percent' => $totalPlannedMin > 0
                ? round(min(100, ($doneMin / $totalPlannedMin) * 100))
                : 0,
        ];
    }

    /**
     * مشاور یا پشتیبان نمایش‌داده‌شده.
     * در حالت آزمایشی، نام/تصویر «پشتیبان جذب» جایگزین مشاور می‌شود.
     */
    public function getDisplayAdvisor()
    {
        if ($this->student && !$this->student->is_trial && $this->student->advisor) {
            return [
                'name'    => $this->student->advisor->name,
                'picture' => $this->student->advisor->picture,
                'id'      => $this->student->advisor->id,
                'label'   => 'مشاور شما',
            ];
        }

        $trial = $this->student?->is_trial
            ? TrialWeek::where('user_id', $this->user->id)
                ->whereNotNull('acquisition_supporter_id')
                ->latest()
                ->first()
            : null;
        if ($trial && $trial->acquisitionSupporter) {
            $sup = $trial->acquisitionSupporter;
            return [
                'name'    => $sup->name,
                'picture' => $sup->picture,
                'id'      => $sup->id,
                'label'   => 'پشتیبان شما',
            ];
        }

        return null;
    }

    /**
     * آیا دانش‌آموز فارغ‌التحصیل است؟ (برای فارغ‌التحصیل‌ها باکس برنامه کلاسی نمایش داده نمی‌شود)
     */
    public function isGraduateStudent(): bool
    {
        // دانش‌آموز آزمایشی: از روی TrialWeek تشخیص بده
        if ($this->student && $this->student->is_trial) {
            $trial = TrialWeek::where('user_id', $this->user->id)->latest()->first();
            if ($trial) {
                return $trial->isGraduate();
            }
        }

        // سایر دانش‌آموزان: از اطلاعات فردی
        $info = $this->user?->personalInformation;
        return (bool) ($info?->is_graduate ?? false);
    }

    /**
     * پارت‌های امتحان، پرسش و پاسخ کلاسی و تکلیف هفته جاری (هفته جلسه مشاور)
     */
    public function getWeeklySpecialParts(): array
    {
        $program = $this->getActiveWeeklyProgram();
        if (!$program || !$this->student) {
            return [];
        }

        $parts = $program->parts()
            ->whereIn('source_type', ['exam', 'class_qa', 'homework'])
            ->with(['lesson', 'ccSubject'])
            ->orderBy('day_of_week')
            ->orderBy('part_order')
            ->get();

        if ($this->isExamProgram($program)) {
            $examParts = $parts->where('source_type', ProgramPart::SOURCE_EXAM);
            $summary = [];

            if ($examParts->isNotEmpty()) {
                $summary[] = [
                    'source_type' => ProgramPart::SOURCE_EXAM,
                    'is_summary' => true,
                    'lesson_name' => 'برنامه امتحانات',
                    'parts_count' => $examParts->count(),
                    'duration_minutes' => (int) $examParts->sum('duration_minutes'),
                    'period_label' => 'کل بازه امتحانات',
                ];
            }

            return array_merge(
                $summary,
                $parts->where('source_type', '!=', ProgramPart::SOURCE_EXAM)->values()->toArray()
            );
        }

        return $parts->toArray();
    }

    public function getExamPlanningCard(): ?array
    {
        if (! $this->user || ! $this->student) {
            return null;
        }

        $access = app(ExamPlanningService::class)->resolveExamAccess($this->user);
        if (($access['mode'] ?? null) !== ExamPlanningService::ACCESS_PAID) {
            return null;
        }

        /** @var \App\Models\ExamPlanningSetting|null $setting */
        $setting = $access['setting'] ?? null;
        if (! $setting) {
            return null;
        }

        $schedule = app(ExamPlanningService::class)->prepareScheduleForUser($this->user);
        $hasManagerCalendar = $schedule?->source_type === \App\Models\StudentExamSchedule::SOURCE_MANAGER;
        $builtProgramId = $schedule?->weekly_program_id
            ? WeeklyProgram::query()->whereKey($schedule->weekly_program_id)->value('id')
            : null;

        return [
            'title' => 'برنامه امتحانات',
            'description' => $builtProgramId
                ? 'برنامه امتحانی تو ساخته شده است و می‌توانی مستقیم همان برنامه را ببینی و مطالعه‌ات را ثبت کنی.'
                : ($hasManagerCalendar
                    ? 'تقویم امتحانی پایه و رشته تو آماده است. مستقیم برو ساعت‌دهی درس‌ها و برنامه‌ات را بساز.'
                    : 'اگر بازه امتحاناتت شروع شده، تقویم امتحان و ساعت مطالعه‌ات را ثبت کن تا برنامه مخصوص امتحاناتت ساخته شود.'),
            'cta' => $builtProgramId ? 'مشاهده برنامه' : 'ساخت برنامه',
            'route' => $builtProgramId
                ? route('client.profile.consultation.weekly-program', $builtProgramId)
                : route('client.profile.exam-planning'),
            'badge' => $setting->term_type_label,
        ];
    }

    /**
     * دریافت برنامه امروز
     */
    public function getTodayProgram()
    {
        $activeProgram = $this->getActiveWeeklyProgram();

        if (!$activeProgram) {
            return [];
        }

        $today = Carbon::today();

        $startDate = Carbon::parse($activeProgram->start_date);
        $endDate = Carbon::parse($activeProgram->end_date ?? $startDate->copy()->addDays(7));

        // اگر امروز در بازه برنامه نیست
        if ($today->lt($startDate) || $today->gt($endDate)) {

            return [];
        }
        // محاسبه اینکه امروز چندمین روز برنامه است
        $dayIndex = $startDate->diffInDays($today);
        return $activeProgram->parts()
            ->where('day_of_week', $dayIndex)
            ->with(['lesson', 'ccSubject', 'ccChapter'])

            ->orderBy('part_order')
            ->get();
    }

    /**
     * تحلیل متنیِ زندهٔ برنامهٔ امروز (۲ تا ۳ خط).
     * بر اساس حجم تستی/تشریحی، عمومی/اختصاصی، آمادگیِ امتحان، تکالیف و دستهٔ ABCD
     * یک جمع‌بندیِ کوتاه و راهبردی از روزِ پیشِ‌رو می‌سازد.
     */
    public function getTodayAnalysis($parts = null): ?array
    {
        $parts = $parts ?? $this->getTodayProgram();

        if (!$parts || count($parts) === 0) {
            return null;
        }

        $min = fn ($p) => (int) ($p->duration_minutes ?? round(($p->duration_hours ?? 0) * 60));

        $totalMin   = 0;
        $testMin    = 0;
        $descMin    = 0;
        $generalMin = 0;
        $specMin    = 0;
        $examMin    = 0;
        $qaMin      = 0;
        $hwMin      = 0;
        $readingMin = 0;
        $studyMin   = 0;
        $testCount  = 0;
        $lessons    = [];

        foreach ($parts as $p) {
            $m = $min($p);
            $totalMin += $m;

            if (($p->part_type ?? null) === 'test') {
                $testMin   += $m;
                $testCount += (int) ($p->test_count ?? 0);
            } else {
                $descMin += $m;
            }

            if (($p->lesson_type ?? null) === 'general') {
                $generalMin += $m;
            } else {
                $specMin += $m;
            }

            switch ($p->source_type ?? null) {
                case 'exam':
                case 'comprehensive_exam':
                    $examMin += $m;
                    break;
                case 'class_qa':
                    $qaMin += $m;
                    break;
                case 'homework':
                    $hwMin += $m;
                    break;
                case 'daily_reading':
                case 'pre_reading':
                    $readingMin += $m;
                    break;
                case 'classification':
                    $studyMin += $m;
                    break;
            }

            $name = $p->lesson_name ?? ($p->lesson->name ?? null);
            if ($name) {
                $lessons[$name] = true;
            }
        }

        if ($totalMin <= 0) {
            return null;
        }

        $fmt = function (int $m): string {
            $h = intdiv($m, 60);
            $r = $m % 60;
            if ($h > 0 && $r > 0) return "{$h} ساعت و {$r} دقیقه";
            if ($h > 0)          return "{$h} ساعت";
            return "{$r} دقیقه";
        };
        $share = fn (int $m): float => $totalMin > 0 ? $m / $totalMin : 0;

        $lessonNames = array_slice(array_keys($lessons), 0, 3);
        $examFocused = ($examMin + $qaMin) > 0;

        $line1 = 'برنامهٔ امروز ' . $fmt($totalMin) . ' مطالعه در ' . count($parts) . ' بخش'
            . (count($lessonNames) ? ' روی ' . implode('، ', $lessonNames) : '') . ' است.';

        $bits = [];
        if ($testMin > 0) {
            $bits[] = 'تستی ' . round($share($testMin) * 100) . '٪'
                . ($testCount > 0 ? " (حدود {$testCount} تست)" : '');
        }
        if ($descMin > 0) {
            $bits[] = 'تشریحی/مطالعه ' . round($share($descMin) * 100) . '٪';
        }
        $axis  = $specMin >= $generalMin ? 'بیشتر روی دروس اختصاصی' : 'بیشتر روی دروس عمومی';
        $line2 = 'ترکیب امروز: ' . implode(' و ', $bits) . ($bits ? '، ' : '') . $axis . ' متمرکز است.';

        if ($examFocused && $share($examMin + $qaMin) >= 0.35) {
            $verdict = 'امروز روزِ آمادگیِ امتحان است؛ تمرکز و انرژیِ بیشتری می‌طلبد.';
        } elseif ($share($testMin) >= 0.4) {
            $verdict = 'امروز روزِ تست‌زنی و پویاست؛ بیشترِ وقت صرف تثبیتِ مباحثِ مسلط (دستهٔ A) می‌شود.';
        } elseif ($share($readingMin + $studyMin) >= 0.5 && $testMin === 0) {
            $verdict = 'امروز روزی آرام و مطالعه‌محور است؛ بیشتر برای یادگیریِ مباحثِ تازه و فصل‌های دستهٔ C/D.';
        } else {
            $verdict = 'امروز روزی متعادل میان مطالعه و تست‌زنی است؛ با تمرکز پیش برو.';
        }
        if ($hwMin > 0) {
            $verdict .= ' انجامِ تکالیف هم در برنامهٔ امروز گنجانده شده.';
        }

        return [
            'lines'  => [$line1, $line2, $verdict],
            'totals' => [
                'total_min' => $totalMin,
                'test_min'  => $testMin,
                'desc_min'  => $descMin,
                'exam_min'  => $examMin + $qaMin,
                'hw_min'    => $hwMin,
            ],
        ];
    }

    /**
     * محاسبه پیشرفت ساعت مطالعه
     */
    public function getStudyHoursProgress()
    {
        $activeProgram = $this->getActiveWeeklyProgram();

        if (!$activeProgram || !$this->student) {
            return [
                'total_hours' => '0 دقیقه',
                'completed_hours' => '0 دقیقه',
                'percentage' => 0,
            ];
        }

        // فقط پارت‌های رسمیِ خودِ برنامه (نه پارت‌های دستیِ دانش‌آموز/جبرانی)
        // هم در مخرج (کل ساعت) و هم در صورت (ساعت مطالعه‌شده) باید یکسان لحاظ شوند
        $officialParts = $activeProgram->parts()->where('is_student_added', false)->get(['id', 'duration_minutes']);
        $totalMinutes = (int) $officialParts->sum('duration_minutes');
        $totalHours = $totalMinutes / 60;

        $startDate = Carbon::parse($activeProgram->start_date)->startOfDay();
        $endDate = Carbon::parse($activeProgram->end_date)->endOfDay();

        // از همان منطق دقیقِ محاسبه‌ی ثانیه‌های مطالعه‌ی واقعی (duration_seconds، بدون احتساب زمان اضافه/جبرانی)
        // استفاده می‌کنیم که در بقیه‌ی گزارش‌ها (کارنامه هوشمند، تحلیل هفتگی) هم به کار می‌رود
        $partIds = $officialParts->pluck('id')->all();
        $map = $this->studySecondsMap($partIds, $startDate, $endDate);
        $completedSeconds = array_sum($map['seconds']);
        $completedHours = $completedSeconds / 3600;

        $percentage = $totalHours > 0 ? min(($completedHours / $totalHours) * 100, 100) : 0;
        $completedCapped = min($completedHours, $totalHours);

        return [
            'total_hours' => $this->formatHoursAndMinutes($totalHours),
            'completed_hours' => $this->formatHoursAndMinutes($completedCapped),
            'percentage' => round($percentage, 1),
        ];
    }

    /**
     * محاسبه «اضافه بر سازمان» این هفته (مجموع زمان اضافه ثبت‌شده روی پارت‌ها)
     */
    public function getExtraOrgProgress(): array
    {
        $activeProgram = $this->getActiveWeeklyProgram();

        if (!$activeProgram || !$this->student) {
            return ['has_extra' => false, 'total_seconds' => 0, 'hours' => '0 دقیقه'];
        }

        $startDate = Carbon::parse($activeProgram->start_date)->startOfDay();
        $endDate = Carbon::parse($activeProgram->end_date)->endOfDay();

        $extraSeconds = (int) StudyPartSession::where('student_id', $this->student->id)
            ->where('weekly_program_id', $activeProgram->id)
            ->whereBetween('started_at', [$startDate, $endDate])
            ->sum('extra_seconds');

        return [
            'has_extra' => $extraSeconds > 0,
            'total_seconds' => $extraSeconds,
            'hours' => $this->formatHoursAndMinutes($extraSeconds / 3600),
        ];
    }

    /**
     * محاسبه پیشرفت گزارش‌ها
     */
    public function getReportProgress()
    {
        $activeProgram = $this->getActiveWeeklyProgram();

        if (!$activeProgram) {
            return [
                'has_program' => false,
                'total_days' => 0,
                'submitted_days' => 0,
                'percentage' => 0,
                'start_date' => null,
            ];
        }

        $startDate = Carbon::parse($activeProgram->start_date)->startOfDay();
        $endDate = Carbon::parse($activeProgram->end_date)->endOfDay();
        
        $totalDays = (int) $startDate->diffInDays(Carbon::parse($activeProgram->end_date)->startOfDay()) + 1;

        $hasExamFlow = $this->isExamProgram($activeProgram);

        if ($this->student && $this->student->is_trial && ! $hasExamFlow) {
            $totalDays = min($totalDays, TrialWeek::PROGRAM_DAYS);
        }

        // در برنامه‌های عادیِ مشاوره‌ای هنوز نمایش فشرده‌ی ۷روزه حفظ می‌شود،
        // اما برنامه‌های امتحانی باید کل بازه را نشان بدهند.
        if (!($this->student && $this->student->is_trial) && ! $hasExamFlow) {
            $totalDays = min($totalDays, 7);
        }

        $visibleEndDate = $startDate->copy()->addDays($totalDays - 1)->endOfDay();
        $submittedDates = DailyReport::where('student_id', $this->student->id)
            ->where('weekly_program_id', $activeProgram->id)
            ->where('is_compensatory', false)
            ->whereBetween('report_date', [$startDate, $visibleEndDate])
            ->pluck('report_date')
            ->map(fn($date) => Carbon::parse($date)->toDateString())
            ->unique()
            ->values()
            ->all();

        // Count non-rest days for percentage calculation
        $restDayIndices = $activeProgram->restDays()
            ->pluck('day_index')
            ->map(fn($value) => (int) $value)
            ->filter(fn($value) => $value >= 0 && $value < $totalDays)
            ->values()
            ->all();

        $submittedDays = count($submittedDates);
        $programmableDays = $totalDays - count($restDayIndices);

        $percentage = $programmableDays > 0 ? ($submittedDays / $programmableDays) * 100 : 0;
        $today = Carbon::today();
        $days = [];

        for ($i = 0; $i < $totalDays; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dateString = $date->toDateString();
            $isRestDay = in_array($i, $restDayIndices, true);
            $isSubmitted = in_array($dateString, $submittedDates, true);
            $isToday = $date->isSameDay($today);
            $isPast = $date->lt($today);

            if ($isRestDay) {
                $statusClass = 'bg-green-600/80 border-green-500 text-white';
            } elseif ($isSubmitted) {
                $statusClass = 'bg-sky-500 border-sky-400 text-white';
            } elseif ($isToday && ! $isSubmitted) {
                $statusClass = 'bg-red-700/80 border-red-600 text-white';
            } elseif ($isPast && ! $isSubmitted) {
                $statusClass = 'bg-red-950/60 border-red-800 text-red-300/80';
            } else {
                $statusClass = 'bg-white/5 border-white/10 text-neutral-500';
            }

            $days[] = [
                'date' => $dateString,
                'jalali_day' => jdate($date)->format('j'),
                'day_name' => $this->weekDayNames[jdate($date)->getDayOfWeek()] ?? '',
                'is_rest' => $isRestDay,
                'is_submitted' => $isSubmitted,
                'is_today' => $isToday,
                'is_past' => $isPast,
                'status_class' => $statusClass,
            ];
        }

        return [
            'has_program' => true,
            'total_days' => $totalDays,
            'submitted_days' => $submittedDays,
            'percentage' => round(min($percentage, 100), 1),
            'start_date' => $startDate->toDateString(),
            'days' => $days,
            'days_per_page' => 8,
            'show_day_pagination' => $hasExamFlow && $totalDays > 8,
            'is_exam_program' => $hasExamFlow,
        ];
    }



    public function render()

    {

        $advisorStudent = $this->getDisplayAdvisor();

        $unreadNotificationsCount = $this->getUnreadNotificationsCount();
        $todayProgram = $this->getTodayProgram();
        $todayAnalysis = $this->getTodayAnalysis($todayProgram);

        $studyHoursProgress = $this->getStudyHoursProgress();

        $reportProgress = $this->getReportProgress();

        $extraOrgProgress = $this->getExtraOrgProgress();

        $weeklyInsights = $this->getWeeklyInsights();
        $monthlyInsights = $this->getMonthlyInsights();
        $weeklySpecialParts = $this->getWeeklySpecialParts();
        $dashboardPeriod = $this->getDashboardPeriodContext();
        $isGraduateStudent = $this->isGraduateStudent();
        $examPlanningCard = $this->getExamPlanningCard();
        $examFeedbackService = app(StudentExamDayFeedbackService::class);
        $examCountdownCard = $examFeedbackService->nextExamCountdown($this->user);
        $pendingExamFeedback = $examFeedbackService->pendingFeedback($this->user);
// برنامه کلاسی
        $classSchedule = null;
        if ($this->student) {
            $classSchedule = ClassSchedule::where('student_id', $this->student->id)
                ->where('is_finalized', true)
                ->with('parts')
                ->latest()
                ->first();
        }
        // اطلاعات مدرسه برای دانش‌آموزان مدرسه‌ای
        $schoolInfo = null;
        if ($this->user && method_exists($this->user, 'isSchoolStudent') && $this->user->isSchoolStudent()) {
            $school = $this->student?->school()->with(['manager', 'schoolManagerAdmin'])->first();
            if ($school) {
                $schoolInfo = [
                    'name'    => $school->name,
                    'manager' => $school->manager?->name ?? $school->schoolManagerAdmin?->name,
                    'phone'   => $school->public_phone,
                    'image'   => $school->image
                        ? asset('schools/' . $school->id . '/' . $school->image)
                        : null,
                ];
            }
        }

        return view('livewire.client.profile.dashboard', [
            'advisorStudent' => $advisorStudent,
            'unreadNotificationsCount' => $unreadNotificationsCount,
            'todayProgram' => $todayProgram,
            'todayAnalysis' => $todayAnalysis,
            'studyHoursProgress' => $studyHoursProgress,
            'reportProgress' => $reportProgress,
            'extraOrgProgress' => $extraOrgProgress,
            'weeklyInsights' => $weeklyInsights,
            'monthlyInsights' => $monthlyInsights,
            'dashboardPeriod' => $dashboardPeriod,
            'classSchedule' => $classSchedule,
            'schoolInfo' => $schoolInfo,
            'weeklySpecialParts' => $weeklySpecialParts,
            'isGraduateStudent' => $isGraduateStudent,
            'examPlanningCard' => $examPlanningCard,
            'examCountdownCard' => $examCountdownCard,
            'pendingExamFeedback' => $pendingExamFeedback,
        ])->layout('layouts.client.app');

    }

}
