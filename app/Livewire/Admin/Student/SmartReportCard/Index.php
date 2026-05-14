<?php

namespace App\Livewire\Admin\Student\SmartReportCard;

use App\Models\AdvisingSession;
use App\Models\DailyReport;
use App\Models\DailyReportPart;
use App\Models\MakeupSession;
use App\Models\ProgramPart;
use App\Models\SpsTiming;
use App\Models\StudyPartSession;
use App\Models\User;
use App\Models\WeeklyProgram;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

class Index extends Component
{
    public int $studentId;
    public int $userId;
    public string $studentName = '';

    public string $filterStartDate = '';
    public string $filterEndDate = '';
    public bool $reportGenerated = false;
    public string $reportError = '';

    public array $studentInfo = [];
    public array $sessionsList = [];
    public array $studyStats = [];
    public array $reportsData = [];
    public array $studyHoursData = [];

    public function mount(User $student): void
    {
        $this->userId = $student->id;
        $this->studentId = $student->student->id;
        $this->studentName = $student->profile?->full_name
            ?? $student->personalInformation?->name
            ?? $student->name;
    }

    protected function parseDateFilter(string $jalali): ?Carbon
    {
        if (empty(trim($jalali))) return null;
        try {
            return Jalalian::fromFormat('Y/m/d', trim($jalali))->toCarbon();
        } catch (\Exception) {
            return null;
        }
    }

    public function generateReport(): void
    {
        $this->reportError = '';
        $this->reportGenerated = false;

        $start = $this->parseDateFilter($this->filterStartDate);
        $end   = $this->parseDateFilter($this->filterEndDate);

        if (!$start) {
            $this->reportError = 'تاریخ شروع معتبر نیست. فرمت صحیح: ۱۴۰۴/۰۱/۰۱';
            return;
        }
        if (!$end) {
            $this->reportError = 'تاریخ پایان معتبر نیست. فرمت صحیح: ۱۴۰۴/۰۱/۳۱';
            return;
        }
        if ($start->gt($end)) {
            $this->reportError = 'تاریخ شروع نباید بعد از تاریخ پایان باشد.';
            return;
        }

        $end = $end->copy()->endOfDay();

        $this->loadStudentInfo();
        $this->loadSessionsList($start, $end);
        $this->loadStudyStats($start, $end);
        $this->loadReportsData($start, $end);
        $this->loadStudyHoursData($start, $end);

        $this->reportGenerated = true;
    }

    protected function loadStudentInfo(): void
    {
        $user = User::with([
            'personalInformation.state',
            'personalInformation.city',
            'profile',
            'student.advisor',
            'student.supporter',
            'student.product',
        ])->find($this->userId);

        if (!$user) return;

        $pi      = $user->personalInformation;
        $profile = $user->profile;
        $student = $user->student;

        $gradeMap = ['10' => 'دهم', '11' => 'یازدهم', '12' => 'دوازدهم'];
        $fieldMap = ['math' => 'ریاضی', 'experimental' => 'تجربی', 'human' => 'انسانی'];

        $this->studentInfo = [
            'name'        => $profile?->full_name ?? $pi?->name ?? $user->name ?? '-',
            'mobile'      => $user->mobile ?? '-',
            'email'       => $user->email ?? '-',
            'grade'       => $gradeMap[$pi?->grade ?? ''] ?? '-',
            'field'       => $fieldMap[$pi?->field ?? ''] ?? '-',
            'code_mell'   => $pi?->code_mell ?? '-',
            'birth_date'  => $pi?->birth_date ?? '-',
            'father_name' => $pi?->father_name ?? '-',
            'city'        => $pi?->city?->name ?? '-',
            'state'       => $pi?->state?->name ?? '-',
            'advisor'     => $student?->advisor?->name ?? '-',
            'product'     => $student?->product?->title ?? '-',
        ];
    }

    protected function loadSessionsList(Carbon $start, Carbon $end): void
    {
        $sessions = AdvisingSession::where('student_id', $this->studentId)
            ->whereBetween('activation_date', [$start->toDateString(), $end->toDateString()])
            ->with(['advisor', 'weeklyProgram'])
            ->orderBy('activation_date')
            ->get();

        $this->sessionsList = $sessions->map(function ($session) {
            return [
                'id'                  => $session->id,
                'title'               => $session->title ?? 'جلسه مشاوره',
                'description'         => $session->description ?? '',
                'date'                => jdate($session->activation_date)->format('Y/m/d'),
                'day_name'            => $this->jalaliDayName($session->activation_date),
                'time'                => $session->session_time ? $session->session_time->format('H:i') : '-',
                'location'            => $session->location_label,
                'status'              => $session->status_label,
                'result'              => $session->result_label,
                'advisor'             => $session->advisor?->name ?? '-',
                'has_weekly_program'  => $session->weeklyProgram !== null,
            ];
        })->toArray();
    }

