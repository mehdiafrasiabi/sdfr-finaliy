<?php

namespace App\Livewire\Admin\Student;

use App\Exports\admin\ReportDailyActivitiesForAdmin;
use App\Exports\ReportDailyForAdminExport;
use App\Models\Report;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Morilog\Jalali\Jalalian;
use App\Models\Student;
use Carbon\Carbon;

class ReportDaily extends Component
{
    use WithPagination;

    public $selectedReports = []; // آرایه‌ای از آی‌دی گزارش‌های انتخاب‌شده
    public $selectAll = false;
    public string $status = 'active';
    public $startDate = null;
    public $endDate = null;
    public string $checkTime = '21:00';
    protected $queryString = ['startDate', 'endDate'];
    public $studentsWithoutReports = [];

    public bool $commentModalOpen = false;
    public ?int $commentReportId = null;
    public string $advisorCommentInput = '';
    public bool $advisorCommentReadonly = false;
    public string $commentStudentName = '';
    public ?string $commentStudentReply = null;

    protected function getStudentsWithoutReports()
    {
        $allStudents = Student::where('supporter_id', auth()->id())
            ->orWhere('advisor_id', auth()->id())
            ->get();

        $referenceTime = $this->resolveReferenceTime();
        $windowStart = (clone $referenceTime)->subHours(48);

        $studentsWithReports = Report::where('admin_id', auth()->id())
            ->whereBetween('created_at', [$windowStart, $referenceTime])
            ->pluck('student_id')
            ->toArray();

        $this->studentsWithoutReports = $allStudents->whereNotIn('id', $studentsWithReports);
    }
    public function updatingStartDate()
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatingEndDate()
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatedCheckTime($value)
    {
        if (!$this->isValidTime($value)) {
            $this->addError('checkTime', 'فرمت زمان نادرست است.');
            return;
        }

        $this->resetErrorBag('checkTime');
        $this->getStudentsWithoutReports();
    }

    // ریست کردن انتخاب‌ها
    private function resetSelection()
    {
        $this->selectedReports = [];
        $this->selectAll = false;
    }



    public function getStatusColor($status)
    {
        switch ($status) {
            case 'pending':
                return 'primary-500';
            case 'rejected':
                return 'warning-700';
            case 'completed':
                return 'success-600';
            default:
                return 'gray-500';
        }
    }

    public function delete($report_id)
    {
        Report::query()
            ->where('id', $report_id)
            ->where('admin_id', auth()->id())
            ->delete();

        // اگر آیتم حذف شده در لیست انتخاب‌ها بود، حذفش کن
        $this->selectedReports = array_diff($this->selectedReports, [$report_id]);

        $this->dispatch('success', 'با موفقیت حذف شد');
    }

    protected function parseJalaliToCarbonStart($jalali): ?\Carbon\Carbon
    {
        try {
            return Jalalian::fromFormat('Y/m/d', $jalali)->toCarbon()->startOfDay();
        } catch (\Throwable) {
            return null;
        }
    }

    protected function parseJalaliToCarbonEnd($jalali): ?\Carbon\Carbon
    {
        try {
            return Jalalian::fromFormat('Y/m/d', $jalali)->toCarbon()->endOfDay();
        } catch (\Throwable) {
            return null;
        }
    }

    // وقتی چک‌باکس "انتخاب همه" تغییر می‌کند
    public function updatedSelectAll($value)
    {
        if ($value) {
            // گرفتن آیتم‌های صفحه فعلی
            $currentPageIds = $this->getCurrentPageReportIds();
            // اضافه کردن آیتم‌های صفحه فعلی به لیست انتخاب‌ها
            $this->selectedReports = array_unique(array_merge($this->selectedReports, $currentPageIds));
        } else {
            // حذف آیتم‌های صفحه فعلی از لیست انتخاب‌ها
            $currentPageIds = $this->getCurrentPageReportIds();
            $this->selectedReports = array_diff($this->selectedReports, $currentPageIds);
        }
    }

    // وقتی یک چک‌باکس تکی تغییر می‌کند
    public function updatedSelectedReports()
    {
        // بررسی اینکه آیا همه آیتم‌های صفحه فعلی انتخاب شده‌اند یا نه
        $currentPageIds = $this->getCurrentPageReportIds();

        if (empty($currentPageIds)) {
            $this->selectAll = false;
            return;
        }

        // اگر همه آیتم‌های صفحه فعلی در لیست انتخاب‌ها هستند، چک‌باکس "همه" را فعال کن
        $selectedInCurrentPage = array_intersect($this->selectedReports, $currentPageIds);
        $this->selectAll = count($selectedInCurrentPage) === count($currentPageIds);
    }

