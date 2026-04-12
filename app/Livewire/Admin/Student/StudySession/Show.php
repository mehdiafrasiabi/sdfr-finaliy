<?php

namespace App\Livewire\Admin\Student\StudySession;

use App\Models\MakeupSession;
use App\Models\ProgramPart;
use App\Models\SessionFeedback;
use App\Models\StudyPartSession;
use App\Models\StudySession;
use App\Models\AdvisingSession;
use Artesaos\SEOTools\Traits\SEOTools;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Morilog\Jalali\Jalalian;
class Show extends Component
{
    use SEOTools, WithPagination;

    public $studentId;
    public $studentName;
    public $studyTime = [];
    public $studySessions = [];

    // Filters
    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $sortBy = 'started_at';
    public $sortDirection = 'desc';
    public $sessionType = 'all';
    public $partTypeFilter = 'all';
    public $sourceTypeFilter = 'all';
    public $advisingSessionFilter = '';
    // Statistics
    public $totalSessions = 0;
    public $averageDuration = 0;
    public $makeupCount = 0;
    public $regularCount = 0;
    public $programPartsCount = 0;
    public $feedbackAvg = 0;
    public $completedPartsCount = 0;

    // جلسات جبرانی
    public $makeupSessions = [];

    // جلسات مشاوره
    public $advisingSessions = [];
    public $filteredAdvisingPlans = [];

    public int $totalRegularSeconds = 0;
    public int $totalProgramSeconds = 0;
    public int $totalActualSeconds = 0;
    public int $totalMakeupSeconds = 0;
    public int $totalPlannedSeconds = 0;
    public int $unmetSeconds = 0;
    public int $adjustedTotalSeconds = 0;
    public int $activeDays = 1;
    public int $dailyAverageSeconds = 0;
    // مودال جزئیات
    public $showDetailModal = false;
    public $selectedSession = null;
    public $selectedSessionType = null;
    protected $queryString = [
        'search' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'partTypeFilter' => ['except' => 'all'],
        'sourceTypeFilter' => ['except' => 'all'],
        'advisingSessionFilter' => ['except' => ''],
    ];

    public function mount(User $student)
    {
        if (!$student->student) {
            abort(404, 'Student not found');
        }

        $this->studentId = $student->student->id;
        $this->studentName = $student->personalInformation->name ?? $student->name;

        $this->loadAdvisingSessions();
        $this->calculateStudyTime();
        $this->loadStudySessions();
        $this->loadMakeupSessions();
        $this->loadStats();
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()
            ->setTitle('ساعت مطالعه ' . $this->studentName)
            ->setDescription('گزارش کامل زمان مطالعه و جلسات ' . $this->studentName);
    }

    protected function loadAdvisingSessions()
    {
        $this->advisingSessions = AdvisingSession::where('student_id', $this->studentId)
            ->where('result_status', AdvisingSession::RESULT_HELD)
            ->with(['weeklyProgram.parts'])
            ->orderByDesc('activation_date')
            ->get();
        $this->totalPlannedSeconds = (int)$this->advisingSessions
            ->sum(fn($session) => ((int)$session->weeklyProgram?->parts?->sum('duration_minutes')) * 60);

        $this->loadFilteredAdvisingPlans();
    }

    protected function loadFilteredAdvisingPlans(): void
    {
        if (!$this->advisingSessionFilter) {
            $this->filteredAdvisingPlans = [];
            return;
        }

        $session = $this->advisingSessions->firstWhere('id', (int)$this->advisingSessionFilter);

        if (!$session || !$session->weeklyProgram) {
            $this->filteredAdvisingPlans = [];
            return;
        }

        $parts = $session->weeklyProgram->parts()->with(['ccSubject', 'ccChapter', 'ccTopic'])->orderBy('day_of_week')->orderBy('part_order')->get();

        $this->filteredAdvisingPlans = $parts->map(function ($part) use ($session) {
            $hasLogged = StudyPartSession::where('student_id', $this->studentId)
                ->where('weekly_program_id', $session->weeklyProgram->id)
                ->where('program_part_id', $part->id)
                ->exists();

            return [
                'id' => $part->id,
                'lesson_name' => $part->lesson_name,
                'cc_subject' => $part->ccSubject?->name,
                'cc_chapter' => $part->ccChapter?->name,
                'cc_topic' => $part->ccTopic?->name,
                'duration_seconds' => ((int)$part->duration_minutes) * 60,
                'is_logged' => $hasLogged,
            ];
        })->toArray();
    }

