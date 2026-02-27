<?php

namespace App\Livewire\Admin\Student\ReportDailyActivities;
use App\Exports\admin\ReportDailyActivitiesSummaryExport;
use App\Models\Student;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use Morilog\Jalali\Jalalian;
class Index extends Component
{
    use WithPagination,SEOTools;

    public $search = ''; // جستجو در نام دانش‌آموز
    public bool $exportModalOpen = false;
    public string $exportTarget = 'all';
    public string $exportStartDate = '';
    public string $exportEndDate = '';
    public array $selectedStudentIds = [];
    public function mount()
    {
        // دریافت پارامتر course_id از URL
        $this->seoConfig();
    }
    public function seoConfig()
    {
        $this->seo()
            ->setTitle('دانش آموزان');
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
            'exportStartDate' => 'required|string',
            'exportEndDate' => 'required|string',
        ];

        if ($this->exportTarget === 'selected') {
            $rules['selectedStudentIds'] = 'required|array|min:1';
        }

        $this->validate($rules, [
            'selectedStudentIds.required' => 'حداقل یک دانش‌آموز را انتخاب کنید.',
            'selectedStudentIds.min' => 'حداقل یک دانش‌آموز را انتخاب کنید.',
            'exportStartDate.required' => 'تاریخ شروع را وارد کنید.',
            'exportEndDate.required' => 'تاریخ پایان را وارد کنید.',
        ]);

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

        $studentIds = $this->exportTarget === 'all' ? null : array_map('intval', $this->selectedStudentIds);
        $this->closeExportModal();

        return Excel::download(
            new ReportDailyActivitiesSummaryExport(auth()->id(), $startDate, $endDate, $studentIds),
            'report_daily_activities_summary_' . now()->format('Ymd_His') . '.xlsx'
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
        return Student::query()
            ->with([
                'payment.order.orderItems.product',
                'payment.order.user',
                'user.personalInformation',
                'user.profile'
            ])
            ->withCount([
                'reportdaily as unread_student_replies_count' => function (Builder $query) {
                    $query->whereNotNull('student_reply')
                        ->whereNull('student_reply_seen_at');
                },
            ])
            ->withMax('reportdaily as latest_student_reply_at', 'student_replied_at')
            ->where(function (Builder $query) use ($adminId) {
                $query->where('supporter_id', $adminId)
                    ->orWhere('advisor_id', $adminId);
            });
    }

    public function render()
    {
        $adminId = auth()->id(); // گرفتن ID پشتیبان لاگین شده

        $studentsQuery = $this->studentsBaseQuery($adminId);

        // اگر جستجو فعال بود
        if ($this->search) {
            $searchTerm = '%' . $this->search . '%';

            $studentsQuery->where(function (Builder $query) use ($searchTerm) {
                $query->whereHas('user.personalInformation', function (Builder $subQuery) use ($searchTerm) {
                    $subQuery->where('name', 'like', $searchTerm);
                })
                    ->orWhereHas('user', function (Builder $subQuery) use ($searchTerm) {
                        $subQuery->where('mobile', 'like', $searchTerm);
                    });
            });
        }

        $students = $studentsQuery->paginate(10);
        $exportStudents = $this->studentsBaseQuery($adminId)
            ->select(['id', 'user_id'])
            ->with(['user.personalInformation'])
            ->get();

        return view('livewire.admin.student.report-daily-activities.index', [
            'students' => $students,
            'exportStudents' => $exportStudents,
        ])->layout('layouts.admin.app');
    }
}