    protected function loadStudyStats(Carbon $start, Carbon $end): void
    {
        // Weekly programs that overlap with the date range
        $weeklyProgramIds = WeeklyProgram::where('student_id', $this->studentId)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start->toDateString(), $end->toDateString()])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('start_date', '<=', $end->toDateString())
                            ->where(function ($q3) use ($start) {
                                $q3->where('end_date', '>=', $start->toDateString())
                                    ->orWhereRaw('DATE_ADD(start_date, INTERVAL 7 DAY) >= ?', [$start->toDateString()]);
                            });
                    });
            })
            ->pluck('id');

        // --- Totals (planned) ---
        $totalParts    = ProgramPart::whereIn('weekly_program_id', $weeklyProgramIds)->count();
        $totalTests    = (int) ProgramPart::whereIn('weekly_program_id', $weeklyProgramIds)->sum('test_count');
        $plannedSeconds = (int) ProgramPart::whereIn('weekly_program_id', $weeklyProgramIds)
            ->sum(DB::raw('duration_minutes * 60'));

        // --- Actuals ---
        $readParts = DailyReportPart::whereHas('dailyReport', fn($q) =>
        $q->where('student_id', $this->studentId)
            ->whereBetween('report_date', [$start->toDateString(), $end->toDateString()])
        )->where('is_read', true)->count();

        $doneTests = (int) DailyReportPart::whereHas('dailyReport', fn($q) =>
        $q->where('student_id', $this->studentId)
            ->whereBetween('report_date', [$start->toDateString(), $end->toDateString()])
        )->sum('tests_done');

        $actualSeconds = (int) SpsTiming::whereHas('studyPartSession', fn($q) =>
        $q->where('student_id', $this->studentId)
        )->whereBetween('started_at', [$start, $end])->sum('duration_seconds');

        $this->studyStats = [
            'total_parts'       => $totalParts,
            'total_tests'       => $totalTests,
            'planned_seconds'   => $plannedSeconds,
            'planned_formatted' => $this->formatSeconds($plannedSeconds),
            'read_parts'        => $readParts,
            'done_tests'        => $doneTests,
            'actual_seconds'    => $actualSeconds,
            'actual_formatted'  => $this->formatSeconds($actualSeconds),
            'parts_percent'     => $totalParts  > 0 ? min(100, round(($readParts  / $totalParts)  * 100)) : 0,
            'tests_percent'     => $totalTests  > 0 ? min(100, round(($doneTests  / $totalTests)  * 100)) : 0,
            'hours_percent'     => $plannedSeconds > 0 ? min(100, round(($actualSeconds / $plannedSeconds) * 100)) : 0,
        ];
    }

    protected function loadReportsData(Carbon $start, Carbon $end): void
    {
        $reports = DailyReport::with(['detail', 'feedback', 'reportParts'])
            ->where('student_id', $this->studentId)
            ->whereBetween('report_date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('report_date')
            ->get();

        $totalSent         = $reports->count();
        $totalApproved     = $reports->filter(fn($r) => ($r->detail?->status ?? 'pending') === 'approved')->count();
        $totalRejected     = $reports->filter(fn($r) => ($r->detail?->status ?? 'pending') === 'rejected')->count();
        $totalPending      = $reports->filter(fn($r) => ($r->detail?->status ?? 'pending') === 'pending')->count();
        $totalCompensatory = $reports->where('is_compensatory', true)->count();
        $totalNotSent      = $this->calculateNotSentDays($start, $end, $reports);

        $reportsList = $reports->map(function ($report) {
            $status = $report->detail?->status ?? 'pending';
            return [
                'id'             => $report->id,
                'date'           => jdate($report->report_date)->format('Y/m/d'),
                'day_name'       => $this->jalaliDayName($report->report_date),
                'is_compensatory'=> $report->is_compensatory,
                'status'         => $status,
                'status_label'   => match ($status) {
                    'approved' => 'تایید شده',
                    'rejected' => 'رد شده',
                    default    => 'در انتظار',
                },
                'parts_read'     => $report->reportParts->where('is_read', true)->count(),
                'total_parts'    => $report->reportParts->count(),
                'advisor_comment'=> $report->feedback?->advisor_comment ?? '',
                'student_reply'  => $report->feedback?->student_reply ?? '',
                'phone_hours'    => $report->detail?->phone_hours ?? 0,
                'description'    => $report->detail?->description ?? '',
                'created_at'     => $report->created_at
                    ? jdate($report->created_at)->format('Y/m/d H:i')
                    : '-',
            ];
        })->toArray();

        $this->reportsData = [
            'total_sent'         => $totalSent,
            'total_approved'     => $totalApproved,
            'total_rejected'     => $totalRejected,
            'total_pending'      => $totalPending,
            'total_compensatory' => $totalCompensatory,
            'total_not_sent'     => $totalNotSent,
            'list'               => $reportsList,
        ];
    }

    protected function calculateNotSentDays(Carbon $start, Carbon $end, $reports): int
    {
        $existingDates = $reports->pluck('report_date')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
            ->toArray();

        $sessions = AdvisingSession::where('student_id', $this->studentId)
            ->where('result_status', 'held')
            ->with('weeklyProgram.restDays')
            ->get();

        $today         = Carbon::today();
        $notSentCount  = 0;
        $countedDates  = [];

        foreach ($sessions as $session) {
            $wp = $session->weeklyProgram;
            if (!$wp) continue;

            $restDayIndices = $wp->restDays?->pluck('day_index')->toArray() ?? [];
            $programStart   = Carbon::parse($session->activation_date);

            for ($dayIndex = 0; $dayIndex <= 7; $dayIndex++) {
                $day       = $programStart->copy()->addDays($dayIndex);
                $dayString = $day->format('Y-m-d');

                if ($day->lt($start) || $day->gt($end)) continue;
                if ($day->gt($today)) continue;
                if (isset($countedDates[$dayString])) continue;
                if (in_array($dayIndex, $restDayIndices)) continue;
                if (in_array($dayString, $existingDates)) continue;

                $countedDates[$dayString] = true;
                $notSentCount++;
            }
        }

        return $notSentCount;
    }

    protected function loadStudyHoursData(Carbon $start, Carbon $end): void
    {
        // Normal study sessions
        $normalSessions = StudyPartSession::where('student_id', $this->studentId)
            ->where('is_completed', true)
            ->whereHas('timing', fn($q) => $q->whereBetween('started_at', [$start, $end]))
            ->with(['timing', 'feedback', 'programPart.ccSubject'])
            ->get();

        // Makeup sessions (اضافه بر سازمان)
        $makeupSessions = MakeupSession::where('student_id', $this->studentId)
            ->whereBetween('started_at', [$start, $end])
            ->with(['ccTopic', 'feedback'])
            ->get();

        $normalSeconds = (int) $normalSessions->sum(fn($s) => $s->timing?->duration_seconds ?? 0);
        $makeupSeconds = (int) $makeupSessions->sum(function ($s) {
            if ($s->started_at && $s->ended_at) {
                return max(0, $s->started_at->diffInSeconds($s->ended_at));
            }
            return 0;
        });
        $totalSeconds = $normalSeconds + $makeupSeconds;

        // Average feedback
        $feedbackRatings = $normalSessions->map(fn($s) => $s->feedback?->rating)->filter();
        $avgFeedback     = $feedbackRatings->count() > 0 ? round($feedbackRatings->avg(), 1) : 0;

        // Average study per session (minutes)
        $totalSessionCount = $normalSessions->count() + $makeupSessions->count();
        $avgStudyMinutes   = $totalSessionCount > 0
            ? round($totalSeconds / $totalSessionCount / 60, 1)
            : 0;

        $normalList = $normalSessions->map(fn($s) => [
            'type'              => 'normal',
            'date'              => $s->timing?->started_at
                ? jdate($s->timing->started_at)->format('Y/m/d')
                : '-',
            'subject'           => $s->programPart?->ccSubject?->name
                ?? $s->programPart?->lesson_name
                    ?? '-',
            'duration_seconds'  => $s->timing?->duration_seconds ?? 0,
            'duration_formatted'=> $this->formatSeconds($s->timing?->duration_seconds ?? 0),
            'feedback_rating'   => $s->feedback?->rating ?? 0,
            'feedback_label'    => $s->feedback?->rating_label ?? '-',
        ])->toArray();

        $makeupList = $makeupSessions->map(function ($s) {
            $seconds = 0;
            if ($s->started_at && $s->ended_at) {
                $seconds = max(0, (int) $s->started_at->diffInSeconds($s->ended_at));
            }
            return [
                'type'               => 'makeup',
                'date'               => $s->started_at ? jdate($s->started_at)->format('Y/m/d') : '-',
                'subject'            => $s->ccTopic?->name ?? '-',
                'duration_seconds'   => $seconds,
                'duration_formatted' => $this->formatSeconds($seconds),
                'part_type'          => $s->part_type_label,
                'status'             => $s->status_label,
                'feedback_rating'    => $s->feedback?->rating ?? 0,
                'feedback_label'     => $s->feedback?->rating_label ?? '-',
            ];
        })->toArray();

        $this->studyHoursData = [
            'normal_seconds'    => $normalSeconds,
            'normal_formatted'  => $this->formatSeconds($normalSeconds),
            'normal_count'      => $normalSessions->count(),
            'makeup_seconds'    => $makeupSeconds,
            'makeup_formatted'  => $this->formatSeconds($makeupSeconds),
            'makeup_count'      => $makeupSessions->count(),
            'total_seconds'     => $totalSeconds,
            'total_formatted'   => $this->formatSeconds($totalSeconds),
            'avg_feedback'      => $avgFeedback,
            'avg_study_minutes' => $avgStudyMinutes,
            'normal_list'       => $normalList,
            'makeup_list'       => $makeupList,
        ];
    }

    protected function formatSeconds(int $seconds): string
    {
        $h = intdiv($seconds, 3600);
        $m = intdiv($seconds % 3600, 60);
        $s = $seconds % 60;
        return sprintf('%02d:%02d:%02d', $h, $m, $s);
    }

    protected function jalaliDayName($date): string
    {
        $dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
        try {
            return $dayNames[jdate($date)->getDayOfWeek()] ?? '-';
        } catch (\Exception) {
            return '-';
        }
    }

    public function render()
    {
        return view('livewire.admin.student.smart-report-card.index')
            ->layout('layouts.admin.app');
    }
}

