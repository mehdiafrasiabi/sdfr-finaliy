<?php


namespace App\Livewire\Admin\Student;


use App\Models\DailyReport;

use App\Models\DailyReportPart;

use App\Models\Student;

use App\Services\NotificationService;

use Carbon\Carbon;

use Illuminate\Support\Facades\Validator;

use Livewire\Component;

use Livewire\WithPagination;

use Morilog\Jalali\Jalalian;


class ReportDaily extends Component

{

    use WithPagination;


    public $selectedReports = [];

    public $selectAll = false;

    public $studentsWithoutReports = [];


    // Comment Modal

    public bool $commentModalOpen = false;

    public ?int $commentReportId = null;

    public string $advisorCommentInput = '';

    public bool $advisorCommentReadonly = false;

    public string $commentStudentName = '';

    public ?string $commentStudentReply = null;


    // Detail Modal

    public bool $detailModalOpen = false;

    public ?int $selectedReportId = null;

    public array $reportPartsDetails = [];

    public array $selectedReportData = [];


    protected $paginationTheme = 'bootstrap';


    public function mount()

    {

        $this->loadStudentsWithoutReports();

    }


    protected function getYesterdayDate(): Carbon

    {

        return Carbon::yesterday();

    }


    protected function getYesterdayJalali(): string

    {

        return jdate($this->getYesterdayDate())->format('Y/m/d');

    }


    protected function getYesterdayDayName(): string

    {

        $dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];

        return $dayNames[jdate($this->getYesterdayDate())->getDayOfWeek()];

    }


    protected function loadStudentsWithoutReports()

    {

        $yesterday = $this->getYesterdayDate();


        $allStudents = Student::with('user')
            ->where(function ($query) {

                $query->where('supporter_id', auth()->id())
                    ->orWhere('advisor_id', auth()->id());

            })
            ->get();


        $studentsWithReports = DailyReport::where('admin_id', auth()->id())
            ->whereDate('report_date', $yesterday)
            ->pluck('student_id')
            ->toArray();


        $this->studentsWithoutReports = $allStudents
            ->whereNotIn('id', $studentsWithReports)
            ->map(function ($student) {

                return $student->user->name ?? null;

            })
            ->filter()
            ->values()
            ->toArray();

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

        $yesterday = $this->getYesterdayDate();


        return DailyReport::where('admin_id', auth()->id())
            ->whereDate('report_date', $yesterday)
            ->where('status', 'pending')
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


        DailyReport::whereIn('id', $this->selectedReports)
            ->where('admin_id', auth()->id())
            ->update(['status' => $action]);


        // Send notifications

        $reports = DailyReport::with('student.user')
            ->whereIn('id', $this->selectedReports)
            ->get();


        $statusLabel = $action === 'approved' ? 'تایید' : 'رد';


        foreach ($reports as $report) {

            $studentName = $report->student?->user?->name ?? 'دانش آموز عزیز';


            NotificationService::sendToStudent(

                $report->student_id,

                'وضعیت گزارش روزانه',

                "{$studentName}، گزارش شما {$statusLabel} گردید."

            );

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


        $report = DailyReport::with('student.user')
            ->where('id', $reportId)
            ->where('admin_id', auth()->id())
            ->firstOrFail();


        $report->update(['status' => $value]);


        if ($value === 'approved') {

            $this->selectedReports = array_diff($this->selectedReports, [$reportId]);

        }


        if (in_array($value, ['approved', 'rejected'])) {

            $studentName = $report->student?->user?->name ?? 'دانش آموز عزیز';

            $statusLabel = $value === 'approved' ? 'تایید' : 'رد';


            NotificationService::sendToStudent(

                $report->student_id,

                'وضعیت گزارش روزانه',

                "{$studentName}، گزارش شما {$statusLabel} گردید."

            );

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

        $report = DailyReport::with('student.user')
            ->where('id', $reportId)
            ->where('admin_id', auth()->id())
            ->firstOrFail();


        $this->commentReportId = $reportId;

        $this->advisorCommentInput = $report->advisor_comment ?? '';

        $this->advisorCommentReadonly = !empty($report->advisor_comment);

        $this->commentStudentName = $report->student->user->name ?? '';

        $this->commentStudentReply = $report->student_reply;

        $this->commentModalOpen = true;

    }


    public function closeCommentModal()

    {

        $this->commentModalOpen = false;

        $this->advisorCommentInput = '';

        $this->commentReportId = null;

        $this->advisorCommentReadonly = false;

        $this->commentStudentReply = null;

        $this->commentStudentName = '';

        $this->resetErrorBag('advisorCommentInput');

    }


    public function saveAdvisorComment()

    {

        if (!$this->commentReportId) return;


        $report = DailyReport::where('id', $this->commentReportId)
            ->where('admin_id', auth()->id())
            ->firstOrFail();


        if (!empty($report->advisor_comment)) {

            $this->dispatch('warning', 'برای این گزارش قبلاً نظری ثبت شده است.');

            $this->closeCommentModal();

            return;

        }


        $validated = $this->validate([

            'advisorCommentInput' => 'required|string|max:1000',

        ], [

            'advisorCommentInput.required' => 'متن نظر را وارد کنید.',

            'advisorCommentInput.max' => 'طول نظر نمی‌تواند بیشتر از ۱۰۰۰ کاراکتر باشد.',

        ]);


        $report->update([

            'advisor_comment' => $validated['advisorCommentInput'],

            'advisor_commented_at' => now(),

        ]);


        $this->dispatch('success', 'نظر شما ثبت شد.');

        $this->closeCommentModal();

    }


    public function openDetailModal(int $reportId)

    {

        $report = DailyReport::with([

            'student.user',

            'weeklyProgram',

            'reportParts.programPart.ccSubject',

            'reportParts.programPart.ccTopic',

        ])
            ->where('id', $reportId)
            ->where('admin_id', auth()->id())
            ->firstOrFail();


        $this->selectedReportId = $reportId;


        // Store report data as array

        $dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];

        $this->selectedReportData = [

            'student_name' => $report->student->user->name ?? 'نامشخص',

            'report_date' => jdate($report->report_date)->format('Y/m/d'),

            'day_name' => $dayNames[$report->day_of_week] ?? '-',

            'phone_hours' => $report->phone_hours,

            'description' => $report->description,

            'rating' => $report->rating,

            'rating_label' => DailyReport::RATINGS[$report->rating] ?? 'نامشخص',

            'is_compensatory' => $report->is_compensatory,

            'status' => $report->status,

        ];


        // Get program parts for this day

        $dayOfWeek = $report->day_of_week;

        $programParts = $report->weeklyProgram
            ->parts()
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('part_order')
            ->get();


        // Map report parts

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

                'topic_name' => $programPart->ccTopic->name ?? null,

                'duration_minutes' => $programPart->duration_minutes,

                'is_read' => $isRead,

                'tests_done' => $testsDone,

                'test_count' => $testCount,

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

        $yesterday = $this->getYesterdayDate();


        $reports = DailyReport::with([

            'student.user',

            'weeklyProgram',

            'reportParts.programPart',

        ])
            ->where('admin_id', auth()->id())
            ->whereDate('report_date', $yesterday)
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);


        $this->loadStudentsWithoutReports();


        return view('livewire.admin.student.report-daily', [

            'reports' => $reports,

            'yesterdayJalali' => $this->getYesterdayJalali(),

            'yesterdayDayName' => $this->getYesterdayDayName(),

            'studentsWithoutReports' => $this->studentsWithoutReports,

        ])->layout('layouts.admin.app');

    }

}
