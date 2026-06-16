<?php

namespace App\Livewire\Admin\SchoolManager;

use App\Models\AdvisingSession;
use App\Models\SmartReportCard;
use App\Models\Student;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

/**
 * آمار جلسات مشاورهٔ ماهانه برای دانش‌آموزان مدرسه (M10):
 *  - تفکیک کل/برگزارشده/غیبت در ماه انتخابی (بر اساس تاریخ فعال‌سازی جلسه)
 *  - هدف: هر دانش‌آموز حداقل ۳ جلسه (برنامه) در ماه؛ کسری‌ها مشخص می‌شوند.
 */
class AdvisingStats extends Component
{
    public const MONTHLY_TARGET = 3;

    public int $jalaliYear;
    public int $jalaliMonth;

    public function mount(): void
    {
        $this->schoolId();
        $now = Jalalian::now();
        $this->jalaliYear  = (int) $now->getYear();
        $this->jalaliMonth = (int) $now->getMonth();
    }

    private function schoolId(): ?int
    {
        $admin = auth('admin')->user();
        abort_unless($admin?->school_id || $admin?->hasRole('super admin'), 403);
        return $admin?->school_id;
    }

    public function render()
    {
        $students = Student::with('user')
            ->where('school_id', $this->schoolId())
            ->get();
        $studentIds = $students->pluck('id');

        $range = SmartReportCard::jalaliMonthRange($this->jalaliYear, $this->jalaliMonth);

        $sessions = AdvisingSession::whereIn('student_id', $studentIds)
            ->whereBetween('activation_date', [$range['start']->toDateString(), $range['end']->toDateString()])
            ->get(['student_id', 'result_status']);

        $byStudent = $sessions->groupBy('student_id');

        $summary = [
            'total'          => $sessions->count(),
            'held'           => $sessions->where('result_status', AdvisingSession::RESULT_HELD)->count(),
            'student_absent' => $sessions->where('result_status', AdvisingSession::RESULT_STUDENT_ABSENT)->count(),
            'advisor_absent' => $sessions->where('result_status', AdvisingSession::RESULT_ADVISOR_ABSENT)->count(),
        ];

        // همهٔ دانش‌آموزان فهرست می‌شوند تا کسری‌ها هم دیده شوند.
        $perStudent = $students->map(function (Student $s) use ($byStudent) {
            $rows = $byStudent->get($s->id) ?? collect();
            $held = $rows->where('result_status', AdvisingSession::RESULT_HELD)->count();
            return [
                'name'           => $s->user?->name ?? '—',
                'total'          => $rows->count(),
                'held'           => $held,
                'student_absent' => $rows->where('result_status', AdvisingSession::RESULT_STUDENT_ABSENT)->count(),
                'advisor_absent' => $rows->where('result_status', AdvisingSession::RESULT_ADVISOR_ABSENT)->count(),
                'met'            => $held >= self::MONTHLY_TARGET,
            ];
        })->sortBy('held')->values();

        $studentCount = $students->count();

        return view('livewire.admin.school-manager.advising-stats', [
            'summary'      => $summary,
            'perStudent'   => $perStudent,
            'monthNames'   => SmartReportCard::MONTH_NAMES,
            'yearOptions'  => range($this->jalaliYear, $this->jalaliYear - 2),
            'target'       => self::MONTHLY_TARGET,
            'expected'     => $studentCount * self::MONTHLY_TARGET,
            'metCount'     => $perStudent->where('met', true)->count(),
            'studentCount' => $studentCount,
        ])->layout('layouts.admin.app');
    }
}
