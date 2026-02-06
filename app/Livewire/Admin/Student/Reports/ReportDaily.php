<?php

namespace App\Livewire\Admin\Student\Reports;

use App\Models\DailyReport;
use App\Models\DailyReportDetail;
use App\Models\DailyReportFeedback;
use App\Models\Student;
use App\Models\WeeklyProgram;
use App\Models\WeeklyProgramRestDay;
use App\Models\AdvisingSession;
use App\Models\PersonalInformation;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class ReportDaily extends Component
{
    use WithPagination;

    const REPORT_CUTOFF_HOUR = 6;

    public $selectedReports = [];
    public $selectAll = false;
    public $studentsWithoutReports = [];
    public $studentsOnRestDay = [];

    // Comment Modal
    public bool $commentModalOpen = false;
    public ?int $commentReportId = null;
    public string $advisorCommentInput = '';
    public string $commentStatusInput = 'pending';
    public bool $advisorCommentReadonly = false;
    public string $commentStudentName = '';
    public ?string $commentStudentReply = null;

    // Detail Modal
    public bool $detailModalOpen = false;
    public ?int $selectedReportId = null;
    public array $reportPartsDetails = [];
    public array $selectedReportData = [];

    // Student Info Modal
    public bool $studentInfoModalOpen = false;
    public string $studentInfoModalTitle = '';
    public array $studentInfoList = [];

    // All unconfirmed reports
    public bool $allReportsModalOpen = false;
    public bool $allReportsConfirmed = false;
    public array $allUnconfirmedReports = [];

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->loadStudentsWithoutReports();
    }

    protected function getReportDate(): Carbon
    {
        $now = Carbon::now();
        if ($now->hour < self::REPORT_CUTOFF_HOUR) {
            return Carbon::today()->subDays(2);
        }
        return Carbon::yesterday();
    }

    protected function getReportTimeRange(): array
    {
        $reportDate = $this->getReportDate();
        return [
            'start' => $reportDate->copy()->startOfDay(),
            'end' => $reportDate->copy()->addDay()->setHour(self::REPORT_CUTOFF_HOUR)->setMinute(0)->setSecond(0),
        ];
    }

    protected function getReportDateJalali(): string
    {
        return jdate($this->getReportDate())->format('Y/m/d');
    }

    protected function getReportDateDayName(): string
    {
        $dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
        return $dayNames[jdate($this->getReportDate())->getDayOfWeek()];
    }

    /**
     * ✅ منطق اصلاح شده: فقط دانش‌آموزانی که جلسه برگزار شده و برنامه فعال دارند
     */
    protected function loadStudentsWithoutReports()
    {
        $reportDate = $this->getReportDate();
        $today = Carbon::today();

        // ✅ دانش‌آموزانی که جلسه برگزار شده و برنامه فعال دارند
        $eligibleStudents = Student::with(['user.personalInformation'])
            ->where(function ($query) {
                $query->where('supporter_id', auth()->id())
                    ->orWhere('advisor_id', auth()->id());
            })
            ->whereHas('advisingSessions', function ($query) {
                // ✅ جلسه باید completed باشد و result_status باید held باشد
                $query->where('status', 'completed')
                    ->where('result_status', 'held');
            })
            ->whereHas('weeklyPrograms', function ($query) use ($reportDate, $today) {
                // ✅ برنامه باید is_active = true باشد
                // ✅ و تاریخ گزارش باید بین start_date و end_date باشد
                $query->where('is_active', true)
                    ->where('start_date', '<=', $reportDate)
                    ->where('end_date', '>=', $reportDate);
            })
            ->get();

        // دانش‌آموزانی که گزارش داده‌اند
        $studentsWithReports = DailyReport::where('admin_id', auth()->id())
            ->whereDate('report_date', $reportDate)
            ->pluck('student_id')
            ->toArray();

        $studentsOnRestDay = [];
        $studentsWithoutReportsFiltered = [];

        foreach ($eligibleStudents->whereNotIn('id', $studentsWithReports) as $student) {
            $user = $student->user;
            $personalInfo = $user?->personalInformation;

            if (!$user || !$personalInfo) continue;

            $studentData = [
                'name' => $user->name,
                'grade' => $personalInfo->grade ?? '-',
                'field' => $this->getFieldLabel($personalInfo->field ?? ''),
                'mobile' => $user->mobile ?? '-',
                'father_mobile' => $personalInfo->father_mobile ?? '-',
                'mother_mobile' => $personalInfo->mother_mobile ?? '-',
            ];

            // بررسی روز استراحت
            if ($this->isStudentRestDay($student->id, $reportDate)) {
                $studentsOnRestDay[] = $studentData;
            } else {
                $studentsWithoutReportsFiltered[] = $studentData;
            }
        }

        $this->studentsWithoutReports = $studentsWithoutReportsFiltered;
        $this->studentsOnRestDay = $studentsOnRestDay;
    }

    protected function getFieldLabel(string $field): string
    {
        return match ($field) {
            'math' => 'ریاضی',
            'experimental' => 'تجربی',
            'human' => 'انسانی',
            default => '-',
        };
    }

    protected function isStudentRestDay(int $studentId, Carbon $date): bool
    {
        $program = WeeklyProgram::where('student_id', $studentId)
            ->where('is_active', true)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->latest('start_date')
            ->first();

        if (!$program) {
            return false;
        }

        $startDate = Carbon::parse($program->start_date);
        $dayIndex = $startDate->diffInDays($date);

        if ($dayIndex < 0 || $dayIndex > 7) {
            return false;
        }

        return WeeklyProgramRestDay::where('weekly_program_id', $program->id)
            ->where('day_index', $dayIndex)
            ->exists();
    }

    private function resetSelection()
    {
        $this->selectedReports = [];
        $this->selectAll = false;
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $currentPageIds = $this->getCurrentPageReportIds();
            $this->selectedReports = array_unique(array_merge($this->selectedReports, $currentPageIds));
        } else {
            $currentPageIds = $this->getCurrentPageReportIds();
            $this->selectedReports = array_diff($this->selectedReports, $currentPageIds);
        }
    }

    public function updatedSelectedReports()
    {
        $currentPageIds = $this->getCurrentPageReportIds();

        if (empty($currentPageIds)) {
            $this->selectAll = false;
            return;
        }

        $selectedInCurrentPage = array_intersect($this->selectedReports, $currentPageIds);
        $this->selectAll = count($selectedInCurrentPage) === count($currentPageIds);
    }

    private function getCurrentPageReportIds()
    {
        $reportDate = $this->getReportDate();

        return DailyReport::where('admin_id', auth()->id())
            ->whereDate('report_date', $reportDate)
            ->whereHas('detail', fn($q) => $q->where('status', 'pending'))
            ->latest()
            ->paginate(10)
            ->pluck('id')
            ->toArray();
    }

    public function updatingPage()
    {
        $this->selectAll = false;
    }

    public function bulkAction($action)
    {
        if (empty($this->selectedReports)) {
            $this->dispatch('warning', 'هیچ گزارشی انتخاب نشده است.');
            return;
        }

        if (!in_array($action, ['approved', 'rejected'])) {
            $this->dispatch('warning', 'عملیات نامعتبر است.');
            return;
        }

        DailyReportDetail::whereIn('daily_report_id', $this->selectedReports)->update(['status' => $action]);

        $reports = DailyReport::with('student.user')
            ->whereIn('id', $this->selectedReports)
            ->get();

        foreach ($reports as $report) {
            $studentName = $report->student?->user?->name ?? 'دانش آموز';
            $reportDate = jdate($report->report_date)->format('Y/m/d');

            if ($action === 'approved') {
                $message = "{$studentName} عزیز\nگزارش مطالعه شما در تاریخ {$reportDate} تایید شد. به همین روند ادامه بده!\nبا تشکر";
                $title = 'تایید گزارش روزانه';
            } else {
                $message = "{$studentName} عزیز\nگزارش مطالعه شما در تاریخ {$reportDate} رد شد. لطفاً گزارش را بررسی و اصلاح کنید.\nبا تشکر";
                $title = 'رد گزارش روزانه';
            }

            NotificationService::sendToStudent($report->student_id, $title, $message);
        }

        $this->resetSelection();
        $this->dispatch('success', 'عملیات گروهی با موفقیت انجام شد.');
    }

    public function changeStatus($reportId, $value)
    {
        $validator = Validator::make(['status' => $value, 'id' => $reportId], [
            'id' => 'required|exists:daily_reports,id',
            'status' => 'required|in:pending,approved,rejected'
        ], [
            '*.required' => 'فیلد اجباری است.',
            'status.in' => 'وضعیت نامعتبر است.',
            'id.exists' => 'گزارش یافت نشد.'
        ]);

        $validator->validate();
        $this->resetValidation();

        $report = DailyReport::with(['student.user', 'detail'])
            ->where('id', $reportId)
            ->where('admin_id', auth()->id())
            ->firstOrFail();

        $report->detail->update(['status' => $value]);

        if ($value === 'approved') {
            $this->selectedReports = array_diff($this->selectedReports, [$reportId]);
        }

        if (in_array($value, ['approved', 'rejected'])) {
            $studentName = $report->student?->user?->name ?? 'دانش آموز';
            $reportDate = jdate($report->report_date)->format('Y/m/d');

            if ($value === 'approved') {
                $message = "{$studentName} عزیز\nگزارش مطالعه شما در تاریخ {$reportDate} تایید شد. به همین روند ادامه بده!\nبا تشکر";
                $title = 'تایید گزارش روزانه';
            } else {
                $message = "{$studentName} عزیز\nگزارش مطالعه شما در تاریخ {$reportDate} رد شد. لطفاً گزارش را بررسی و اصلاح کنید.\nبا تشکر";
                $title = 'رد گزارش روزانه';
            }

            NotificationService::sendToStudent($report->student_id, $title, $message);
        }

        $this->dispatch('success', 'وضعیت با موفقیت تغییر کرد.');
    }

    public function delete($reportId)
    {
        DailyReport::query()
            ->where('id', $reportId)
            ->where('admin_id', auth()->id())
            ->delete();

        $this->selectedReports = array_diff($this->selectedReports, [$reportId]);
        $this->dispatch('success', 'گزارش با موفقیت حذف شد.');
    }

    public function openCommentModal(int $reportId)
    {
        $report = DailyReport::with(['student.user', 'feedback', 'detail'])
            ->where('id', $reportId)
            ->where('admin_id', auth()->id())
            ->firstOrFail();

        $this->commentReportId = $reportId;
        $this->advisorCommentInput = $report->feedback->advisor_comment ?? '';
        $this->commentStatusInput = $report->detail->status ?? 'pending';
        $this->advisorCommentReadonly = !empty($report->feedback->advisor_comment);
        $this->commentStudentName = $report->student->user->name ?? '';
        $this->commentStudentReply = $report->feedback->student_reply;
        $this->commentModalOpen = true;
    }

    public function closeCommentModal()
    {
        $this->commentModalOpen = false;
        $this->advisorCommentInput = '';
        $this->commentStatusInput = 'pending';
        $this->commentReportId = null;
        $this->advisorCommentReadonly = false;
        $this->commentStudentReply = null;
        $this->commentStudentName = '';
        $this->resetErrorBag(['advisorCommentInput', 'commentStatusInput']);
    }

    public function saveAdvisorComment()
    {
        if (!$this->commentReportId) return;

        $report = DailyReport::with(['feedback', 'detail', 'student.user'])
            ->where('id', $this->commentReportId)
            ->where('admin_id', auth()->id())
            ->firstOrFail();

        if (!empty($report->feedback->advisor_comment)) {
            $this->dispatch('warning', 'برای این گزارش قبلاً نظری ثبت شده است.');
            $this->closeCommentModal();
            return;
        }

        $validated = $this->validate([
            'advisorCommentInput' => 'nullable|string|max:1000',
            'commentStatusInput' => 'required|in:pending,approved,rejected',
        ], [
            'advisorCommentInput.max' => 'طول نظر نمی‌تواند بیشتر از ۱۰۰۰ کاراکتر باشد.',
            'commentStatusInput.required' => 'انتخاب وضعیت الزامی است.',
            'commentStatusInput.in' => 'وضعیت نامعتبر است.',
        ]);

        if (!empty($validated['advisorCommentInput'])) {
            $report->feedback->update([
                'advisor_comment' => $validated['advisorCommentInput'],
                'advisor_commented_at' => now(),
            ]);
        }

        $report->detail->update([
            'status' => $validated['commentStatusInput'],
        ]);

        if (in_array($validated['commentStatusInput'], ['approved', 'rejected'])) {
            $studentName = $report->student?->user?->name ?? 'دانش آموز';
            $reportDate = jdate($report->report_date)->format('Y/m/d');

            if ($validated['commentStatusInput'] === 'approved') {
                $message = "{$studentName} عزیز\nگزارش مطالعه شما در تاریخ {$reportDate} تایید شد. به همین روند ادامه بده!\nبا تشکر";
                $title = 'تایید گزارش روزانه';
            } else {
                $message = "{$studentName} عزیز\nگزارش مطالعه شما در تاریخ {$reportDate} رد شد. لطفاً گزارش را بررسی و اصلاح کنید.\nبا تشکر";
                $title = 'رد گزارش روزانه';
            }

            NotificationService::sendToStudent($report->student_id, $title, $message);
        }

        $this->dispatch('success', 'نظر و وضعیت با موفقیت ثبت شد.');
        $this->closeCommentModal();
    }

    public function openDetailModal(int $reportId)
    {
        $report = DailyReport::with([
            'student.user.personalInformation', // ✅ اضافه شد
            'weeklyProgram',
            'reportParts.programPart.ccSubject',
            'reportParts.programPart.ccTopic',
            'reportParts.programPart.ccChapter', // ✅ اضافه شد
            'detail',
            'feedback',
        ])
            ->where('id', $reportId)
            ->where('admin_id', auth()->id())
            ->firstOrFail();

        $this->selectedReportId = $reportId;

        $dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];

        // ✅ اطلاعات دانش‌آموز از personal_information
        $personalInfo = $report->student->user->personalInformation;

        $this->selectedReportData = [
            'student_name' => $report->student->user->name ?? 'نامشخص',
            'student_grade' => $personalInfo->grade ?? '-', // ✅ اضافه شد
            'student_field' => $this->getFieldLabel($personalInfo->field ?? ''), // ✅ اضافه شد
            'report_date' => jdate($report->report_date)->format('Y/m/d'),
            'day_name' => $dayNames[$report->day_of_week] ?? '-',
            'phone_hours' => $report->detail->phone_hours ?? 0,
            'description' => $report->detail->description ?? '',
            'rating' => $report->detail->rating ?? 0,
            'rating_label' => DailyReport::RATINGS[$report->detail->rating ?? 0] ?? 'نامشخص',
            'is_compensatory' => $report->is_compensatory,
            'status' => $report->detail->status ?? 'pending',
            'advisor_comment' => $report->feedback->advisor_comment ?? '',
            'student_reply' => $report->feedback->student_reply ?? '',
            'created_at' => $report->created_at?->format('Y/m/d H:i'), // ✅ فرمت کامل
        ];

        // ✅ گرفتن پارت‌های برنامه برای این روز
        $dayOfWeek = $report->day_of_week;
        $programParts = $report->weeklyProgram
            ->parts()
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('part_order')
            ->get();

        // ✅ Map کردن پارت‌های گزارش شده
        $reportPartsMap = $report->reportParts->keyBy('program_part_id');

        $this->reportPartsDetails = [];
        $totalTests = 0;
        $doneTests = 0;
        $totalParts = 0;
        $readParts = 0;

        foreach ($programParts as $programPart) {
            $reportPart = $reportPartsMap->get($programPart->id);
            $isRead = $reportPart?->is_read ?? false;
            $testsDone = $reportPart?->tests_done ?? 0;
            $testCount = $programPart->test_count ?? 0;

            $totalParts++;
            if ($isRead) $readParts++;
            $totalTests += $testCount;
            $doneTests += $testsDone;

            $this->reportPartsDetails[] = [
                'id' => $programPart->id,
                'lesson_name' => $programPart->lesson_name,
                'subject_name' => $programPart->ccSubject->name ?? null,
                'chapter_name' => $programPart->ccChapter->name ?? null, // ✅ اضافه شد
                'topic_name' => $programPart->ccTopic->name ?? null,
                'duration_minutes' => $programPart->duration_minutes,
                'is_read' => $isRead,
                'tests_done' => $testsDone,
                'test_count' => $testCount,
                'part_rating' => $reportPart?->part_rating ?? null,
                'is_compensatory' => $reportPart?->is_compensatory ?? false,
            ];
        }

        $this->selectedReportData['total_parts'] = $totalParts;
        $this->selectedReportData['read_parts'] = $readParts;
        $this->selectedReportData['unread_parts'] = $totalParts - $readParts;
        $this->selectedReportData['total_tests'] = $totalTests;
        $this->selectedReportData['done_tests'] = $doneTests;
        $this->selectedReportData['undone_tests'] = $totalTests - $doneTests;

        $this->detailModalOpen = true;
    }

    public function closeDetailModal()
    {
        $this->detailModalOpen = false;
        $this->selectedReportId = null;
        $this->reportPartsDetails = [];
        $this->selectedReportData = [];
    }

    public function openStudentInfoModal(string $type)
    {
        if ($type === 'rest') {
            $this->studentInfoModalTitle = 'دانش‌آموزان با روز استراحت';
            $this->studentInfoList = $this->studentsOnRestDay;
        } else {
            $this->studentInfoModalTitle = 'دانش‌آموزان بدون گزارش';
            $this->studentInfoList = $this->studentsWithoutReports;
        }
        $this->studentInfoModalOpen = true;
    }

    public function closeStudentInfoModal()
    {
        $this->studentInfoModalOpen = false;
        $this->studentInfoList = [];
        $this->studentInfoModalTitle = '';
    }

    public function showAllReportsConfirmation()
    {
        $this->allReportsModalOpen = true;
        $this->allReportsConfirmed = false;
        $this->allUnconfirmedReports = [];
    }

    public function confirmLoadAllReports()
    {
        $this->allUnconfirmedReports = DailyReport::with([
            'student.user',
            'detail',
            'reportParts.programPart',
            'feedback',
        ])
            ->where('admin_id', auth()->id())
            ->whereHas('detail', fn($q) => $q->where('status', 'pending'))
            ->latest()
            ->get()
            ->map(function ($report) {
                $readParts = $report->reportParts->where('is_read', true)->count();
                $totalParts = $report->reportParts->count();
                $totalTests = $report->reportParts->sum(fn($p) => $p->programPart?->test_count ?? 0);
                $doneTests = $report->reportParts->sum('tests_done');

                return [
                    'id' => $report->id,
                    'student_name' => $report->student->user->name ?? '-',
                    'report_date' => jdate($report->report_date)->format('Y/m/d'),
                    'day_name' => $report->day_name,
                    'read_parts' => $readParts,
                    'total_parts' => $totalParts,
                    'done_tests' => $doneTests,
                    'total_tests' => $totalTests,
                    'phone_hours' => $report->detail->phone_hours ?? 0,
                    'rating' => $report->detail->rating ?? 0,
                    'rating_label' => DailyReport::RATINGS[$report->detail->rating ?? 0] ?? '-',
                    'is_compensatory' => $report->is_compensatory,
                    'description' => $report->detail->description ?? '',
                    'created_at' => $report->created_at?->format('Y/m/d H:i'),
                ];
            })
            ->toArray();

        $this->allReportsConfirmed = true;
    }

    public function closeAllReportsModal()
    {
        $this->allReportsModalOpen = false;
        $this->allReportsConfirmed = false;
        $this->allUnconfirmedReports = [];
    }

    public function getStatusColor($status): string
    {
        return match ($status) {
            'pending' => 'primary',
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }

    public function getRatingLabel(int $rating): string
    {
        return DailyReport::RATINGS[$rating] ?? 'نامشخص';
    }

    public function render()
    {
        $reportDate = $this->getReportDate();
        $reports = DailyReport::with([
            'student.user',
            'weeklyProgram',
            'reportParts.programPart',
            'detail',
            'feedback',
        ])
            ->where('admin_id', auth()->id())
            ->whereDate('report_date', $reportDate)
            ->whereHas('detail', fn($q) => $q->where('status', 'pending'))
            ->latest()
            ->paginate(10);

        $this->loadStudentsWithoutReports();

        return view('livewire.admin.student.reports.report-daily', [
            'reports' => $reports,
            'reportDateJalali' => $this->getReportDateJalali(),
            'reportDateDayName' => $this->getReportDateDayName(),
            'studentsWithoutReports' => $this->studentsWithoutReports,
            'studentsOnRestDay' => $this->studentsOnRestDay,
        ])->layout('layouts.admin.app');
    }
}