    protected function calculateStudyTime()
    {
        $now = Carbon::now();

        // جلسات عادی (بدون برنامه)
        $this->totalRegularSeconds = (int) StudySession::where('student_id', $this->studentId)->sum('duration_seconds');
        $this->totalProgramSeconds = (int) StudyPartSession::where('student_id', $this->studentId)->sum('duration_seconds');
        $this->totalActualSeconds = $this->totalRegularSeconds + $this->totalProgramSeconds;
        $this->totalMakeupSeconds = (int) MakeupSession::where('student_id', $this->studentId)->sum('duration_seconds');
        // جلسات پارت‌های برنامه
        $jalaliToday = Jalalian::fromCarbon($now);
        $todayStart = Jalalian::fromFormat('Y/m/d', $jalaliToday->format('Y/m/d'))->toCarbon()->startOfDay();
        $todayEnd = $todayStart->copy()->endOfDay();
        // Today
        $weekStartJalali = $jalaliToday->subDays($jalaliToday->getDayOfWeek());
        $weekStart = Jalalian::fromFormat('Y/m/d', $weekStartJalali->format('Y/m/d'))->toCarbon()->startOfDay();
        $weekEnd = $weekStart->copy()->addDays(6)->endOfDay();

        $monthYear = (int)$jalaliToday->getYear();
        $monthNumber = (int)$jalaliToday->getMonth();
        $monthStart = Jalalian::fromFormat('Y/m/d', sprintf('%04d/%02d/01', $monthYear, $monthNumber))->toCarbon()->startOfDay();
        $monthDays = Jalalian::fromFormat('Y/m/d', sprintf('%04d/%02d/01', $monthYear, $monthNumber))->getMonthDays();
        $monthEnd = $monthStart->copy()->addDays($monthDays - 1)->endOfDay();

        $todaySeconds = $this->sumAllStudySecondsBetween($todayStart, $todayEnd);
        $weekSeconds = $this->sumAllStudySecondsBetween($weekStart, $weekEnd);
        $monthSeconds = $this->sumAllStudySecondsBetween($monthStart, $monthEnd);

        $this->unmetSeconds = max(0, $this->totalPlannedSeconds - $this->totalActualSeconds);
        $this->adjustedTotalSeconds = max(0, $this->totalActualSeconds - $this->unmetSeconds);

        $firstActivity = collect([
            StudySession::where('student_id', $this->studentId)->min('started_at'),
            StudyPartSession::where('student_id', $this->studentId)->min('started_at'),
            MakeupSession::where('student_id', $this->studentId)->min('started_at'),
        ])->filter()->map(fn($d) => Carbon::parse($d))->sort()->first();

        $this->activeDays = $firstActivity ? max(1, $firstActivity->startOfDay()->diffInDays($now->copy()->startOfDay()) + 1) : 1;
        $this->dailyAverageSeconds = (int) floor($this->adjustedTotalSeconds / $this->activeDays);

        $this->studyTime = [
            'regular_plus_extra' => $this->formatHourMinute($this->totalRegularSeconds) . '+' . $this->formatHourMinute($this->totalMakeupSeconds),
            'today' => $this->formatHourMinute($todaySeconds),
            'week' => $this->formatHourMinute($weekSeconds),
            'month' => $this->formatHourMinute($monthSeconds),
            'makeup' => $this->formatHourMinute($this->totalMakeupSeconds),
            'total_actual' => $this->formatHourMinute($this->totalActualSeconds),
            'total_planned' => $this->formatHourMinute($this->totalPlannedSeconds),
            'unmet' => $this->formatHourMinute($this->unmetSeconds),
            'daily_average' => $this->formatHourMinute($this->dailyAverageSeconds),
            'final_total' => $this->formatHourMinute(max(0, $this->totalPlannedSeconds - $this->totalActualSeconds + $this->totalMakeupSeconds)),
        ];

        // Calculate total sessions count
        $this->totalSessions = StudyPartSession::where('student_id', $this->studentId)->count();
        $this->averageDuration = $this->totalSessions > 0 ? round($this->totalProgramSeconds / $this->totalSessions / 60, 0) : 0;
    }
    protected function sumAllStudySecondsBetween(Carbon $from, Carbon $to): int
    {
        $program = (int) StudyPartSession::where('student_id', $this->studentId)
            ->whereBetween('started_at', [$from, $to])
            ->sum('duration_seconds');

        $regular = (int) StudySession::where('student_id', $this->studentId)
            ->whereBetween('started_at', [$from, $to])
            ->sum('duration_seconds');

        return $program + $regular;
    }

