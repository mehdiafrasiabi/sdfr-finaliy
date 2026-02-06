<?php

namespace App\Livewire\Admin\Student\StudySession;
use App\Models\MakeupSession;
use App\Models\SessionFeedback;
use App\Models\StudyPartSession;
use App\Models\StudySession;
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
    public $sessionType = 'all'; // all | regular | makeup
    // Statistics
    public $totalSessions = 0;
    public $averageDuration = 0;
    public $makeupCount = 0;
    public $regularCount = 0;
    public $feedbackAvg = 0;
    public $completedPartsCount = 0;
    // جلسات جبرانی
    public $makeupSessions = [];
    protected $queryString = [
        'search' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'sessionType' => ['except' => 'all'],
    ];

    public function mount(User $student)
    {
        // چک کردن اینکه کاربر حتما student داشته باشد
        if (!$student->student) {
            abort(404, 'Student not found');
        }

        $this->studentId = $student->student->id;
        $this->studentName = $student->personalInformation->name ?? $student->name;

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

    protected function calculateStudyTime()
    {
        $now = Carbon::now();

        $totalSeconds = StudySession::where('student_id', $this->studentId)->sum('duration_seconds');

        // Today - using today's date
        $todaySeconds = StudySession::where('student_id', $this->studentId)
            ->whereDate('started_at', $now->toDateString())
            ->sum('duration_seconds');

        // This Week - Saturday to Friday (Persian week)
        $weekStart = $now->copy()->startOfWeek(Carbon::SATURDAY);
        $weekEnd = $now->copy()->endOfWeek(Carbon::FRIDAY);

        $weekSeconds = StudySession::where('student_id', $this->studentId)
            ->whereBetween('started_at', [$weekStart, $weekEnd])
            ->sum('duration_seconds');

        // This Month
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();

        $monthSeconds = StudySession::where('student_id', $this->studentId)
            ->whereBetween('started_at', [$monthStart, $monthEnd])
            ->sum('duration_seconds');

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
            'total' => $formatTime($totalSeconds + $makeupSeconds),
            'today' => $formatTime($todaySeconds),
            'week' => $formatTime($weekSeconds),
            'month' => $formatTime($monthSeconds),
            'makeup' => $formatTime($makeupSeconds),
        ];

        // Calculate total sessions count
        $this->totalSessions = StudySession::where('student_id', $this->studentId)->count();

        // Calculate average duration
        if ($this->totalSessions > 0) {
            $this->averageDuration = round($totalSeconds / $this->totalSessions / 60, 0);
        }
    }
    protected function loadStats()
    {
        $this->makeupCount = MakeupSession::where('student_id', $this->studentId)->count();
        $this->regularCount = $this->totalSessions;
        $this->completedPartsCount = StudyPartSession::where('student_id', $this->studentId)
            ->where('is_completed', true)
            ->count();
        $this->feedbackAvg = round(
            SessionFeedback::where('student_id', $this->studentId)->avg('rating') ?? 0,
            1
        );
    }

    protected function loadStudySessions()
    {
        $query = StudySession::where('student_id', $this->studentId);

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('note', 'like', '%' . $this->search . '%')
                    ->orWhere('id', 'like', '%' . $this->search . '%');
            });
        }

        // Apply date filters
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

        // Apply sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        $this->studySessions = $query->get();
    }
    protected function loadMakeupSessions()
    {
        $this->makeupSessions = MakeupSession::where('student_id', $this->studentId)
            ->with(['ccTopic.chapter.subject'])
            ->orderByDesc('created_at')
            ->get();
    }
    public function updatedSearch()
    {
        $this->loadStudySessions();
    }
    public function updatedSessionType()
    {
        $this->loadStudySessions();
    }

    public function updatedDateFrom()
    {
        $this->loadStudySessions();
    }

    public function updatedDateTo()
    {
        $this->loadStudySessions();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
            $this->sessionType = 'all';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }

        $this->loadStudySessions();

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

        $this->loadStudySessions();
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
        // Logic for exporting to Excel
        session()->flash('success', 'فایل Excel در حال آماده‌سازی است...');

        // You can use Laravel Excel package here
        // return Excel::download(new StudySessionsExport($this->studentId), 'study-sessions.xlsx');
    }

    public function exportToPdf()
    {
        // Logic for exporting to PDF
        session()->flash('success', 'فایل PDF در حال آماده‌سازی است...');

        // You can use DomPDF package here
        // $pdf = PDF::loadView('admin.reports.study-sessions-pdf', [
        //     'student' => $this->studentName,
        //     'sessions' => $this->studySessions,
        //     'studyTime' => $this->studyTime
        // ]);
        // return $pdf->download('study-sessions.pdf');
    }

    public function deleteSession($sessionId)
    {
        try {
            $session = StudySession::findOrFail($sessionId);
            $session->delete();

            session()->flash('success', 'جلسه مطالعه با موفقیت حذف شد.');

            // Reload data
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
