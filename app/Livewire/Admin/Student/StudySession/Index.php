<?php

namespace App\Livewire\Admin\Student\StudySession;

use App\Exports\StudentsByAdminExport;
use App\Models\Student;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Validator;
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

    public function render()
    {
        $adminId = auth()->id(); // گرفتن ID پشتیبان لاگین شده

        $studentsQuery = Student::query()
            ->with([
                'payment.order.orderItems.product',
                'payment.order.user',
                'user.personalInformation',
                'user.profile'
            ])
            ->where('supporter_id', $adminId);        // فقط دانش‌آموزان مربوط به همین پشتیبان

        // اگر جستجو فعال بود
        if ($this->search) {
            $studentsQuery->whereHas('user.personalInformation', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }
        $students = $studentsQuery->paginate(10);

        return view('livewire.admin.student.study-session.index',['students'=>$students])->layout('layouts.admin.app');
    }
}