    protected function loadStats()
    {
        $this->makeupCount = MakeupSession::where('student_id', $this->studentId)->count();
        $this->regularCount = StudySession::where('student_id', $this->studentId)->count();

        // کل پارت‌های برنامه‌ریزی شده برای این دانش‌آموز در همه برنامه‌های هفتگی
        $this->programPartsCount = ProgramPart::whereHas('weeklyProgram', function ($q) {
            $q->where('student_id', $this->studentId);
        })->count();

        // تعداد پارت‌های ثبت‌شده (لاگ‌شده)
        $this->completedPartsCount = StudyPartSession::where('student_id', $this->studentId)->count();

        // میانگین بازخورد از تمام پارت‌های برنامه (از طریق sps_id)
        $spsIds = StudyPartSession::where('student_id', $this->studentId)->pluck('id');
        $this->feedbackAvg = round(
            SessionFeedback::whereIn('sps_id', $spsIds)->avg('rating') ?? 0,
            1
        );
    }

    protected function loadStudySessions()
    {
        $query = StudyPartSession::where('student_id', $this->studentId)
            ->with(['programPart.ccSubject', 'programPart.ccChapter', 'programPart.ccTopic', 'feedback', 'weeklyProgram.advisingSession']);
        // Apply filters
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('programPart', function ($partQ) {
                    $partQ->where('lesson_name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            });
        }

        if ($this->dateFrom) {
            try {
                $dateFrom = Jalalian::fromFormat('Y/m/d', $this->dateFrom)->toCarbon()->startOfDay();
                $query->where('started_at', '>=', $dateFrom);
            } catch (\Throwable $e) {
            }
        }

        if ($this->dateTo) {
            try {
                $dateTo = Jalalian::fromFormat('Y/m/d', $this->dateTo)->toCarbon()->endOfDay();
                $query->where('started_at', '<=', $dateTo);
            } catch (\Throwable $e) {
                // Invalid date format
            }
        }

        if ($this->partTypeFilter !== 'all') {
            $query->whereHas('programPart', function ($q) {
                $q->where('part_type', $this->partTypeFilter);
            });
        }

        if ($this->sourceTypeFilter !== 'all') {
            $query->whereHas('programPart', function ($q) {
                $q->where('source_type', $this->sourceTypeFilter);
            });
        }

        // Advising session filter
        if ($this->advisingSessionFilter) {
            $query->whereHas('weeklyProgram', function ($q) {
                $q->where('advising_session_id', $this->advisingSessionFilter);
            });
        }

        $this->studySessions = $query->orderBy($this->sortBy, $this->sortDirection)->get();
    }

    protected function loadMakeupSessions()
    {
        $query = MakeupSession::where('student_id', $this->studentId)
            ->with(['ccTopic.chapter.subject']);

        // Apply same date filters
        if ($this->dateFrom) {
            try {
                $dateFrom = Jalalian::fromFormat('Y/m/d', $this->dateFrom)->toCarbon()->startOfDay();
                $query->where('created_at', '>=', $dateFrom);
            } catch (\Throwable $e) {
                // Invalid date format
            }
        }

        if ($this->dateTo) {
            try {
                $dateTo = Jalalian::fromFormat('Y/m/d', $this->dateTo)->toCarbon()->endOfDay();
                $query->where('created_at', '<=', $dateTo);
            } catch (\Throwable $e) {
                // Invalid date format
            }
        }

        if ($this->partTypeFilter !== 'all') {
            $query->where('part_type', $this->partTypeFilter);
        }

        $this->makeupSessions = $query->orderByDesc('created_at')->get();
    }

    public function updatedSearch() { $this->loadStudySessions(); $this->loadMakeupSessions(); }
    public function updatedPartTypeFilter() { $this->loadStudySessions(); $this->loadMakeupSessions(); }
    public function updatedSourceTypeFilter() { $this->loadStudySessions(); }
    public function updatedAdvisingSessionFilter() { $this->loadStudySessions(); $this->loadFilteredAdvisingPlans(); }
    public function updatedDateFrom() { $this->loadStudySessions(); $this->loadMakeupSessions(); }
    public function updatedDateTo() { $this->loadStudySessions(); $this->loadMakeupSessions(); }
    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }

        $this->loadStudySessions();
    }

