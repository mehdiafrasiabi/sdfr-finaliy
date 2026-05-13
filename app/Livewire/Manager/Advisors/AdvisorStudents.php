<?php

namespace App\Livewire\Manager\Advisors;

use App\Models\Admin;
use App\Models\Student;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class AdvisorStudents extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingPage()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function assign($userId, $advisorId)
    {
        if (!$advisorId) {
            $this->dispatch('error', 'مشاور نامعتبر است.');
            return;
        }

        $student = Student::where('user_id', $userId)
            ->whereNull('advisor_id')
            ->first();

        if (!$student) {
            $this->dispatch('error', 'دانش‌آموز یافت نشد یا قبلاً مشاور گرفته است.');
            return;
        }

        $student->advisor_id = $advisorId;
        $student->save();

        $this->dispatch('success', 'مشاور با موفقیت اختصاص یافت.');
    }

    public function render()
    {
        $advisors = Admin::role('advisor')->get();

        $students = User::whereHas('student', function ($query) {
            $query->whereNull('advisor_id');
        })
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('mobile', 'like', '%' . $this->search . '%');
            })
            ->whereHas('payments', function ($query) {
                $query->where('status', 'completed');
            })
            ->with('personalInformation', 'student')
            ->paginate(10);

        return view('livewire.manager.advisors.advisor-students', [
            'students' => $students,
            'advisors' => $advisors,
        ])->layout('layouts.manager.app');
    }
}
