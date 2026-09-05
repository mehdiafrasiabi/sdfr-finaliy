<?php

namespace App\Livewire\Admin\EssayExam;

use App\Models\EssayExam;
use App\Models\EssayExamAssignment;
use App\Models\EssayExamAssignmentTime;
use App\Models\Notification;
use App\Models\NotificationRecipient;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

class ExamAssign extends Component
{
    public int $examId;
    public ?EssayExam $exam = null;

    public string $search = '';
    /** @var int[] */
    public array $selected = [];

    // زمان‌بندی آزمون به‌صورت شمسی (تاریخ) + ساعت جدا، مطابق با الگوی بقیه صفحات اختصاص آزمون
    public string $startDateJalali = '';
    public string $endDateJalali = '';
    public string $startTime = '08:00';
    public string $endTime = '18:00';
    public int $duration_minutes = 60;

    public function mount(int $examId): void
    {
        $admin = Auth::guard('admin')->user();
        $this->exam = EssayExam::where('admin_id', $admin->id)
            ->with('questions')
            ->findOrFail($examId);
        $this->examId = $examId;

        $this->startDateJalali = $this->toJalali(now());
        $this->endDateJalali   = $this->toJalali(now()->addDay());
    }

    /**
     * تبدیل تاریخ میلادی به رشته شمسی نمایشی (Y/m/d)
     */
    protected function toJalali(Carbon $value): string
    {
        return Jalalian::fromDateTime($value)->format('Y/m/d');
    }

    /**
     * تبدیل رشته شمسی ورودی کاربر (Y/m/d) به Carbon؛ در صورت نامعتبر بودن null برمی‌گرداند.
     */
    protected function parseJalaliDateToCarbon(?string $jalaliDate): ?Carbon
    {
        $normalized = $this->normalizeDigits(trim((string) $jalaliDate));
        if ($normalized === '') {
            return null;
        }

        try {
            return Jalalian::fromFormat('Y/m/d', $normalized)->toCarbon()->startOfDay();
        } catch (\Throwable) {
            return null;
        }
    }

    protected function combineDateAndTime(Carbon $date, string $time): ?Carbon
    {
        $time = trim($time);
        if (!preg_match('/^\d{2}:\d{2}$/', $time)) {
            return null;
        }

        [$hour, $minute] = array_map('intval', explode(':', $time));
        return $date->copy()->setTime($hour, $minute);
    }

    protected function normalizeDigits(string $value): string
    {
        return str_replace(
            ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'],
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            $value
        );
    }

    public function assign(): void
    {
        $this->validate([
            'selected'         => 'required|array|min:1',
            'selected.*'       => 'integer|exists:students,id',
            'startDateJalali'  => 'required|string',
            'endDateJalali'    => 'required|string',
            'startTime'        => 'required',
            'endTime'          => 'required',
            'duration_minutes' => 'required|integer|min:5|max:600',
        ], [], [
            'selected' => 'دانش‌آموزان',
        ]);

        $startDate = $this->parseJalaliDateToCarbon($this->startDateJalali);
        $endDate   = $this->parseJalaliDateToCarbon($this->endDateJalali);

        if (!$startDate) {
            $this->addError('startDateJalali', 'تاریخ شروع را به‌صورت شمسی و معتبر وارد کنید؛ مثل 1405/06/13.');
        }

        if (!$endDate) {
            $this->addError('endDateJalali', 'تاریخ پایان را به‌صورت شمسی و معتبر وارد کنید؛ مثل 1405/06/14.');
        }

        if ($this->getErrorBag()->isNotEmpty()) {
            return;
        }

        $startAt = $this->combineDateAndTime($startDate, $this->startTime);
        $endAt   = $this->combineDateAndTime($endDate, $this->endTime);

        if (!$startAt || !$endAt) {
            $this->dispatch('error', 'فرمت ساعت آزمون صحیح نیست.');
            return;
        }

        if ($endAt->lte($startAt)) {
            $this->addError('endDateJalali', 'تاریخ و ساعت پایان باید بعد از شروع باشد.');
            return;
        }

        $admin = Auth::guard('admin')->user();

        DB::transaction(function () use ($admin, $startAt, $endAt) {
            foreach ($this->selected as $studentId) {
                $assignment = EssayExamAssignment::create([
                    'essay_exam_id' => $this->exam->id,
                    'student_id'    => $studentId,
                    'admin_id'      => $admin->id,
                    'status'        => EssayExamAssignment::STATUS_PENDING,
                ]);

                EssayExamAssignmentTime::create([
                    'assignment_id'    => $assignment->id,
                    'start_at'         => $startAt,
                    'end_at'           => $endAt,
                    'duration_minutes' => $this->duration_minutes,
                ]);

                $student = Student::with('user')->find($studentId);
                if ($student?->user) {
                    $notification = Notification::create([
                        'admin_id'         => $admin->id,
                        'category'         => Notification::CATEGORY_ANNOUNCEMENT,
                        'target_type'      => Notification::TARGET_SINGLE,
                        'title'            => 'آزمون تشریحی جدید',
                        'body'             => 'آزمون «' . $this->exam->title . '» برای شما ثبت شد.',
                        'is_from_manager'  => false,
                    ]);
                    NotificationRecipient::create([
                        'notification_id' => $notification->id,
                        'user_id'         => $student->user->id,
                        'is_read'         => false,
                    ]);
                }
            }
        });

        session()->flash('success', 'آزمون با موفقیت برای ' . count($this->selected) . ' دانش‌آموز اختصاص داده شد.');
        $this->selected = [];
    }

    public function render()
    {
        $admin = Auth::guard('admin')->user();

        $students = Student::with('user.personalInformation')
            ->where('advisor_id', $admin->id)
            ->when($this->search, function ($q) {
                $q->whereHas('user', function ($qq) {
                    $qq->where('name', 'like', "%{$this->search}%")
                        ->orWhere('mobile', 'like', "%{$this->search}%");
                });
            })
            ->get();

        return view('livewire.admin.essay-exam.exam-assign', compact('students'))
            ->layout('layouts.admin.app');
    }
}
