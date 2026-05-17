<?php

namespace App\Livewire\Manager\AssignStudents;

use App\Models\Admin;
use App\Models\Student;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $selectedAdmin = [];

    public function updatingPage()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    /**
     * تخصیص «مشاور تحصیلی» به دانش‌آموزی که خرید کرده و هنوز مشاور ندارد.
     */
    public function assign($userId, $adminId)
    {
        if (! $adminId) {
            $this->dispatch('error', 'مشاور نامعتبر است.');
            return;
        }

        $student = Student::where('user_id', $userId)
            ->whereNull('advisor_id')
            ->first();

        if (! $student) {
            $this->dispatch('error', 'دانش‌آموز یافت نشد یا قبلاً مشاور دارد.');
            return;
        }

        $student->advisor_id = $adminId;
        $student->save();

        $this->dispatch('success', 'مشاور با موفقیت اختصاص یافت.');
    }

    public function render()
    {
        // فقط مشاوران تحصیلی برای تخصیص
        $admins = Admin::role('مشاور تحصیلی')->get();

        // کاربران با پرداخت کامل که student دارند ولی هنوز مشاور ندارند
        $students = User::whereHas('student', function ($q) {
            $q->whereNull('advisor_id');
        })
            ->whereHas('payments', function ($q) {
                $q->where('status', 'completed');
            })
            ->with('personalInformation', 'student')
            ->paginate(10);

        return view('livewire.manager.assign-students.index', [
            'students' => $students,
            'admins'   => $admins,
        ])->layout('layouts.manager.app');
    }
}
