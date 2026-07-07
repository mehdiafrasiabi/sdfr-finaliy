<?php

namespace App\Livewire\Admin\Student\StudySession;

use App\Models\DailyReportPart;
use App\Models\ProgramPart;
use App\Models\Student;
use App\Models\StudyPartSession;
use App\Models\SessionFeedback;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\admin\StudySessionSummaryExport;
use Illuminate\Database\Eloquent\Builder;
use Morilog\Jalali\Jalalian;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination,SEOTools;

    public $search = '';
    public bool $exportModalOpen = false;
    public string $exportTarget = 'all';
    public string $exportMode = 'date_range';
    public string $exportStartDate = '';
    public string $exportEndDate = '';
    public array $selectedStudentIds = [];
    public array $studentTotalDisplays = [];
    public array $dashboardData = [];

    public function mount()
    {
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()->setTitle('ساعت مطالعه دانش آموزان');
    }

    private function formatSecondsToHours(int $seconds): float
    {
        return round($seconds / 3600, 2);
    }

    protected function calculateDashboardData(Builder $studentsQuery): void
    {
        $studentIds = $studentsQuery->pluck('id')->toArray();
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        // 1. ساعت مطالعه کل ثبت شده (برنامه‌ریزی شده)
        $totalPlannedSeconds = ProgramPart::whereHas('weeklyProgram', function ($q) use ($studentIds) {
            $q->whereIn('student_id', $studentIds);
        })->sum('duration_minutes') * 60;

        // 2. ساعت مطالعه کل خوانده شده تا الان
        $totalStudiedSeconds = StudyPartSession::whereIn('student_id', $studentIds)->sum('duration_seconds');

        // 4. کل ساعت مطالعه جبرانی (از مدل MakeupSession)
        $totalCompensatorySeconds = \App\Models\MakeupSession::whereIn('student_id', $studentIds)->sum('duration_seconds');

        // 5. میانگین امتیازات هر پارت ثبت شده
        $averagePartRating = SessionFeedback::whereIn('student_id', $studentIds)->avg('rating');

        // 6. تعداد کل پارت های برنامه ریزی شده -> انجام شده / انجام نشده
        $totalPartsPlanned = ProgramPart::whereHas('weeklyProgram', function ($q) use ($studentIds) {
            $q->whereIn('student_id', $studentIds);
        })->count();
        $studiedPartIds = StudyPartSession::whereIn('student_id', $studentIds)->where('duration_seconds', '>', 0)->pluck('program_part_id')->unique();
        $totalPartsDone = $studiedPartIds->count();
        $totalPartsNotDone = $totalPartsPlanned - $totalPartsDone;

        // 7. حذف شده - این آمار قابل محاسبه نبود
        // $extraOrganizationalSeconds = \App\Models\MakeupSession::whereIn('student_id', $studentIds)->where('source', 'organizational')->sum('duration_seconds');

        // 8. میانگین مطالعه دانش آموزان در روز
        $totalDays = $thirtyDaysAgo->diffInDays(Carbon::now());
        $averageDailyStudySeconds = ($totalStudents = count($studentIds)) > 0 && $totalDays > 0
            ? $totalStudiedSeconds / $totalDays / $totalStudents
            : 0;

        // 3 & 9 & 10. بهترین/بدترین دانش آموزان, پارت های تقلب, دروس فراری
        $bestPerformers = [];
        $worstPerformers = [];
        $cheatedPartsDetails = [];
        $allFugitiveLessons = [];

        $studentsData = Student::with('user.personalInformation')->whereIn('id', $studentIds)->get();

        foreach ($studentsData as $student) {
            $planned = ProgramPart::whereHas('weeklyProgram', function($q) use ($student) {
                $q->where('student_id', $student->id);
            })->where('created_at', '>=', $thirtyDaysAgo)->sum('duration_minutes') * 60;

            $studied = StudyPartSession::where('student_id', $student->id)->where('created_at', '>=', $thirtyDaysAgo)->sum('duration_seconds');
            $completionRate = $planned > 0 ? ($studied / $planned) * 100 : 0;

            if ($completionRate > 80) $bestPerformers[] = $student;
            if ($completionRate < 30) $worstPerformers[] = $student;

            // 9. پارت های تقلب
            $studentCheatedParts = DailyReportPart::whereHas('dailyReport', function($q) use ($student) {
                $q->where('student_id', $student->id);
            })
                ->where('is_read', true)
                ->whereDoesntHave('programPart.studyPartSessions', function (Builder $q) {
                    $q->where('duration_seconds', '>', 0);
                })
                ->with('programPart.ccSubject')
                ->get();

            if ($studentCheatedParts->isNotEmpty()) {
                $cheatedPartsDetails[] = [
                    'student_name' => $student->user?->personalInformation?->name ?? $student->user->name,
                    'count' => $studentCheatedParts->count(),
                ];
            }

            // 10. دروس فراری
            $studentPlannedParts = ProgramPart::whereHas('weeklyProgram', function($q) use ($student) {
                $q->where('student_id', $student->id);
            })->where('created_at', '>=', $thirtyDaysAgo)->with('ccSubject')->get();

            $studiedPartIdsForStudent = StudyPartSession::where('student_id', $student->id)->where('created_at', '>=', $thirtyDaysAgo)->pluck('program_part_id');

            $lessons = $studentPlannedParts->groupBy('cc_subject_id');
            foreach ($lessons as $subjectId => $parts) {
                if ($parts->pluck('id')->intersect($studiedPartIdsForStudent)->isEmpty()) {
                     $lessonName = $parts->first()->ccSubject?->name ?? 'درس نامشخص';
                     if (!isset($allFugitiveLessons[$lessonName])) $allFugitiveLessons[$lessonName] = 0;
                     $allFugitiveLessons[$lessonName]++;
                }
            }
        }
        arsort($allFugitiveLessons);

        // 11. تعداد تست در نظر گرفته -> انجام شده / انجام نشده
        $totalTestsPlanned = ProgramPart::whereHas('weeklyProgram', function ($q) use ($studentIds) {
            $q->whereIn('student_id', $studentIds);
        })->sum('test_count');

        $totalTestsDone = DailyReportPart::whereHas('dailyReport', function($q) use ($studentIds){
            $q->whereIn('student_id', $studentIds);
        })->sum('tests_done');

        $totalTestsNotDone = $totalTestsPlanned - $totalTestsDone;

        $this->dashboardData = [
            'totalPlannedHours' => $this->formatSecondsToHours($totalPlannedSeconds),
            'totalStudiedHours' => $this->formatSecondsToHours($totalStudiedSeconds),
            'bestPerformers' => $bestPerformers,
            'worstPerformers' => $worstPerformers,
            'totalCompensatoryHours' => $this->formatSecondsToHours($totalCompensatorySeconds),
            'averagePartRating' => round($averagePartRating, 2),
            'totalPartsPlanned' => $totalPartsPlanned,
            'totalPartsDone' => $totalPartsDone,
            'totalPartsNotDone' => $totalPartsNotDone,
            'averageDailyStudyHours' => $this->formatSecondsToHours($averageDailyStudySeconds),
            'cheatedPartsDetails' => $cheatedPartsDetails,
            'fugitiveLessons' => $allFugitiveLessons,
            'totalTestsPlanned' => (int)$totalTestsPlanned,
            'totalTestsDone' => (int)$totalTestsDone,
            'totalTestsNotDone' => (int)($totalTestsPlanned - $totalTestsDone),
        ];
    }


    public function openExportModal(): void
    {
        $this->resetExportForm();
        $this->exportModalOpen = true;
    }

    public function closeExportModal(): void
    {
        $this->exportModalOpen = false;
        $this->resetExportForm();
    }

    public function updatedExportTarget($value): void
    {
        if ($value === 'all') {
            $this->selectedStudentIds = [];
        }
    }

    public function exportExcel()
    {
        $rules = [
            'exportTarget' => 'required|in:all,selected',
            'exportMode' => 'required|in:date_range,last_program',

        ];
        if ($this->exportMode === 'date_range') {
            $rules['exportStartDate'] = 'required|string';
            $rules['exportEndDate'] = 'required|string';
        }
        if ($this->exportTarget === 'selected') {
            $rules['selectedStudentIds'] = 'required|array|min:1';
        }

        $this->validate($rules, [
            'selectedStudentIds.required' => 'حداقل یک دانش‌آموز را انتخاب کنید.',
            'selectedStudentIds.min' => 'حداقل یک دانش‌آموز را انتخاب کنید.',
            'exportStartDate.required' => 'تاریخ شروع را وارد کنید.',
            'exportEndDate.required' => 'تاریخ پایان را وارد کنید.',
        ]);

        $startDate = null;
        $endDate = null;

        if ($this->exportMode === 'date_range') {
            try {
                $startDate = Jalalian::fromFormat('Y/m/d', $this->exportStartDate)->toCarbon()->startOfDay();
                $endDate = Jalalian::fromFormat('Y/m/d', $this->exportEndDate)->toCarbon()->endOfDay();
            } catch (\Throwable) {
                $this->addError('exportStartDate', 'فرمت تاریخ صحیح نیست. مثال: 1404/09/10');
                return;
            }

            if ($startDate->gt($endDate)) {
                $this->addError('exportStartDate', 'تاریخ شروع نباید بعد از تاریخ پایان باشد.');
                return;
            }
        }

        $studentIds = $this->exportTarget === 'all' ? null : array_map('intval', $this->selectedStudentIds);
        $this->closeExportModal();

        return Excel::download(
            new StudySessionSummaryExport(auth()->id(), $startDate, $endDate, $studentIds),
            'study_session_summary_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    protected function resetExportForm(): void
    {
        $this->exportTarget = 'all';
        $this->exportStartDate = '';
        $this->exportEndDate = '';
        $this->selectedStudentIds = [];
        $this->resetErrorBag(['selectedStudentIds', 'exportStartDate', 'exportEndDate']);
    }

    protected function studentsBaseQuery(int $adminId): Builder
    {
        $query = Student::query()
            ->with([
                'user.personalInformation',
                'user.profile',
                'advisor',
            ]);
        $admin = auth('admin')->user();
        if ($admin?->hasRole('school-manager') && $admin->school_id) {
            return $query->where('school_id', $admin->school_id);
        }

        return $query->where('advisor_id', $adminId);
    }
    public function formatHourMinute(?int $seconds): string
    {
        if (is_null($seconds) || $seconds <= 0) {
            return '00:00';
        }

        return sprintf('%02d:%02d', floor($seconds / 3600), floor(($seconds % 3600) / 60));
    }
    public function render()
    {
        $adminId = auth()->id();
        $baseStudentsQuery = $this->studentsBaseQuery($adminId);

        $this->calculateDashboardData(clone $baseStudentsQuery);

        $studentsQuery = $baseStudentsQuery;
        if ($this->search) {
            $studentsQuery->whereHas('user.personalInformation', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }
        $students = $studentsQuery->paginate(10);
        $studentIds = $students->pluck('id')->all();
        $regularSums = \App\Models\StudySession::query()
            ->selectRaw('student_id, COALESCE(SUM(duration_seconds),0) as total_seconds')
            ->whereIn('student_id', $studentIds)
            ->groupBy('student_id')
            ->pluck('total_seconds', 'student_id');

        $extraSums = \App\Models\MakeupSession::query()
            ->selectRaw('student_id, COALESCE(SUM(duration_seconds),0) as total_seconds')
            ->whereIn('student_id', $studentIds)
            ->groupBy('student_id')
            ->pluck('total_seconds', 'student_id');

        $this->studentTotalDisplays = [];
        foreach ($studentIds as $sid) {
            $regular = (int) ($regularSums[$sid] ?? 0);
            $extra = (int) ($extraSums[$sid] ?? 0);
            $this->studentTotalDisplays[$sid] = $this->formatHourMinute($regular) . '+' . $this->formatHourMinute($extra);
        }

        $exportStudents = $this->studentsBaseQuery($adminId)
            ->select(['id', 'user_id'])
            ->with(['user.personalInformation'])
            ->get();

        return view('livewire.admin.student.study-session.index', [
            'students' => $students,
            'exportStudents' => $exportStudents,
        ])->layout('layouts.admin.app');
    }
}
