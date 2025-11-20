<?php

namespace App\Livewire\Admin\Student\Plan;

use App\Exports\StudentsByAdminExport;
use App\Models\Student;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

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
        $adminId = auth()->id();

        $studentsQuery = Student::query()
            ->with([
                'payment.order.orderItems.product',
                'payment.order.user',
                'user.personalInformation' // اضافه شد
            ])
            ->where('advisor_id', $adminId)
        ->orWhere('supporter_id', $adminId);

        // اگر جستجو فعال بود
        if ($this->search) {
            $studentsQuery->whereHas('payment.order.user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            });
        }

        $students = $studentsQuery->paginate(10);

        return view('livewire.admin.student.plan.index',['students' => $students,])->layout('layouts.admin.app');
    }
}
