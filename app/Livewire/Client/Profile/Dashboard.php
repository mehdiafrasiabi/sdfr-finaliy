<?php

namespace App\Livewire\Client\Profile;

use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\AdvisingSession;

use App\Models\NotificationRecipient;
use App\Models\WeeklyProgram;
use App\Models\DailyReport;
use App\Models\StudyPartSession;
use App\Models\ClassSchedule;

use Carbon\Carbon;

class Dashboard extends Component

{

    public $student;

    public $user;


    use SEOTools;


    public function mount()

    {

        $this->seoConfig();

        $this->user = Auth::user();

        $this->student = $this->user->student ?? null;

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
        $activeSession = $this->getActiveAdvisingSession();
        if (!$activeSession) {
            return null;
        }

        return WeeklyProgram::where('advising_session_id', $activeSession->id)->first();
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

        $advisorStudent = $this->student?->advisor;

        $unreadNotificationsCount = $this->getUnreadNotificationsCount();
        $todayProgram = $this->getTodayProgram();

        $studyHoursProgress = $this->getStudyHoursProgress();

        $reportProgress = $this->getReportProgress();
// برنامه کلاسی
        $classSchedule = null;
        if ($this->student) {
            $classSchedule = ClassSchedule::where('student_id', $this->student->id)
                ->where('is_finalized', true)
                ->with('parts')
                ->latest()
                ->first();
        }
        return view('livewire.client.profile.dashboard', [
            'advisorStudent' => $advisorStudent,
            'unreadNotificationsCount' => $unreadNotificationsCount,
            'todayProgram' => $todayProgram,
            'studyHoursProgress' => $studyHoursProgress,
            'reportProgress' => $reportProgress,
            'classSchedule' => $classSchedule,
        ])->layout('layouts.client.app');

    }

}

