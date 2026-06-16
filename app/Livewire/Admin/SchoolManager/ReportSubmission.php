<?php

namespace App\Livewire\Admin\SchoolManager;

use App\Models\DailyReport;
use App\Models\Student;
use Carbon\Carbon;
use Livewire\Component;

/**
 * گزارش ارسال «گزارش روزانهٔ مطالعه» در بازهٔ یک هفته (M9):
 *  - درصد دانش‌آموزانی که هر روز گزارش داده‌اند در برابر نداده‌اند.
 *  - گزارش‌های جبرانی (is_compensatory) شمارش نمی‌شوند.
 *  - بازه هفتگی است (شنبه تا جمعه) و با هر هفته قابل پیمایش/به‌روزرسانی است.
 */
class ReportSubmission extends Component
{
    public int $weekOffset = 0; // 0 = هفتهٔ جاری، منفی = هفته‌های قبل

    public function mount(): void
    {
        $this->schoolId();
    }

    private function schoolId(): ?int
    {
        $admin = auth('admin')->user();
        abort_unless($admin?->school_id || $admin?->hasRole('super admin'), 403);
        return $admin?->school_id;
    }

    public function prevWeek(): void { $this->weekOffset--; }
    public function nextWeek(): void { if ($this->weekOffset < 0) { $this->weekOffset++; } }

    private function weekStart(): Carbon
    {
        return Carbon::today()->addWeeks($this->weekOffset)->startOfWeek(Carbon::SATURDAY);
    }

    public function render()
    {
        $students = Student::with('user')
            ->where('school_id', $this->schoolId())
            ->get();

        $studentIds = $students->pluck('id');
        $total = $students->count();

        $start = $this->weekStart();
        $end   = $start->copy()->addDays(6)->endOfDay();

        $reports = DailyReport::whereIn('student_id', $studentIds)
            ->where('is_compensatory', false)
            ->whereBetween('report_date', [$start->toDateString(), $end->copy()->toDateString()])
            ->get(['student_id', 'report_date']);

        // تفکیک روزانه
        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $start->copy()->addDays($i);
            $dateStr = $date->toDateString();
            $sentStudents = $reports
                ->filter(fn($r) => Carbon::parse($r->report_date)->toDateString() === $dateStr)
                ->pluck('student_id')->unique();
            $sent = $sentStudents->count();

            $days[] = [
                'jalali'   => jdate($date)->format('l Y/m/d'),
                'sent'     => $sent,
                'not_sent' => max($total - $sent, 0),
                'percent'  => $total ? round($sent / $total * 100, 1) : 0,
            ];
        }

        // تعداد روزهای گزارش‌شده برای هر دانش‌آموز در این هفته
        $perStudentDays = $reports
            ->groupBy('student_id')
            ->map(fn($rows) => $rows->pluck('report_date')->map(fn($d) => Carbon::parse($d)->toDateString())->unique()->count());

        $studentRows = $students->map(fn($s) => [
            'name' => $s->user?->name ?? '—',
            'days' => (int) ($perStudentDays[$s->id] ?? 0),
        ])->sortBy('days')->values();

        // خلاصهٔ کل هفته (بر مبنای روز-دانش‌آموز)
        $expected = $total * 7;
        $actual   = $reports->map(fn($r) => $r->student_id . '|' . Carbon::parse($r->report_date)->toDateString())->unique()->count();

        return view('livewire.admin.school-manager.report-submission', [
            'days'        => $days,
            'studentRows' => $studentRows,
            'total'       => $total,
            'weekLabel'   => jdate($start)->format('Y/m/d') . ' تا ' . jdate($end)->format('Y/m/d'),
            'overallPercent' => $expected ? round($actual / $expected * 100, 1) : 0,
            'isCurrentWeek'  => $this->weekOffset === 0,
        ])->layout('layouts.admin.app');
    }
}
