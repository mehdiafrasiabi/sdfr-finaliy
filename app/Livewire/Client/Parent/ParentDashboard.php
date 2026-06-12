<?php

namespace App\Livewire\Client\Parent;

use App\Models\AdvisingSession;
use App\Models\DailyReport;
use App\Models\DailyReportPart;
use App\Models\ProgramPart;
use App\Models\Student;
use App\Models\StudyPartSession;
use App\Models\WeeklyProgram;
use Artesaos\SEOTools\Traits\SEOTools;
use Carbon\Carbon;
use Livewire\Component;

class ParentDashboard extends Component
{
    use SEOTools;

    public ?Student $student = null;

    public string $studentName = '';

    public string $parentRole = '';

    /** نام روزهای هفته (0=شنبه تا 6=جمعه) */
    protected array $weekDayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];

    public function mount()
    {
        $auth = session('parent_portal');
        if (!$auth || empty($auth['student_id'])) {
            return redirect()->route('client.parent.portal.login');
        }

        $this->student = Student::with('advisor')->find($auth['student_id']);
        if (!$this->student) {
            session()->forget('parent_portal');
            return redirect()->route('client.parent.portal.login');
        }

        $this->studentName = $auth['student_name'] ?? ($this->student->user?->name ?? 'دانش‌آموز');
        $this->parentRole = $auth['parent_role'] ?? '';

        $this->seo()->setTitle('پنل والدین');
    }

    public function logout()
    {
        session()->forget('parent_portal');
        return redirect()->route('client.parent.portal.login');
    }

    public function getParentRoleLabelProperty(): string
    {
        return match ($this->parentRole) {
            'father' => 'پدر',
            'mother' => 'مادر',
            default => 'والد',
        };
    }

    /**
     * آخرین جلسه مشاوره برگزار شده
     */
    protected function getLatestHeldSession(): ?AdvisingSession
    {
        return AdvisingSession::where('student_id', $this->student->id)
            ->where('result_status', AdvisingSession::RESULT_HELD)
            ->orderBy('activation_date', 'desc')
            ->with('advisor')
            ->first();
    }

    /**
     * برنامه هفتگی مرتبط با آخرین جلسه مشاوره برگزار شده
     */
    protected function getProgramForSession(?AdvisingSession $session): ?WeeklyProgram
    {
        if ($session) {
            $program = WeeklyProgram::where('advising_session_id', $session->id)->latest()->first();
            if ($program) {
                return $program;
            }
        }

        return WeeklyProgram::where('student_id', $this->student->id)->latest()->first();
    }

    /**
     * ثانیه‌های مطالعه ثبت‌شده به تفکیک پارت در بازه برنامه
     *
     * @return array<int,int> program_part_id => seconds
     */
    protected function studySecondsMap(array $partIds, Carbon $start, Carbon $end): array
    {
        if (empty($partIds)) {
            return [];
        }

        $rows = StudyPartSession::query()
            ->where('student_id', $this->student->id)
            ->whereIn('program_part_id', $partIds)
            ->whereBetween('started_at', [$start, $end])
            ->get(['id', 'program_part_id', 'started_at', 'ended_at', 'duration_seconds']);

        $seconds = [];
        foreach ($rows as $row) {
            $sec = (int) ($row->duration_seconds ?? 0);
            if ($sec <= 0 && $row->started_at && $row->ended_at) {
                $sec = $row->started_at->diffInSeconds($row->ended_at);
            }
            if ($sec > 0) {
                $seconds[$row->program_part_id] = ($seconds[$row->program_part_id] ?? 0) + $sec;
            }
        }

        return $seconds;
    }

    /**
     * تبدیل پارت برنامه به آرایه نمایش برای لیست‌ها (امتحان/پرسش‌وپاسخ/تکلیف)
     */
    protected function partToRow(ProgramPart $part, array $secondsMap): array
    {
        $studiedSec = $secondsMap[$part->id] ?? 0;

        return [
            'lesson' => $part->ccSubject->name ?? ($part->lesson_name ?? ($part->lesson->name ?? 'درس')),
            'chapter' => $part->ccChapter->name ?? null,
            'day' => $this->weekDayNames[$part->day_of_week] ?? '-',
            'date' => $part->part_date ? jdate($part->part_date)->format('Y/m/d') : null,
            'type_label' => $part->part_type_label,
            'duration_minutes' => (int) ($part->duration_minutes ?? 0),
            'test_count' => (int) ($part->test_count ?? 0),
            'is_done' => $studiedSec > 0,
            'studied_minutes' => (int) floor($studiedSec / 60),
        ];
    }

    public function render()
    {
        $session = $this->getLatestHeldSession();
        $program = $this->getProgramForSession($session);

        $sessionInfo = $session ? [
            'date' => jdate($session->activation_date)->format('Y/m/d'),
            'time' => $session->session_time?->format('H:i'),
            'advisor' => $session->advisor?->name,
            'location' => $session->location_label,
        ] : null;

        $data = [
            'has_program' => false,
            'week_range' => null,
            'study' => ['planned_hours' => 0, 'done_hours' => 0, 'percent' => 0],
            'tests' => ['planned' => 0, 'done' => 0, 'percent' => 0],
            'parts' => ['test' => 0, 'descriptive' => 0, 'total' => 0, 'test_percent' => 0, 'descriptive_percent' => 0],
            'reports' => collect(),
            'exams' => [],
            'qa' => [],
            'homework' => [],
        ];

        if ($program) {
            $start = Carbon::parse($program->start_date)->startOfDay();
            $end = Carbon::parse($program->end_date ?? $start->copy()->addDays(7))->endOfDay();

            $parts = $program->parts()->with(['ccSubject', 'ccChapter', 'lesson'])->orderBy('day_of_week')->orderBy('part_order')->get();
            $secondsMap = $this->studySecondsMap($parts->pluck('id')->all(), $start, $end);

            // ---- ساعت مطالعه این هفته ----
            $plannedMinutes = (int) $parts->sum('duration_minutes');
            $doneSeconds = array_sum($secondsMap);
            $plannedHours = round($plannedMinutes / 60, 1);
            $doneHours = round($doneSeconds / 3600, 1);

            // ---- تعداد تست این هفته ----
            $testsPlanned = (int) $parts->sum('test_count');
            $reportIds = DailyReport::where('student_id', $this->student->id)
                ->where('weekly_program_id', $program->id)
                ->whereBetween('report_date', [$start, $end])
                ->pluck('id')->all();
            $testsDone = !empty($reportIds)
                ? (int) DailyReportPart::whereIn('daily_report_id', $reportIds)->sum('tests_done')
                : 0;

            // ---- پارت تستی / تشریحی ----
            $testParts = $parts->where('part_type', ProgramPart::PART_TYPE_TEST)->count();
            $descriptiveParts = $parts->where('part_type', ProgramPart::PART_TYPE_DESCRIPTIVE)->count();
            $totalParts = $parts->count();

            // ---- گزارش‌های ارسالی این هفته ----
            $reports = DailyReport::where('student_id', $this->student->id)
                ->where('weekly_program_id', $program->id)
                ->whereBetween('report_date', [$start, $end])
                ->with(['detail', 'feedback', 'reportParts.programPart', 'weeklyProgram.parts'])
                ->orderBy('report_date')
                ->get()
                ->map(fn(DailyReport $r) => [
                    'date' => jdate($r->report_date)->format('Y/m/d'),
                    'day' => $r->day_name,
                    'is_compensatory' => (bool) $r->is_compensatory,
                    'status_label' => $r->status_label,
                    'status' => $r->status,
                    'rating_label' => $r->rating_label,
                    'phone_hours' => $r->phone_hours,
                    'tests' => $r->total_tests,
                    'read_parts' => $r->read_parts_count,
                    'total_parts' => $r->total_parts,
                    'advisor_comment' => $r->advisor_comment,
                ]);

            // ---- امتحانات / پرسش و پاسخ / تکالیف این هفته ----
            $examParts = $parts->filter(
                fn($p) => in_array($p->source_type, [ProgramPart::SOURCE_EXAM, ProgramPart::SOURCE_COMPREHENSIVE_EXAM], true)
                    || in_array($p->part_type, [ProgramPart::PART_TYPE_TOPIC_EXAM, ProgramPart::PART_TYPE_COMPREHENSIVE_EXAM, ProgramPart::PART_TYPE_EXAM_ANALYSIS], true)
            );
            $qaParts = $parts->where('source_type', ProgramPart::SOURCE_CLASS_QA);
            $homeworkParts = $parts->where('source_type', ProgramPart::SOURCE_HOMEWORK);

            $data = [
                'has_program' => true,
                'week_range' => jdate($start)->format('Y/m/d') . ' تا ' . jdate(Carbon::parse($program->end_date ?? $end))->format('Y/m/d'),
                'study' => [
                    'planned_hours' => $plannedHours,
                    'done_hours' => $doneHours,
                    'percent' => $plannedHours > 0 ? round(min(100, ($doneHours / $plannedHours) * 100)) : 0,
                ],
                'tests' => [
                    'planned' => $testsPlanned,
                    'done' => $testsDone,
                    'percent' => $testsPlanned > 0 ? round(min(100, ($testsDone / $testsPlanned) * 100)) : 0,
                ],
                'parts' => [
                    'test' => $testParts,
                    'descriptive' => $descriptiveParts,
                    'total' => $totalParts,
                    'test_percent' => $totalParts > 0 ? round(($testParts / $totalParts) * 100) : 0,
                    'descriptive_percent' => $totalParts > 0 ? round(($descriptiveParts / $totalParts) * 100) : 0,
                ],
                'reports' => $reports,
                'exams' => $examParts->map(fn($p) => $this->partToRow($p, $secondsMap))->values()->all(),
                'qa' => $qaParts->map(fn($p) => $this->partToRow($p, $secondsMap))->values()->all(),
                'homework' => $homeworkParts->map(fn($p) => $this->partToRow($p, $secondsMap))->values()->all(),
            ];
        }

        return view('livewire.client.parent.parent-dashboard', [
            'sessionInfo' => $sessionInfo,
            'advisorName' => $this->student->advisor?->name ?? $sessionInfo['advisor'] ?? null,
            'data' => $data,
        ])->layout('layouts.client.app-auth');
    }
}
