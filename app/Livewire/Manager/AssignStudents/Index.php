<?php

namespace App\Livewire\Manager\AssignStudents;

use App\Models\Admin;
use App\Models\Payment;
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

    public function assign($userId, $adminId)
    {
        if (!$adminId) {
            $this->dispatch('error', 'پشتیبان نامعتبر است.');
            return;
        }

        $student = Student::where('user_id', $userId)
            ->whereNull('supporter_id')
            ->first();

        if (!$student) {
            $this->dispatch('error', 'دانش‌آموز یافت نشد یا قبلاً اختصاص یافته.');
            return;
        }

        $student->supporter_id = $adminId;
        $student->save();

        $this->dispatch('success', 'با موفقیت اختصاص یافت.');
    }

    public function render()
    {
        $admins = Admin::role('acquisition_supporter')->get();

        // کاربران با پرداخت کامل که student دارند ولی هنوز supporter_id ثبت نشده
        $students = User::whereHas('student', function ($q) {
            $q->whereNull('supporter_id');
        })
            ->whereHas('payments', function ($q) {
                $q->where('status', 'completed');
            })
            ->with('personalInformation', 'student')
            ->paginate(10);

        return view('livewire.manager.assign-students.index', [
            'students' => $students,
            'admins' => $admins,
        ])->layout('layouts.manager.app');
    }


}
