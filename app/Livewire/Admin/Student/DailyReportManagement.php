<?php


namespace App\Livewire\Admin\Student;

use App\Exports\DailyReportsExport;
use App\Models\DailyReport;
use App\Models\Student;
use App\Models\ReportComment;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Morilog\Jalali\Jalalian;
use Maatwebsite\Excel\Facades\Excel;

class DailyReportManagement extends Component

{

    use WithPagination;


    // Multi-select

    public $selectedReports = [];

    public $selectAll = false;


    // Modal نظر مشاور

    public bool $commentModalOpen = false;

    public ?int $commentReportId = null;

    public string $advisorCommentInput = '';

    public bool $advisorCommentReadonly = false;

    public string $commentStudentName = '';

    public ?string $commentStudentReply = null;


    public function mount()

    {

        // فقط نمایش گزارش‌های روز قبل در انتظار

    }

// خروجی اکسل

    public function exportExcel()

    {

        $yesterday = $this->getYesterdayDate();

        $jalaliDate = Jalalian::fromCarbon($yesterday)->format('Y-m-d');


        $fileName = "daily_reports_{$jalaliDate}_" . now()->format('Ymd_His') . ".xlsx";


        return Excel::download(

            new DailyReportsExport($yesterday, auth()->id()),

            $fileName

        );

    }

    // تاریخ روز قبل (یک روز عقب‌تر از امروز)

    protected function getYesterdayDate()

    {

        return Carbon::yesterday();

    }


    // وقتی چک‌باکس "انتخاب همه" تغییر می‌کند

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


    // وقتی یک چک‌باکس تکی تغییر می‌کند

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


    // گرفتن آی‌دی‌های صفحه فعلی

    private function getCurrentPageReportIds()

    {

        $yesterday = $this->getYesterdayDate();


        $query = DailyReport::query()
            ->whereHas('advisingSession', function ($q) {

                $q->where('result_status', 'held');

            })
            ->whereDate('report_date', $yesterday->format('Y-m-d'))
            ->where('status', 'pending')
            ->whereHas('student', function ($q) {

                $q->where('supporter_id', auth()->id())
                    ->orWhere('advisor_id', auth()->id());

            });


        $reports = $query->latest()->paginate(10);


        return $reports->pluck('id')->toArray();

    }


    public function updatingPage()

    {

        $this->selectAll = false;

    }


    // عملیات گروهی (تایید/رد)

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
            ->whereHas('student', function ($q) {

                $q->where('supporter_id', auth()->id())
                    ->orWhere('advisor_id', auth()->id());

            })
            ->update(['status' => $action]);


        $this->selectedReports = [];

        $this->selectAll = false;


        $actionLabel = $action === 'approved' ? 'تایید' : 'رد';

