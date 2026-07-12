<?php

namespace App\Livewire\Admin\Student\Consultation;

use App\Models\Student;
use App\Models\User;
use App\Models\AdvisingSession;
use Carbon\Carbon;
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

    public function studentDisplayName($student): string
    {
        $personalInfo = $student?->user?->personalInformation;
        $fullName = trim(($personalInfo?->name ?? '') . ' ' . ($personalInfo?->name_full ?? ''));

        return $fullName !== '' ? $fullName : ($student?->user?->name ?? 'دانش‌آموز');
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

        if ($status === AdvisingSession::RESULT_STUDENT_ABSENT) {
            $this->createMakeupForAbsence($session);
        }

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

    protected function sessionDateTime(AdvisingSession $session): ?Carbon
    {
        if (! $session->activation_date || ! $session->session_time) {
            return null;
        }

        return Carbon::parse($session->activation_date)
            ->setTimeFromTimeString($session->session_time->format('H:i:s'));
    }

    public function canMarkStudentAbsentDuringWindow(int $sessionId): bool
    {
        $session = AdvisingSession::find($sessionId);
        if (! $session || (int) $session->student_id !== (int) $this->studentId) {
            return false;
        }

        if (! $session->finalized || $session->result_status !== null || $session->status === AdvisingSession::STATUS_COMPLETED) {
            return false;
        }

        $sessionDateTime = $this->sessionDateTime($session);
        if (! $sessionDateTime) {
            return false;
        }

        $now = Carbon::now();

        return $now->gte($sessionDateTime) && $now->lte($sessionDateTime->copy()->addHour());
    }

    public function canOpenWeeklyProgram(int $sessionId): bool
    {
        $session = AdvisingSession::find($sessionId);
        if (! $session) {
            return false;
        }

        return ! in_array($session->result_status, [
            AdvisingSession::RESULT_STUDENT_ABSENT,
            AdvisingSession::RESULT_ADVISOR_ABSENT,
        ], true);
    }

    protected function createMakeupForAbsence(AdvisingSession $sourceSession): void
    {
        AdvisingSession::firstOrCreate(
            [
                'advisor_id'        => $sourceSession->advisor_id,
                'student_id'        => $sourceSession->student_id,
                'source_session_id' => $sourceSession->id,
                'is_makeup'         => true,
            ],
            [
                'title'           => 'جلسه جبرانی',
                'description'     => 'جلسه جبرانی (غیبت دانش‌آموز)',
                'activation_date' => null,
                'session_time'    => null,
                'location_type'   => AdvisingSession::LOCATION_ONLINE,
                'status'          => AdvisingSession::STATUS_INACTIVE,
                'is_active'       => false,
                'finalized'       => false,
                'makeup_reason'   => AdvisingSession::MAKEUP_STUDENT_ABSENCE,
            ]
        );
    }

    public function markStudentAbsentDuringWindow(int $sessionId): void
    {
        if (! $this->canMarkStudentAbsentDuringWindow($sessionId)) {
            $this->dispatch('warning', 'امکان ثبت غیبت برای این جلسه در این زمان وجود ندارد.');
            return;
        }

        $session = AdvisingSession::find($sessionId);
        if (! $session) {
            return;
        }

        $session->update([
            'result_status' => AdvisingSession::RESULT_STUDENT_ABSENT,
            'status'        => AdvisingSession::STATUS_COMPLETED,
            'is_active'     => false,
        ]);

        $this->createMakeupForAbsence($session);

        $this->dispatch('success', 'غیبت دانش‌آموز ثبت شد و جلسه‌ی جبرانی در انتظار تعیین روز قرار گرفت.');
    }

    public function render()
    {
        AdvisingSession::markExpiredSessionsAsAdvisorAbsent();

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
