<?php

namespace App\Livewire\Admin\Student\Consultation;

use App\Models\Student;
use App\Models\User;
use App\Models\AdvisingSession;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * صفحه‌ی a — «تاریخچه‌ی جلسات مشاوره‌ی دانش‌آموز» (فقط‌خواندنی).
 *
 * در طرحِ جدید، ساخت/ویرایشِ جلسه از این صفحه حذف شده و همه‌چیز در صفحه‌ی جلسات (b)
 * انجام می‌شود. اینجا مشاور فقط جلساتِ برگزارشده/پیش‌رو را می‌بیند و نتیجه‌ی هر جلسه را
 * (برگزار شد / غیبتِ دانش‌آموز / غیبتِ مشاور) ثبت می‌کند.
 */
class CreateAdvisingSession extends Component
{
    use WithPagination;

    public $studentId = null;

    public function mount(User $student)
    {
        if (! $student->student) {
            abort(404, 'Student not found');
        }
        $this->studentId = $student->student->id;
    }

    /**
     * ثبتِ نتیجه‌ی جلسه (برگزار/غیبت). برای «برگزار شد» نیاز به برنامه‌ی هفتگیِ کامل است.
     */
    public function updateResultStatus($sessionId, $status)
    {
        $session = AdvisingSession::find($sessionId);
        if (! $session) {
            return;
        }

        if ($status === AdvisingSession::RESULT_HELD) {
            $weeklyProgram = $session->weeklyProgram;
            if (! $weeklyProgram) {
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
            'status'        => AdvisingSession::STATUS_COMPLETED,
        ]);
        $this->dispatch('success', 'وضعیت جلسه با موفقیت ثبت شد.');
    }

    public function isProgramComplete($sessionId): bool
    {
        $session = AdvisingSession::find($sessionId);
        if (! $session) {
            return false;
        }

        $weeklyProgram = $session->weeklyProgram;
        if (! $weeklyProgram) {
            return false;
        }

        $totalParts = $weeklyProgram->parts()->count();
        if ($totalParts === 0) {
            return false;
        }

        return $weeklyProgram->parts()->where('duration_minutes', 0)->count() === 0;
    }

    public function render()
    {
        $student = Student::with(['user.personalInformation'])->find($this->studentId);

        $sessions = AdvisingSession::where('student_id', $this->studentId)
            ->with(['preSession', 'weeklyProgram'])
            ->orderBy('activation_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15);

        foreach ($sessions as $session) {
            $session->activateIfNeeded();
        }

        return view('livewire.admin.student.consultation.create-advising-session', [
            'student'  => $student,
            'sessions' => $sessions,
        ])->layout('layouts.admin.app');
    }
}
