<?php

namespace App\Livewire\Admin\Student\ReportDailyActivities;

use App\Exports\StudentsByAdminExport;
use App\Models\Student;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Builder;

class Index extends Component
{
    use WithPagination,SEOTools;

    public $search = ''; // جستجو در نام دانش‌آموز

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
    public function exportExcel()
    {
        $admin = auth()->user();
        $adminId = $admin->id;
        $adminName = $admin->name ?: 'admin'; // اگر نام نبود، fallback

        $fileName = Str::slug($adminName) . '_students_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new StudentsByAdminExport($adminId),
            $fileName
        );
    }
    public function render()
    {
        $adminId = auth()->id(); // گرفتن ID پشتیبان لاگین شده

        $studentsQuery = Student::query()
            ->with([
                'payment.order.orderItems.product',
                'payment.order.user',
                'user.personalInformation'
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
            });        // فقط دانش‌آموزان مربوط به همین پشتیبان

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
        return view('livewire.admin.student.report-daily-activities.index', ['students' => $students])->layout('layouts.admin.app');
    }
}
