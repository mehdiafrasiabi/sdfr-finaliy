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
    use WithPagination, SEOTools;
    public $search = ''; // جستجو در نام دانش‌آموز
    public function mount()
    {
        $this->seoConfig();
    }
    public function seoConfig()
    {
        $this->seo()
            ->setTitle('دانش آموزان');
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $admin = auth('admin')->user();
        $studentsQuery = Student::query()
            ->with([
                'payment.order.orderItems.product',
                'payment.order.user',
                'user.personalInformation',
                'user.personalInformation.state',
                'user.personalInformation.city',
                'user.profile',
                'advisor',
            ]);

        // مدیر مدرسه فقط دانش‌آموزان مدرسهٔ خود را می‌بیند؛ سایر ادمین‌ها دانش‌آموزان تحت مشاورهٔ خود.
        $isSchoolManager = $admin?->hasRole('school-manager') && $admin->school_id;

        if ($isSchoolManager) {
            $studentsQuery->where('school_id', $admin->school_id);

            if ($this->search) {
                $studentsQuery->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('mobile', 'like', '%' . $this->search . '%');
                });
            }
        } else {
            $studentsQuery->where('advisor_id', $admin?->id);

            if ($this->search) {
                $studentsQuery->whereHas('user.personalInformation', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            }
        }

        $students = $studentsQuery->paginate(10);
        return view('livewire.admin.student.index', [
            'students'        => $students,
            'isSchoolManager' => $isSchoolManager,
        ])->layout('layouts.admin.app');
    }


}
