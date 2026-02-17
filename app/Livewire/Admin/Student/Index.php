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
        $adminId = auth()->id();
        $studentsQuery = Student::query()
            ->with([
                'payment.order.orderItems.product',
                'payment.order.user',
                'user.personalInformation',
                'user.personalInformation.state',
                'user.personalInformation.city',
                'user.profile',
            ])
            ->where(function ($q) use ($adminId) {
                $q->where('supporter_id', $adminId)
                    ->orWhere('advisor_id', $adminId);
            });
        if ($this->search) {
            $studentsQuery->whereHas('user.personalInformation', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }
        $students = $studentsQuery->paginate(10);
        return view('livewire.admin.student.index', [
            'students' => $students,
        ])->layout('layouts.admin.app');
    }


}
