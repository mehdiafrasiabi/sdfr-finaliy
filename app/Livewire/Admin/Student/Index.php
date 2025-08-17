<?php
namespace App\Livewire\Admin\Student;

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

    public function changeStatus($startId, $value)
    {
        $validator = Validator::make(['star' => $value, 'id' => $startId],
            [
                'id' => 'required|exists:students,id',
                'star' => 'required|in:A,B,C,D'
            ],
            [
                '*.required' => 'فیلد اجیاری است.',
                'star.in' => 'فرمت اشتباه است',
                'id.exists' => 'وضعیت سطح  نامعتبر'
            ]
        );
        $validator->validate();
        $this->resetValidation();
        $this->dispatch('success', 'با موفقیت ثبت شد');

        Student::query()->updateOrCreate(
            [
                'id' => $startId
            ],
            [
                'star' => $value
            ]);
    }
    public function getStatusColor($status)
    {
        switch ($status) {
            case 'A':
                return 'success';
            case 'B':
                return 'white';
            case 'C':
                return 'warning';
            case 'D':
                return 'danger';

        }
    }

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
                'user.personalInformation' // اضافه شد
            ])
            ->where('supporter_id', $adminId)->orWhere('advisor_id', $adminId);
        ;
        // فقط دانش‌آموزان مربوط به همین پشتیبان

        // اگر جستجو فعال بود
        if ($this->search) {
            $studentsQuery->whereHas('payment.order.user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            });
        }

        $students = $studentsQuery->paginate(10);

        return view('livewire.admin.student.index', [
            'students' => $students,
        ])->layout('layouts.admin.app');
    }

}
