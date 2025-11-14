<?php

namespace App\Livewire\Admin\Notification;

use App\Models\Notification;
use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;

class Create extends Component
{
    use WithPagination;

    public $students;
    public $title;
    public $body;
    public $studentId = null;

    public function mount()
    {
        $this->students = Student::query()->where('supporter_id',auth()->id())->orWhere('advisor_id',auth()->id())->get();
    }

    public function send()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'studentId' => 'nullable|exists:students,id',
        ], [
            'title.required' => 'عنوان اعلان الزامی است.',
            'body.required' => 'متن اعلان الزامی است.',
            'studentId.exists' => 'شناسه دانش‌آموز نامعتبر است.',
        ]);

        if ($this->studentId) {
            Notification::create([
                'admin_id' => auth()->id(),
                'student_id' => $this->studentId,
                'title' => $this->title,
                'body' => $this->body,
            ]);
        } else {
            foreach ($this->students as $student) {
                Notification::create([
                    'admin_id' => auth()->id(),
                    'student_id' => $student->id,
                    'title' => $this->title,
                    'body' => $this->body,
                ]);
            }
        }

        $this->dispatch('success', 'اعلان با موفقیت ارسال شد.');
        $this->resetPage(); // برگرد به صفحه اول پس از ارسال
        $this->reset(['title', 'body', 'studentId']);
    }

    public function deleteNotification($id)
    {
        Notification::findOrFail($id)->delete();
        $this->dispatch('success', 'اعلان با موفقیت حذف شد.');
    }
    public function render()
    {
        $notifications =  Notification::query()

            ->where('admin_id',auth()->id())->latest()->paginate(10);
        return view('livewire.admin.notification.create',[
            'notifications' => $notifications,
        ])->layout('layouts.admin.app');
    }
}
