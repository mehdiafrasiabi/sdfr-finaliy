<?php

namespace App\Livewire\SchoolManager;

use App\Models\AdvisingSession;
use App\Models\Student;
use Livewire\Component;

/**
 * آمار جلسات مشاوره برای دانش‌آموزان مدرسه:
 *  - تعداد کل، برگزارشده، غیبت دانش‌آموز، غیبت مشاور
 *  - تفکیک به‌ازای هر دانش‌آموز
 */
class AdvisingStats extends Component
{
    public function render()
    {
        $school = auth('school-manager')->user()?->school;
        $studentIds = $school ? $school->students()->pluck('id') : collect();

        $sessions = AdvisingSession::whereIn('student_id', $studentIds)->get();

        $summary = [
            'total'          => $sessions->count(),
            'held'           => $sessions->where('result_status', AdvisingSession::RESULT_HELD)->count(),
            'student_absent' => $sessions->where('result_status', AdvisingSession::RESULT_STUDENT_ABSENT)->count(),
            'advisor_absent' => $sessions->where('result_status', AdvisingSession::RESULT_ADVISOR_ABSENT)->count(),
        ];

        // تفکیک به‌ازای هر دانش‌آموز
        $byStudentRaw = $sessions->groupBy('student_id');
        $students = Student::with('user')
            ->whereIn('id', $byStudentRaw->keys())
            ->get()
            ->keyBy('id');

        $perStudent = $byStudentRaw->map(function ($rows, $sid) use ($students) {
            return [
                'student'        => $students->get($sid),
                'total'          => $rows->count(),
                'held'           => $rows->where('result_status', AdvisingSession::RESULT_HELD)->count(),
                'student_absent' => $rows->where('result_status', AdvisingSession::RESULT_STUDENT_ABSENT)->count(),
                'advisor_absent' => $rows->where('result_status', AdvisingSession::RESULT_ADVISOR_ABSENT)->count(),
            ];
        })->sortByDesc('total')->values();

        return view('livewire.school-manager.advising-stats', [
            'summary'    => $summary,
            'perStudent' => $perStudent,
        ])->layout('layouts.school-manager.app');
    }
}
