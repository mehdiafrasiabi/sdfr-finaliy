<?php

namespace App\Livewire\Admin\Student\Consultation;

use App\Models\Student;
use App\Models\User;
use App\Models\AdvisingSession;
use App\Models\AdvisingPreSession;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

class CreateAdvisingSession extends Component
{
    use WithPagination;

    public $studentId = null;
    public $title;
    public $description;
    public $activation_date;
    public $session_time;
    public $location_type = 'online';
    public $skyroom_link;
    // ویرایش جلسه
    public $editingSessionId = null;
    public $result_status = null;

    protected function messages()
    {
        return [
            'title.required' => 'وارد کردن عنوان جلسه الزامی است.',
            'title.max' => 'عنوان جلسه نباید بیشتر از ۲۵۵ کاراکتر باشد.',
            'description.string' => 'فرمت توضیحات صحیح نیست.',
            'activation_date.required' => 'تاریخ برگزاری جلسه الزامی است.',
            'activation_date.date' => 'فرمت تاریخ صحیح نیست.',
            'session_time.required' => 'ساعت برگزاری جلسه الزامی است.',
            'location_type.required' => 'محل برگزاری الزامی است.',
            'location_type.in' => 'محل برگزاری معتبر نیست.',
            'skyroom_link.required_if' => 'لینک جلسه آنلاین الزامی است.',
            'skyroom_link.url' => 'فرمت لینک صحیح نیست.',
        ];
    }

    public function mount(User $student)
    {
        if (!$student->student) {
            abort(404, 'Student not found');
        }
        $this->studentId = $student->student->id;
        $this->initDefaults();
    }

    protected function initDefaults(): void
    {
        // Auto-fill description
        $this->description = 'جلسه مشاوره فردی';

        // Auto-fill date/time with today
        $this->activation_date = Carbon::today()->format('Y-m-d');
        $this->session_time = Carbon::now()->format('H:i');
    }