    public function showDetail($sessionId, $type = 'program')
    {
        $this->selectedSessionType = $type;

        if ($type === 'makeup') {
            $this->selectedSession = MakeupSession::with(['ccTopic.chapter.subject.grade', 'student.user.personalInformation'])->find($sessionId);
        } else {
            $this->selectedSession = StudyPartSession::with(['programPart.ccSubject', 'programPart.ccChapter', 'programPart.ccTopic', 'feedback', 'student.user.personalInformation', 'weeklyProgram.advisingSession'])->find($sessionId);

        }

        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedSession = null;
        $this->selectedSessionType = null;
    }

    public function approveMakeup($id)
    {
        $makeup = MakeupSession::where('id', $id)->where('student_id', $this->studentId)->first();
        if ($makeup && $makeup->status === 'pending') {
            $makeup->update(['status' => 'approved']);
            $this->loadMakeupSessions();
            $this->calculateStudyTime();
            $this->loadStats();
            session()->flash('success', 'جلسه جبرانی تایید شد.');
        }
    }

    public function rejectMakeup($id)
    {
        $makeup = MakeupSession::where('id', $id)->where('student_id', $this->studentId)->first();
        if ($makeup && $makeup->status === 'pending') {
            $makeup->update(['status' => 'rejected']);
            $this->loadMakeupSessions();
            $this->loadStats();
            session()->flash('success', 'جلسه جبرانی رد شد.');
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->sortBy = 'started_at';
        $this->sortDirection = 'desc';

        $this->partTypeFilter = 'all';
        $this->sourceTypeFilter = 'all';
        $this->advisingSessionFilter = '';

        $this->loadStudySessions();
        $this->loadMakeupSessions();
        $this->loadFilteredAdvisingPlans();
    }

    public function formatDuration(?int $seconds): string
    {
        if (is_null($seconds) || $seconds <= 0) {
            return '-';
        }

        return sprintf('%02d:%02d:%02d', floor($seconds / 3600), floor(($seconds % 3600) / 60), $seconds % 60);
    }

    public function formatHourMinute(?int $seconds): string
    {
        if (is_null($seconds) || $seconds <= 0) {
            return '00:00';
        }

        return sprintf('%02d:%02d', floor($seconds / 3600), floor(($seconds % 3600) / 60));
    }

    public function isLatePartSession($session): bool
    {
        if (!$session?->started_at || !$session?->ended_at || (int)$session->duration_seconds <= 0) {
            return false;
        }

        $expectedEnd = $session->started_at->copy()->addSeconds((int)$session->duration_seconds);
        return $session->ended_at->gt($expectedEnd->addMinutes(20));
    }
    public function exportToExcel() { session()->flash('success', 'فایل Excel در حال آماده‌سازی است...'); }
    public function exportToPdf() { session()->flash('success', 'فایل PDF در حال آماده‌سازی است...'); }
    public function deleteSession($sessionId)
    {
        try {
            $session = StudyPartSession::findOrFail($sessionId);
            $session->delete();

            session()->flash('success', 'جلسه مطالعه با موفقیت حذف شد.');

            $this->calculateStudyTime();
            $this->loadStudySessions();
            $this->loadStats();

        } catch (\Exception $e) {
            session()->flash('error', 'خطا در حذف جلسه مطالعه: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.student.study-session.show')->layout('layouts.admin.app');
    }
}
