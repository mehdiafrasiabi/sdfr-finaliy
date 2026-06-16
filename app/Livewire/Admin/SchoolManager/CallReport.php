<?php

namespace App\Livewire\Admin\SchoolManager;

use App\Models\SchoolParentContact;
use App\Models\SmartReportCard;
use App\Models\Student;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

/**
 * گزارش تماس‌های ماهانه با اولیای دانش‌آموزان مدرسه (M11):
 *  - تعداد تماس در ماه و اینکه با چه کسی (پدر/مادر) صحبت شده.
 *  - هدف: حداقل ۳ تماس در ماه برای هر دانش‌آموز؛ کسری‌ها مشخص می‌شوند.
 */
class CallReport extends Component
{
    public const MONTHLY_TARGET = 3;

    public const WITH_LABELS = [
        'father' => 'پدر',
        'mother' => 'مادر',
    ];

    public int $jalaliYear;
    public int $jalaliMonth;

    public ?int $expandedStudentId = null;

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

    public function toggleStudent(int $studentId): void
    {
        $this->expandedStudentId = $this->expandedStudentId === $studentId ? null : $studentId;
    }

    public function render()
    {
        $students = Student::with('user')
            ->where('school_id', $this->schoolId())
            ->get();
        $studentIds = $students->pluck('id');

        $range = SmartReportCard::jalaliMonthRange($this->jalaliYear, $this->jalaliMonth);

        $contacts = SchoolParentContact::with('admin')
            ->whereIn('student_id', $studentIds)
            ->whereBetween('contacted_at', [$range['start'], $range['end']])
            ->orderByDesc('contacted_at')
            ->get();

        $byStudent = $contacts->groupBy('student_id');

        $perStudent = $students->map(function (Student $s) use ($byStudent) {
            $rows = $byStudent->get($s->id) ?? collect();
            $count = $rows->count();
            return [
                'student_id' => $s->id,
                'name'       => $s->user?->name ?? '—',
                'count'      => $count,
                'father'     => $rows->where('contacted_with', 'father')->count(),
                'mother'     => $rows->where('contacted_with', 'mother')->count(),
                'met'        => $count >= self::MONTHLY_TARGET,
                'calls'      => $rows->map(fn($c) => [
                    'with'    => self::WITH_LABELS[$c->contacted_with] ?? $c->contacted_with,
                    'by'      => $c->admin?->name ?? '—',
                    'date'    => $c->contacted_at ? jdate($c->contacted_at)->format('Y/m/d H:i') : '—',
                    'notes'   => $c->notes,
                ])->values(),
            ];
        })->sortBy('count')->values();

        $studentCount = $students->count();

        return view('livewire.admin.school-manager.call-report', [
            'perStudent'   => $perStudent,
            'monthNames'   => SmartReportCard::MONTH_NAMES,
            'yearOptions'  => range($this->jalaliYear, $this->jalaliYear - 2),
            'target'       => self::MONTHLY_TARGET,
            'totalCalls'   => $contacts->count(),
            'expected'     => $studentCount * self::MONTHLY_TARGET,
            'metCount'     => $perStudent->where('met', true)->count(),
            'studentCount' => $studentCount,
        ])->layout('layouts.admin.app');
    }
}
