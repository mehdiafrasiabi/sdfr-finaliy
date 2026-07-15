<?php

namespace App\Services;

use App\Models\StudentExamDayFeedback;
use App\Models\StudentExamSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class StudentExamDayFeedbackService
{
    private const EXAM_END_TIME = '07:30:00';
    private const FEEDBACK_OPEN_TIME = '09:00:00';

    public function activeBuiltScheduleFor(User $user): ?StudentExamSchedule
    {
        return StudentExamSchedule::query()
            ->where('user_id', $user->id)
            ->whereNotNull('weekly_program_id')
            ->whereNotNull('program_built_at')
            ->with(['days.subject'])
            ->orderByDesc('program_built_at')
            ->orderByDesc('id')
            ->first();
    }

    public function nextExamCountdown(User $user): ?array
    {
        $schedule = $this->activeBuiltScheduleFor($user);
        if (! $schedule) {
            return null;
        }

        $now = now();
        $examGroup = $this->examGroups($schedule)
            ->first(fn (array $group) => $group['ends_at']->gt($now));

        if (! $examGroup) {
            return null;
        }

        return [
            'schedule_id' => $schedule->id,
            'exam_date' => $examGroup['exam_date'],
            'ends_at' => $examGroup['ends_at']->toIso8601String(),
            'jalali_date' => jdate($examGroup['exam_date'])->format('Y/m/d'),
            'day_name' => jdate($examGroup['exam_date'])->format('%A'),
            'subjects' => $examGroup['subjects'],
        ];
    }

    public function pendingFeedback(User $user): ?array
    {
        $schedule = $this->activeBuiltScheduleFor($user);
        if (! $schedule) {
            return null;
        }

        $now = now();
        $answeredDates = StudentExamDayFeedback::query()
            ->where('user_id', $user->id)
            ->where('student_exam_schedule_id', $schedule->id)
            ->pluck('exam_date')
            ->map(fn ($date) => Carbon::parse($date)->toDateString())
            ->all();

        $examGroup = $this->examGroups($schedule)
            ->first(function (array $group) use ($now, $answeredDates) {
                return $group['feedback_opens_at']->lte($now)
                    && ! in_array($group['exam_date']->toDateString(), $answeredDates, true);
            });

        if (! $examGroup) {
            return null;
        }

        return [
            'schedule_id' => $schedule->id,
            'exam_date' => $examGroup['exam_date']->toDateString(),
            'jalali_date' => jdate($examGroup['exam_date'])->format('Y/m/d'),
            'day_name' => jdate($examGroup['exam_date'])->format('%A'),
            'subjects' => $examGroup['subjects'],
            'difficulty_options' => StudentExamDayFeedback::DIFFICULTY_LABELS,
        ];
    }

    public function submit(User $user, int $scheduleId, string $examDate, string $difficulty, ?string $note): void
    {
        if (! array_key_exists($difficulty, StudentExamDayFeedback::DIFFICULTY_LABELS)) {
            throw new \InvalidArgumentException('وضعیت آزمون امروز را انتخاب کنید.');
        }

        $schedule = StudentExamSchedule::query()
            ->where('user_id', $user->id)
            ->whereKey($scheduleId)
            ->whereNotNull('weekly_program_id')
            ->whereNotNull('program_built_at')
            ->firstOrFail();

        $examDate = Carbon::parse($examDate)->toDateString();
        $hasExamOnDate = $schedule->days()
            ->whereDate('exam_date', $examDate)
            ->exists();

        if (! $hasExamOnDate) {
            throw new \InvalidArgumentException('برای این تاریخ، امتحانی در برنامه امتحانی شما ثبت نشده است.');
        }

        StudentExamDayFeedback::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'student_exam_schedule_id' => $schedule->id,
                'exam_date' => $examDate,
            ],
            [
                'student_id' => $schedule->student_id,
                'difficulty' => $difficulty,
                'note' => $note,
                'submitted_at' => now(),
            ]
        );
    }

    private function examGroups(StudentExamSchedule $schedule): Collection
    {
        return $schedule->days
            ->groupBy(fn ($day) => $day->exam_date->toDateString())
            ->map(function (Collection $days, string $date) {
                $examDate = Carbon::parse($date)->startOfDay();

                return [
                    'exam_date' => $examDate,
                    'ends_at' => $examDate->copy()->setTimeFromTimeString(self::EXAM_END_TIME),
                    'feedback_opens_at' => $examDate->copy()->setTimeFromTimeString(self::FEEDBACK_OPEN_TIME),
                    'subjects' => $days
                        ->map(fn ($day) => $day->subject?->name)
                        ->filter()
                        ->values()
                        ->all(),
                ];
            })
            ->sortBy(fn (array $group) => $group['exam_date']->timestamp)
            ->values();
    }
}
