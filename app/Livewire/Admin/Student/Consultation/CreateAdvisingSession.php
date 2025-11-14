<?php

namespace App\Livewire\Admin\Student\Consultation;

use App\Models\Student;
use App\Models\AdvisingSession;
use App\Models\AdvisingPreSession;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class CreateAdvisingSession extends Component
{
    use WithPagination;

    public $studentId = null;
    public $title;
    public $description;
    public $activation_date;
    public $skyroom_link;


    public function mount(Student $student)
    {
        $this->studentId = $student->id;
    }

    public function createSession($formData)
    {

        // ✅ ولیدیشن حرفه‌ای با Validator::make
        $validator = Validator::make($formData, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'activation_date' => 'required|date',
            'skyroom_link' => 'required|url',
        ], [
            '*.required' => 'فیلد ضروری است.',
            '*.string' => 'فرمت داده اشتباه است!',
            '*.date' => 'فرمت تاریخ اشتباه است!',
            '*.url' => 'آدرس لینک معتبر نیست.',
        ]);

      $validator->validate();

        // ✅ ساخت جلسه اصلی
        $session = AdvisingSession::create([
            'student_id' => $this->studentId,
            'advisor_id' => auth()->id(),
            'title' => $this->title,
            'description' => $this->description,
            'activation_date' => $this->activation_date,
            'skyroom_link' => $this->skyroom_link,
            'status' => 'inactive',
        ]);

        // ✅ ساخت پیش‌جلسه به‌صورت خودکار
        AdvisingPreSession::create([
            'advising_session_id' => $session->id,
            'student_id' => $this->studentId,
            'title' => $this->title,
            'status' => 'pending',
        ]);

        // ✅ ریست فرم
        $this->reset(['title', 'description', 'activation_date', 'skyroom_link']);

        $this->dispatch('success', 'جلسه مشاوره و پیش‌جلسه با موفقیت ایجاد شد.');

    }

    // ✅ حذف جلسه
    public function deleteSession($id)
    {
        $session = AdvisingSession::find($id);
        if ($session) {
            // حذف پیش‌جلسه مرتبط
            AdvisingPreSession::where('advising_session_id', $session->id)->delete();
            $session->delete();
            $this->dispatch('success', 'جلسه مشاوره حذف شد.');
        } else {
            $this->dispatch('warning', 'جلسه مورد نظر یافت نشد.');
        }
    }

    public function render()
    {
        // 🔧 نکته مهم: استفاده از paginate به جای get (که باعث firstItem error شده بود)
        $student = Student::find($this->studentId);
        $sessions = AdvisingSession::where('student_id', $this->studentId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.student.consultation.create-advising-session', [
            'student' => $student,
            'sessions' => $sessions,
        ])->layout('layouts.admin.app');
    }
}
