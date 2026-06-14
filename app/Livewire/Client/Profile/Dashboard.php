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
use Carbon\Carbon;

class Dashboard extends Component

{

    public $student;

    public $user;

    /** کش برنامه فعال تا در یک درخواست چند بار کوئری نزنیم */
    protected $activeProgramResolved = false;
    protected $activeProgramCache = null;

    /** نام روزهای هفته (0=شنبه تا 6=جمعه) */
    protected array $weekDayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];


    use SEOTools;


    public function mount()

    {
        $this->user = Auth::user();

        $this->seoConfig();

        $this->student = $this->user?->student ?? null;

    }


    public function seoConfig()

    {

        $this->seo()->setTitle('پیشخوان');

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

        return $this->activeProgramCache = WeeklyProgram::where('advising_session_id', $activeSession->id)->first();
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

        $start = Carbon::parse($program->start_date)->startOfDay();
        $end = Carbon::parse($program->end_date)->endOfDay();

        $parts = $program->parts()->with(['ccSubject'])->get();
        if ($parts->isEmpty()) {
            return $empty;
        }

        $partIds = $parts->pluck('id')->all();
        $map = $this->studySecondsMap($partIds, $start, $end);
        $seconds = $map['seconds'];
        $ratings = $map['ratings'];

        $restIdx = $program->restDays->pluck('day_index')->map(fn($v) => (int) $v)->all();

        // ---- شکست روزانه ----
        $daily = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $start->copy()->addDays($i);
            $dayParts = $parts->where('day_of_week', $i);
            $plannedMin = (int) $dayParts->sum('duration_minutes');
            $studiedSec = 0;
            foreach ($dayParts as $p) {
                $studiedSec += $seconds[$p->id] ?? 0;
            }
            $isRest = in_array($i, $restIdx, true) || ($plannedMin === 0 && $dayParts->isEmpty());
            $daily[] = [
                'label' => $this->weekDayNames[$i],
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
            'total_study_hours' => 0,
            'total_planned_hours' => 0,
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
            'total_study_hours' => round($totalDoneSec / 3600, 1),
            'total_planned_hours' => round($totalPlannedMin / 60, 1),
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

        $trial = TrialWeek::where('user_id', $this->user->id)
            ->whereNotNull('acquisition_supporter_id')
            ->latest()->first();
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

        return $parts->toArray();
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
     * محاسبه پیشرفت ساعت مطالعه
     */
    public function getStudyHoursProgress()
    {
        $activeProgram = $this->getActiveWeeklyProgram();

        if (!$activeProgram) {
            return [
                'total_hours' => 0,
                'completed_hours' => 0,
                'extra_hours' => 0,
                'percentage' => 0,
                'extra_percentage' => 0,
            ];
        }

        // کل ساعات برنامه
        $totalMinutes = $activeProgram->parts()->sum('duration_minutes');
        $totalHours = round($totalMinutes / 60, 1);

        // ساعات انجام شده در این هفته
        $startDate = Carbon::parse($activeProgram->start_date);
        $endDate = Carbon::parse($activeProgram->end_date);

        $completedMinutes = StudyPartSession::where('student_id', $this->student->id)
            ->where('weekly_program_id', $activeProgram->id)
            ->whereBetween('started_at', [$startDate, $endDate])
            ->whereNotNull('ended_at')
            ->get()
            ->sum(function ($session) {
                if ($session->started_at && $session->ended_at) {
                    return $session->started_at->diffInMinutes($session->ended_at);
                }
                return 0;
            });

        $completedHours = round($completedMinutes / 60, 1);

        // محاسبه درصد و ساعات اضافی
        $percentage = $totalHours > 0 ? min(($completedHours / $totalHours) * 100, 100) : 0;
        $extraHours = max($completedHours - $totalHours, 0);
        $extraPercentage = $totalHours > 0 && $extraHours > 0 ? ($extraHours / $totalHours) * 100 : 0;

        return [
            'total_hours' => $totalHours,
            'completed_hours' => min($completedHours, $totalHours),
            'extra_hours' => $extraHours,
            'percentage' => round($percentage, 1),
            'extra_percentage' => round($extraPercentage, 1),
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
                'total_days' => 7,
                'submitted_days' => 0,
                'percentage' => 0,
            ];
        }

        $startDate = Carbon::parse($activeProgram->start_date);
        $endDate = Carbon::parse($activeProgram->end_date);

        $submittedReports = DailyReport::where('student_id', $this->student->id)
            ->where('weekly_program_id', $activeProgram->id)
            ->where('is_compensatory', false)

            ->whereBetween('report_date', [$startDate, $endDate])
            ->count();

        // تعداد روزهایی که برنامه دارند (برای محاسبه درصد)
        $programDays = $activeProgram->parts()->distinct('day_of_week')->count('day_of_week');
        $totalDays = max($programDays, 1);
        $percentage = ($submittedReports / $totalDays) * 100;

        return [
            'total_days' => $totalDays,
            'submitted_days' => $submittedReports,
            'percentage' => round(min($percentage, 100), 1),
        ];
    }



    public function render()

    {

        $advisorStudent = $this->getDisplayAdvisor();

        $unreadNotificationsCount = $this->getUnreadNotificationsCount();
        $todayProgram = $this->getTodayProgram();

        $studyHoursProgress = $this->getStudyHoursProgress();

        $reportProgress = $this->getReportProgress();

        $weeklyInsights = $this->getWeeklyInsights();
        $monthlyInsights = $this->getMonthlyInsights();
        $weeklySpecialParts = $this->getWeeklySpecialParts();
        $isGraduateStudent = $this->isGraduateStudent();
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
            'studyHoursProgress' => $studyHoursProgress,
            'reportProgress' => $reportProgress,
            'weeklyInsights' => $weeklyInsights,
            'monthlyInsights' => $monthlyInsights,
            'classSchedule' => $classSchedule,
            'schoolInfo' => $schoolInfo,
            'weeklySpecialParts' => $weeklySpecialParts,
            'isGraduateStudent' => $isGraduateStudent,
        ])->layout('layouts.client.app');

    }

}
