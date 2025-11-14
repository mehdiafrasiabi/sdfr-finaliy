<?php

namespace App\Livewire\Admin\Student\Exam;

use App\Models\Exam;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public Exam $selectedExam;
    public $students;
    public $assignedStudents = [];
    public bool $showStudentModal = false;

    // متد mount برای مقداردهی اولیه
    public function mount()
    {
        // در ابتدا، لیست دانش‌آموزان مرتبط با ادمین فعلی را بارگذاری می‌کنیم.
        // فرض می‌کنیم در مدل Student یک foreignId به admin_id داریم.
        $this->students = auth()->user()->supportedStudents;
    }

    // متد برای فعال‌سازی یک آزمون
    public function activateExam(Exam $exam)
    {
        if ($exam->is_active) {
            session()->flash('error', 'این آزمون قبلا فعال شده است.');
            return;
        }

        $exam->update(['is_active' => true]);
        session()->flash('success', 'آزمون با موفقیت فعال شد.');
    }

    // متد باز کردن Modal برای اختصاص دانش‌آموزان
    public function openAssignStudentsModal(Exam $exam)
    {
        $this->selectedExam = $exam;
        $this->showStudentModal = true;

        // این بخش بسیار مهم است. ID دانش‌آموزان اختصاص داده شده را بارگذاری می‌کند.
        $this->assignedStudents = $exam->students->pluck('id')->toArray();
    }
    // متد برای بستن Modal
    public function closeAssignStudentsModal()
    {
        $this->showStudentModal = false;
        $this->reset(['assignedStudents']);
    }

    // متد برای اختصاص دانش‌آموزان به آزمون
    public function assignStudents()
    {
        $this->validate([
            'assignedStudents' => 'required|array|min:1'
        ], [
            'assignedStudents.required' => 'لطفاً حداقل یک دانش‌آموز را انتخاب کنید.',
            'assignedStudents.min' => 'لطفاً حداقل یک دانش‌آموز را انتخاب کنید.'
        ]);

        // ارتباط دانش‌آموزان انتخاب شده با آزمون
        // از syncWithPivotValues برای جلوگیری از تکرار استفاده می‌کنیم
        $this->selectedExam->students()->sync($this->assignedStudents);

        session()->flash('success', 'دانش‌آموزان با موفقیت به آزمون اختصاص داده شدند.');
        $this->closeAssignStudentsModal();
    }

    // متد render برای نمایش View
    public function render()
    {
        // نمایش آزمون‌هایی که توسط ادمین فعلی ساخته شده‌اند
        $exams = Exam::with(['user.personalInformation'])
            ->where('admin_id', 1)
            ->latest()
            ->paginate(10);


        return view('livewire.admin.student.exam.index',[
            'exams' => $exams
        ])->layout('layouts.admin.app');
    }
}