        $this->dispatch('success', "گزارش‌ها با موفقیت {$actionLabel} شدند.");

    }


    // تغییر وضعیت تک گزارش

    public function changeStatus($reportId, $status)

    {

        $this->validate([

            'status' => 'in:pending,approved,rejected'

        ], [], [

            'status' => $status

        ]);


        DailyReport::where('id', $reportId)
            ->whereHas('student', function ($q) {

                $q->where('supporter_id', auth()->id())
                    ->orWhere('advisor_id', auth()->id());

            })
            ->update(['status' => $status]);


        // حذف از لیست انتخاب‌شده‌ها اگر approved/rejected شد

        if (in_array($status, ['approved', 'rejected'])) {

            $this->selectedReports = array_diff($this->selectedReports, [$reportId]);

        }


        $this->dispatch('success', 'وضعیت با موفقیت تغییر کرد.');

    }


    // حذف گزارش

    public function delete($reportId)

    {

        DailyReport::where('id', $reportId)
            ->whereHas('student', function ($q) {

                $q->where('supporter_id', auth()->id())
                    ->orWhere('advisor_id', auth()->id());

            })
            ->delete();


        $this->selectedReports = array_diff($this->selectedReports, [$reportId]);


        $this->dispatch('success', 'گزارش با موفقیت حذف شد.');

    }


    // باز کردن مودال نظر

    public function openCommentModal(int $reportId)

    {

        $report = DailyReport::with(['student.user', 'comment'])
            ->where('id', $reportId)
            ->whereHas('student', function ($q) {

                $q->where('supporter_id', auth()->id())
                    ->orWhere('advisor_id', auth()->id());

            })
            ->firstOrFail();


        $this->commentReportId = $reportId;

        $this->commentStudentName = $report->student->user->name ?? '';


        $comment = $report->comment;

        if ($comment && !empty($comment->advisor_comment)) {

            $this->advisorCommentInput = $comment->advisor_comment;

            $this->advisorCommentReadonly = true;

            $this->commentStudentReply = $comment->student_reply;

        } else {

            $this->advisorCommentInput = '';

            $this->advisorCommentReadonly = false;

            $this->commentStudentReply = null;

        }


        $this->commentModalOpen = true;

    }


    public function closeCommentModal()

    {

        $this->commentModalOpen = false;

        $this->commentReportId = null;

        $this->advisorCommentInput = '';

        $this->advisorCommentReadonly = false;

        $this->commentStudentName = '';

        $this->commentStudentReply = null;

        $this->resetErrorBag('advisorCommentInput');

    }


    public function saveAdvisorComment()

    {

        if (!$this->commentReportId) return;


        $this->validate([

            'advisorCommentInput' => 'required|string|max:1000',

        ], [

            'advisorCommentInput.required' => 'متن نظر را وارد کنید.',

            'advisorCommentInput.max' => 'طول نظر نمی‌تواند بیشتر از 1000 کاراکتر باشد.',

        ]);


        $report = DailyReport::with('comment')
            ->where('id', $this->commentReportId)
            ->whereHas('student', function ($q) {

                $q->where('supporter_id', auth()->id())
                    ->orWhere('advisor_id', auth()->id());

            })
            ->firstOrFail();


        $comment = $report->comment;

        if ($comment && !empty($comment->advisor_comment)) {

            $this->dispatch('warning', 'برای این گزارش قبلاً نظری ثبت شده است.');

            $this->closeCommentModal();

            return;

        }


        // ایجاد یا آپدیت comment

        ReportComment::updateOrCreate(

            ['report_id' => $this->commentReportId],

            [

                'advisor_comment' => $this->advisorCommentInput,

                'commented_at' => now(),

            ]

        );


        $this->dispatch('success', 'نظر شما ثبت شد.');

        $this->closeCommentModal();

    }


    public function render()

    {

        $yesterday = $this->getYesterdayDate();


        // گزارش‌های روز قبل در انتظار

        $reports = DailyReport::query()
            ->with(['student.user', 'parts.programPart', 'comment', 'details'])
            ->whereHas('advisingSession', function ($q) {

                $q->where('result_status', 'held');

            })
            ->whereDate('report_date', $yesterday->format('Y-m-d'))
            ->where('status', 'pending')
            ->whereHas('student', function ($q) {

                $q->where('supporter_id', auth()->id())
                    ->orWhere('advisor_id', auth()->id());

            })
            ->latest()
            ->paginate(10);


        // تعداد گزارش‌های ارسال نشده (روز قبل)

        $studentsWithoutReports = Student::where(function ($q) {

            $q->where('supporter_id', auth()->id())
                ->orWhere('advisor_id', auth()->id());

        })
            ->whereDoesntHave('dailyReports', function ($q) use ($yesterday) {

                $q->whereDate('report_date', $yesterday->format('Y-m-d'));

            })
            ->with('user')
            ->get()
            ->pluck('user.name')
            ->filter()
            ->values()
            ->toArray();


        $yesterdayJalali = Jalalian::fromCarbon($yesterday)->format('Y/m/d');


        return view('livewire.admin.student.daily-report-management', [

            'reports' => $reports,

            'studentsWithoutReports' => $studentsWithoutReports,

            'yesterday' => $yesterday,

            'yesterdayJalali' => $yesterdayJalali,

        ])->layout('layouts.admin.app');

    }

}