    // گرفتن آی‌دی‌های صفحه فعلی
    private function getCurrentPageReportIds()
    {
        $query = Report::with('student.user')->where('admin_id', auth()->id());

        $query->where('status', '!=', 'completed');


        if ($this->startDate) {
            $start = $this->parseJalaliToCarbonStart($this->startDate);
            if ($start) {
                $query->where('created_at', '>=', $start);
            }
        }

        if ($this->endDate) {
            $end = $this->parseJalaliToCarbonEnd($this->endDate);
            if ($end) {
                $query->where('created_at', '<=', $end);
            }
        }

        $reports = $query->latest()->paginate(10);

        return $reports->pluck('id')->toArray();
    }

    // وقتی صفحه تغییر می‌کند، انتخاب‌های صفحه قبل را پاک نکن
    // فقط وضعیت چک‌باکس "انتخاب همه" را بررسی کن
    public function updatingPage()
    {
        // چک‌باکس "همه" را غیرفعال کن چون داریم به صفحه دیگه میریم
        $this->selectAll = false;
    }

    // عملیات گروهی
    public function bulkAction($action)
    {
        if (empty($this->selectedReports)) {
            $this->dispatch('warning', 'هیچ گزارشی انتخاب نشده است.');
            return;
        }

        if (!in_array($action, ['completed', 'rejected'])) {
            $this->dispatch('warning', 'عملیات نامعتبر است.');
            return;
        }

        Report::whereIn('id', $this->selectedReports)
            ->where('admin_id', auth()->id())
            ->update(['status' => $action]);

        $this->resetSelection();

        $this->dispatch('success', 'عملیات گروهی با موفقیت انجام شد.');
    }

    public function exportExcel()
    {
        $statusLabel = $this->status === 'all' ? 'all' : $this->status;
        $startLabel = $this->startDate ? str_replace('/', '-', $this->startDate) : 'start';
        $endLabel = $this->endDate ? str_replace('/', '-', $this->endDate) : 'end';

        $fileName = "report_daily_{$statusLabel}_{$startLabel}_{$endLabel}_" . now()->format('Ymd_His') . ".xlsx";

        return Excel::download(new ReportDailyActivitiesForAdmin($this->status, $this->startDate, $this->endDate), $fileName);
    }

    protected function resolveReferenceTime(): Carbon
    {
        $time = $this->parseTime($this->checkTime) ?? Carbon::createFromTimeString('21:00', config('app.timezone'));
        $reference = Carbon::today(config('app.timezone'))->setTimeFromTimeString($time->format('H:i'));

        if (Carbon::now(config('app.timezone'))->lt($reference)) {
            $reference->subDay();
        }

        return $reference;
    }

    protected function parseTime(string $value): ?Carbon
    {
        try {
            return Carbon::createFromFormat('H:i', $value, config('app.timezone'));
        } catch (\Throwable) {
            return null;
        }
    }

    protected function isValidTime(string $value): bool
    {
        return $this->parseTime($value) !== null;
    }

    public function openCommentModal(int $reportId)
    {
        $report = Report::with('student.user')
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
        if (!$this->commentReportId) {
            return;
        }

        $report = Report::where('id', $this->commentReportId)
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

    public function changeStatus($reportId, $value)
    {
        $validator = Validator::make(['status' => $value, 'id' => $reportId],
            [
                'id' => 'required|exists:reports,id',
                'status' => 'required|in:pending,completed,rejected'
            ],
            [
                '*.required' => 'فیلد اجباری است.',
                'status.in' => 'فرمت اشتباه است',
                'id.exists' => 'وضعیت تماس نامعتبر'
            ]
        );

        $validator->validate();
        $this->resetValidation();

        $report = Report::where('id', $reportId)
            ->where('admin_id', auth()->id())
            ->firstOrFail();

        $report->update(['status' => $value]);

        if ($value === 'completed') {
            $this->selectedReports = array_diff($this->selectedReports, [$reportId]);
        }

        $this->dispatch('success', 'با موفقیت ثبت شد');
    }




    public function render()
    {
        $query = Report::with('student.user')->where('admin_id', auth()->id());

        if ($this->status === 'active') {
            $query->where('status', '!=', 'completed');
        } elseif ($this->status !== 'all') {
            $query->where('status', $this->status);
        }
        if ($this->status === 'active') {
            $query->where('status', '!=', 'rejected');
        } elseif ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        if ($this->startDate) {
            $start = $this->parseJalaliToCarbonStart($this->startDate);
            if ($start) {
                $query->where('created_at', '>=', $start);
            }
        }

        if ($this->endDate) {
            $end = $this->parseJalaliToCarbonEnd($this->endDate);
            if ($end) {
                $query->where('created_at', '<=', $end);
            }
        }

        $reports = $query->latest()->paginate(10);

        $this->getStudentsWithoutReports();

        $reports->getCollection()->transform(function ($item) {
            $item->statusColor = $this->getStatusColor($item->status);
            return $item;
        });

        return view('livewire.admin.student.report-daily', [
            'reports' => $reports,
            'studentsWithoutReports' => $this->studentsWithoutReports,
            'referenceTime' => $this->resolveReferenceTime(),
        ])->layout('layouts.admin.app');
    }
}
