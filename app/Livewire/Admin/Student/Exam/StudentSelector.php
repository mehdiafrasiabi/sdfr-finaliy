<?php

namespace App\Livewire\Admin\Student\Exam;

use App\Models\Exam;
use App\Models\Student;
use Livewire\Component;

class StudentSelector extends Component
{
    public $exam;
    public $selectedStudents = [];

    public function mount(Exam $exam)
    {
        $this->exam = $exam;
        $this->selectedStudents = $exam->students()->select('students.id')->pluck('students.id')->toArray();
    }

    public function save()
    {
        $this->exam->students()->sync($this->selectedStudents);
        session()->flash('success', 'دانش‌آموزان با موفقیت ثبت شدند.');
    }
    public function render()
    {
        $students = Student::query()
            ->with([
                'payment.order.orderItems.product',
                'payment.order.user',
                'user.personalInformation'
            ])
            ->where('supporter_id', auth()->id())
            ->get(); // این خط اضافه بشه
        return view('livewire.admin.student.exam.student-selector',['students'=>$students])->layout('layouts.admin.app');
    }
}