    public function createSession()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'activation_date' => 'required|date',
            'session_time' => 'required',
            'location_type' => 'required|in:in_person,online',
        ];
        if ($this->location_type === 'online') {
            $rules['skyroom_link'] = 'required|url';
        }
        $this->validate($rules, $this->messages());
        $session = AdvisingSession::create([
            'student_id' => $this->studentId,
            'advisor_id' => auth()->id(),
            'title' => $this->title,
            'description' => $this->description,
            'activation_date' => $this->activation_date,
            'session_time' => $this->session_time,
            'location_type' => $this->location_type,
            'skyroom_link' => $this->location_type === 'online' ? $this->skyroom_link : null,
            'status' => 'inactive',
            'is_active' => false,
        ]);
        AdvisingPreSession::create([
            'advising_session_id' => $session->id,
            'student_id' => $this->studentId,
            'title' => $this->title,
            'status' => 'pending',
        ]);
        // ارسال نوتیفیکیشن به دانش‌آموز
        $this->sendSessionCreatedNotification($session);
        $this->reset(['title', 'description', 'activation_date', 'session_time', 'skyroom_link', 'editingSessionId']);
        $this->location_type = 'online';
        $this->initDefaults();
        $this->dispatch('success', 'جلسه مشاوره و پیش‌جلسه با موفقیت ایجاد شد.');
    }

    /**
     * ارسال نوتیفیکیشن هنگام ایجاد جلسه مشاوره
     */
    protected function sendSessionCreatedNotification(AdvisingSession $session): void
    {
        $student = Student::with('user')->find($this->studentId);
        if (!$student || !$student->user) {
            return;
        }
        $studentName = $student->user->name ?? 'دانش آموز';
        $jalaliDate = Jalalian::fromDateTime($session->activation_date)->format('Y/m/d');
        $sessionTime = Carbon::parse($session->session_time)->format('H:i');
        if ($session->location_type === 'online') {
            $message = "{$studentName} عزیز\nجلسه مشاوره شما در تاریخ {$jalaliDate} و در ساعت {$sessionTime} به آدرس ({$session->skyroom_link}) برگزار خواهد شد.\nبا تشکر";
        } else {
            $message = "{$studentName} عزیز\nجلسه مشاوره شما در تاریخ {$jalaliDate} و در ساعت {$sessionTime} برگزار خواهد شد.\nبا تشکر";
        }
        NotificationService::sendToStudent(
            $this->studentId,
            'جلسه مشاوره جدید',
            $message
        );
    }

    // ویرایش جلسه
    public function editSession($sessionId)
    {
        $session = AdvisingSession::find($sessionId);
        if (!$session) {
            $this->dispatch('warning', 'جلسه یافت نشد.');
            return;
        }
        $this->editingSessionId = $session->id;
        $this->title = $session->title;
        $this->description = $session->description;
        $this->activation_date = $session->activation_date->format('Y-m-d');
        $this->session_time = $session->session_time ? Carbon::parse($session->session_time)->format('H:i') : null;
        $this->location_type = $session->location_type;
        $this->skyroom_link = $session->skyroom_link;

        // Dispatch event to pre-fill the Jalali datetime picker in the browser
        $jalaliDate = Jalalian::fromCarbon($session->activation_date)->format('Y/m/d');
        $sessionTime = $this->session_time;
        $this->dispatch('jdp-session-loaded', jalali_date: $jalaliDate, session_time: $sessionTime);
    }

    public function updateSession()
    {
        if (!$this->editingSessionId) return;
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'activation_date' => 'required|date',
            'session_time' => 'required',
            'location_type' => 'required|in:in_person,online',
        ];
        if ($this->location_type === 'online') {
            $rules['skyroom_link'] = 'required|url';
        }
        $this->validate($rules, $this->messages());
        $session = AdvisingSession::find($this->editingSessionId);
        if (!$session) {
            $this->dispatch('warning', 'جلسه یافت نشد.');
            return;
        }
        $session->update([
            'title' => $this->title,
            'description' => $this->description,
            'activation_date' => $this->activation_date,
            'session_time' => $this->session_time,
            'location_type' => $this->location_type,
            'skyroom_link' => $this->location_type === 'online' ? $this->skyroom_link : null,
        ]);
        // ارسال نوتیفیکیشن تغییر جلسه به دانش‌آموز
        $this->sendSessionUpdatedNotification();
        $this->reset(['title', 'description', 'activation_date', 'session_time', 'skyroom_link', 'editingSessionId']);
        $this->location_type = 'online';
        $this->initDefaults();
        $this->dispatch('success', 'جلسه مشاوره با موفقیت ویرایش شد.');
    }

    /**
     * ارسال نوتیفیکیشن هنگام ویرایش جلسه مشاوره
     */
    protected function sendSessionUpdatedNotification(): void
    {
        $student = Student::with('user')->find($this->studentId);
        if (!$student || !$student->user) {
            return;
        }
        $studentName = $student->user->name ?? 'دانش آموز';
        $message = "{$studentName} عزیز\nجلسه مشاوره شما تغییر یافت. برای اطلاعات بیشتر به بخش اتاق مشاوره مراجعه کنید.\nبا تشکر";
        NotificationService::sendToStudent(
            $this->studentId,
            'تغییر جلسه مشاوره',
            $message
        );
    }

    public function cancelEdit()
    {
        $this->reset(['title', 'description', 'activation_date', 'session_time', 'skyroom_link', 'editingSessionId']);
        $this->location_type = 'online';
        $this->initDefaults();
    }

    // به‌روزرسانی وضعیت نتیجه جلسه
    public function updateResultStatus($sessionId, $status)
    {
        $session = AdvisingSession::find($sessionId);
        if (!$session) return;

        // For "held" status, check program completeness
        if ($status === 'held') {
            $weeklyProgram = $session->weeklyProgram;
            if (!$weeklyProgram) {
                $this->dispatch('warning', 'برنامه هفتگی ایجاد نشده است. ابتدا برنامه هفتگی را ایجاد کنید.');
                return;
            }

            $totalParts = $weeklyProgram->parts()->count();
            if ($totalParts === 0) {
                $this->dispatch('warning', 'برنامه هفتگی هیچ پارتی ندارد.');
                return;
            }

            $zeroTimeParts = $weeklyProgram->parts()->where('duration_minutes', 0)->count();
            if ($zeroTimeParts > 0) {
                $this->dispatch('warning', "برنامه هفتگی {$zeroTimeParts} پارت بدون تایم دارد. ابتدا تایم همه پارت‌ها را تنظیم کنید.");
                return;
            }
        }
        $session->update([
            'result_status' => $status,
            'status' => 'completed',
        ]);
        $this->dispatch('success', 'وضعیت جلسه با موفقیت ثبت شد.');
    }

    /**
     * Check if a session's program is complete (all parts have time > 0)
     */
    public function isProgramComplete($sessionId): bool
    {
        $session = AdvisingSession::find($sessionId);
        if (!$session) return false;

        $weeklyProgram = $session->weeklyProgram;
        if (!$weeklyProgram) return false;

        $totalParts = $weeklyProgram->parts()->count();
        if ($totalParts === 0) return false;

        return $weeklyProgram->parts()->where('duration_minutes', 0)->count() === 0;
    }

    // حذف جلسه
    public function deleteSession($id)
    {
        $session = AdvisingSession::find($id);
        if ($session) {
            $session->delete();
            $this->dispatch('success', 'جلسه مشاوره حذف شد.');
        } else {
            $this->dispatch('warning', 'جلسه مورد نظر یافت نشد.');
        }

    }

    public function updatedLocationType($value)
    {
        if ($value === 'in_person') {
            $this->skyroom_link = null;
        }
    }

    public function render()
    {
        $student = Student::with(['user.personalInformation'])->find($this->studentId);
        $sessions = AdvisingSession::where('student_id', $this->studentId)
            ->with(['preSession', 'weeklyProgram'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        // فعال‌سازی خودکار جلسات
        foreach ($sessions as $session) {
            $session->activateIfNeeded();
        }
        return view('livewire.admin.student.consultation.create-advising-session', [
            'student' => $student,
            'sessions' => $sessions,
        ])->layout('layouts.admin.app');

    }
}
