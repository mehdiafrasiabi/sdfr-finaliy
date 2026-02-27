<?php

namespace App\Livewire\Admin\Student\StudySession;

use App\Models\MakeupSession;
use App\Models\SessionFeedback;
use App\Models\StudyPartSession;
use App\Models\StudySession;
use App\Models\AdvisingSession;
use Artesaos\SEOTools\Traits\SEOTools;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

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
    public $sessionType = 'all'; // all | regular | makeup | program
    public $feedbackFilter = 'all'; // all | has_feedback | no_feedback
    public $partTypeFilter = 'all'; // all | test | descriptive | video
    public $advisingSessionFilter = ''; // فیلتر بر اساس جلسه مشاوره

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

    // مودال جزئیات
    public $showDetailModal = false;
    public $selectedSession = null;
    public $selectedSessionType = null; // 'regular' | 'makeup' | 'program'

    protected $queryString = [
        'search' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'sessionType' => ['except' => 'all'],
        'feedbackFilter' => ['except' => 'all'],
        'partTypeFilter' => ['except' => 'all'],
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
            ->with(['weeklyProgram'])
            ->orderByDesc('activation_date')
            ->get();
    }

    protected function calculateStudyTime()
    {
        $now = Carbon::now();

        // جلسات عادی (بدون برنامه)
        $totalSeconds = StudySession::where('student_id', $this->studentId)->sum('duration_seconds');

        // جلسات پارت‌های برنامه
        $programSeconds = StudyPartSession::where('student_id', $this->studentId)
            ->where('is_completed', true)
            ->sum('duration_seconds');

        // Today
        $todaySeconds = StudyPartSession::where('student_id', $this->studentId)
            ->whereDate('started_at', $now->toDateString())
            ->sum('duration_seconds');

        // This Week
        $weekStart = $now->copy()->startOfWeek(Carbon::SATURDAY);
        $weekEnd = $now->copy()->endOfWeek(Carbon::FRIDAY);

        $weekSeconds = StudyPartSession::where('student_id', $this->studentId)
            ->whereBetween('started_at', [$weekStart, $weekEnd])
            ->sum('duration_seconds');

        // This Month
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();

        $monthSeconds = StudyPartSession::where('student_id', $this->studentId)
            ->whereBetween('started_at', [$monthStart, $monthEnd])
            ->sum('duration_seconds');

        // جلسات جبرانی
        $makeupSeconds = MakeupSession::where('student_id', $this->studentId)
            ->where('status', 'approved')
            ->sum('duration_seconds');

        $formatTime = fn($seconds) => sprintf(
            '%02d:%02d:%02d',
            floor($seconds / 3600),
            floor(($seconds % 3600) / 60),
            $seconds % 60
        );

        $this->studyTime = [
            'total' => $formatTime($totalSeconds + $programSeconds + $makeupSeconds),
            'today' => $formatTime($todaySeconds),
            'week' => $formatTime($weekSeconds),
            'month' => $formatTime($monthSeconds),
            'makeup' => $formatTime($makeupSeconds),
            'program' => $formatTime($programSeconds),
        ];

        // Calculate total sessions count
        $this->totalSessions = StudyPartSession::where('student_id', $this->studentId)->count();

        // Calculate average duration
        $totalDuration = $programSeconds;
        if ($this->totalSessions > 0) {
            $this->averageDuration = round($totalDuration / $this->totalSessions / 60, 0);
        }
    }

    protected function loadStats()
    {
        $this->makeupCount = MakeupSession::where('student_id', $this->studentId)->count();
        $this->regularCount = StudySession::where('student_id', $this->studentId)->count();
        $this->programPartsCount = StudyPartSession::where('student_id', $this->studentId)
            ->where('is_completed', true)
            ->count();

        $this->completedPartsCount = $this->programPartsCount;

        $this->feedbackAvg = round(
            SessionFeedback::where('student_id', $this->studentId)->avg('rating') ?? 0,
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
                $dateFrom = Carbon::createFromFormat('Y/m/d', $this->dateFrom)->startOfDay();
                $query->where('started_at', '>=', $dateFrom);
            } catch (\Exception $e) {
                // Invalid date format
            }
        }

        if ($this->dateTo) {
            try {
                $dateTo = Carbon::createFromFormat('Y/m/d', $this->dateTo)->endOfDay();
                $query->where('started_at', '<=', $dateTo);
            } catch (\Exception $e) {
                // Invalid date format
            }
        }

        // Feedback filter
        if ($this->feedbackFilter === 'has_feedback') {
            $query->has('feedback');
        } elseif ($this->feedbackFilter === 'no_feedback') {
            $query->doesntHave('feedback');
        }

        // Part type filter
        if ($this->partTypeFilter !== 'all') {
            $query->whereHas('programPart', function ($q) {
                $q->where('part_type', $this->partTypeFilter);
            });
        }

        // Advising session filter
        if ($this->advisingSessionFilter) {
            $query->whereHas('weeklyProgram', function ($q) {
                $q->where('advising_session_id', $this->advisingSessionFilter);
            });
        }

        // Apply sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        $this->studySessions = $query->get();
    }

    protected function loadMakeupSessions()
    {
        $query = MakeupSession::where('student_id', $this->studentId)
            ->with(['ccTopic.chapter.subject']);

        // Apply same date filters
        if ($this->dateFrom) {
            try {
                $dateFrom = Carbon::createFromFormat('Y/m/d', $this->dateFrom)->startOfDay();
                $query->where('created_at', '>=', $dateFrom);
            } catch (\Exception $e) {
                // Invalid date format
            }
        }

        if ($this->dateTo) {
            try {
                $dateTo = Carbon::createFromFormat('Y/m/d', $this->dateTo)->endOfDay();
                $query->where('created_at', '<=', $dateTo);
            } catch (\Exception $e) {
                // Invalid date format
            }
        }

        // Part type filter for makeup
        if ($this->partTypeFilter !== 'all') {
            $query->where('part_type', $this->partTypeFilter);
        }

        $this->makeupSessions = $query->orderByDesc('created_at')->get();
    }

    public function updatedSearch()
    {
        $this->loadStudySessions();
        if ($this->sessionType === 'all' || $this->sessionType === 'makeup') {
            $this->loadMakeupSessions();
        }
    }

    public function updatedSessionType()
    {
        $this->loadStudySessions();
        $this->loadMakeupSessions();
    }

    public function updatedFeedbackFilter()
    {
        $this->loadStudySessions();
    }

    public function updatedPartTypeFilter()
    {
        $this->loadStudySessions();
        $this->loadMakeupSessions();
    }

    public function updatedAdvisingSessionFilter()
    {
        $this->loadStudySessions();
    }

    public function updatedDateFrom()
    {
        $this->loadStudySessions();
        $this->loadMakeupSessions();
    }

    public function updatedDateTo()
    {
        $this->loadStudySessions();
        $this->loadMakeupSessions();
    }

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
            $this->selectedSession = MakeupSession::with(['ccTopic.chapter.subject.grade', 'student.user.personalInformation'])
                ->find($sessionId);
        } else {
            $this->selectedSession = StudyPartSession::with(['programPart.ccSubject', 'programPart.ccChapter', 'programPart.ccTopic', 'feedback', 'student.user.personalInformation', 'weeklyProgram.advisingSession'])
                ->find($sessionId);
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
        $this->sessionType = 'all';
        $this->feedbackFilter = 'all';
        $this->partTypeFilter = 'all';
        $this->advisingSessionFilter = '';

        $this->loadStudySessions();
        $this->loadMakeupSessions();
    }

    public function formatDuration(?int $seconds): string
    {
        if (is_null($seconds) || $seconds == 0) {
            return '-';
        }

        return sprintf(
            '%02d:%02d:%02d',
            floor($seconds / 3600),
            floor(($seconds % 3600) / 60),
            $seconds % 60
        );
    }

    public function exportToExcel()
    {
        session()->flash('success', 'فایل Excel در حال آماده‌سازی است...');
    }

    public function exportToPdf()
    {
        session()->flash('success', 'فایل PDF در حال آماده‌سازی است...');
    }

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
        return view('livewire.admin.student.study-session.show')
            ->layout('layouts.admin.app');
    }
}
