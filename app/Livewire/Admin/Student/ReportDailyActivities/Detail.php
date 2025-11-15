<?php

namespace App\Livewire\Admin\Student\ReportDailyActivities;

use App\Exports\admin\ReportDailyActivitiesStudentForAdmin;
use App\Models\Report;
use App\Models\User;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Morilog\Jalali\Jalalian;

class Detail extends Component
{
    use WithPagination, SEOTools;

    public $studentName;
    public $studentId;
    public $status = 'all';
    public $startDate = null; // شمسی مثل 1403/05/01
    public $endDate = null;   // شمسی
    protected $queryString = ['status', 'startDate', 'endDate'];

    public bool $commentModalOpen = false;
    public ?int $commentReportId = null;
    public string $advisorCommentInput = '';
    public bool $advisorCommentReadonly = false;
    public string $commentStudentName = '';
    public ?string $commentStudentReply = null;

    public function mount(User $student)
    {
        $this->studentId = $student->student->id;
        $this->studentName = $student->personalInformation->name;
        $this->seoConfig();
        $this->markStudentRepliesAsSeen();
    }

    public function seoConfig()
    {
        $this->seo()
            ->setTitle('گزارش‌های ' . $this->studentName);
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingStartDate()
    {
        $this->resetPage();
    }

    public function updatingEndDate()
    {
        $this->resetPage();
    }

    public function changeStatus($statustId, $value)
    {
        $validator = Validator::make(
            ['status' => $value, 'id' => $statustId],
            [
                'id' => 'required|exists:reports,id',
                'status' => 'required|in:pending,completed,rejected'
            ],
            [
                '*.required' => 'فیلد الزامی است.',
                'status.in' => 'فرمت وضعیت اشتباه است.',
                'id.exists' => 'شناسه گزارش نامعتبر است.'
            ]
        );

        $validator->validate();

        Report::query()->where('id', $statustId)->update(['status' => $value]);

        $this->resetValidation();
        $this->dispatch('success', 'با موفقیت ثبت شد');
    }

    public function getStatusColor($status)
    {
        return match ($status) {
            'pending' => 'primary',
            'rejected' => 'danger',
            'completed' => 'success',
            default => 'secondary'
        };
    }

    public function delete($report_id)
    {
        Report::query()->where('id', $report_id)->delete();
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

    public function exportExcel()
    {
        // اعتبارسنجی ساده: اگر هر دو تاریخ هستن، اطمینان از درست بودن بازه
        if ($this->startDate && $this->endDate) {
            $start = $this->parseJalaliToCarbonStart($this->startDate);
            $end = $this->parseJalaliToCarbonEnd($this->endDate);
            if ($start && $end && $start->gt($end)) {
                $this->dispatch('error', 'تاریخ شروع نباید بزرگ‌تر از تاریخ پایان باشد.');
                return;
            }
        }

        $safeName = Str::slug($this->studentName ?: "student_{$this->studentId}");
        $statusLabel = $this->status === 'all' ? 'all' : $this->status;
        $startLabel = $this->startDate ? str_replace('/', '-', $this->startDate) : 'start';
        $endLabel = $this->endDate ? str_replace('/', '-', $this->endDate) : 'end';

        $fileName = "{$safeName}_daily_reports_{$statusLabel}_{$startLabel}_{$endLabel}_" . now()->format('Ymd_His') . ".xlsx";

        return Excel::download(
            new ReportDailyActivitiesStudentForAdmin($this->studentId, $this->status, $this->startDate, $this->endDate),
            $fileName
        );
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

    protected function markStudentRepliesAsSeen(): void
    {
        Report::query()
            ->where('student_id', $this->studentId)
            ->whereNotNull('student_reply')
            ->whereNull('student_reply_seen_at')
            ->update([
                'student_reply_seen_at' => now(),
            ]);
    }

    public function render()
    {
        $query = Report::with('student')->where('student_id', $this->studentId);

        if ($this->status !== 'all') {
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

        return view('livewire.admin.student.report-daily-activities.detail',['reports' => $reports])->layout('layouts.admin.app');
    }
}
