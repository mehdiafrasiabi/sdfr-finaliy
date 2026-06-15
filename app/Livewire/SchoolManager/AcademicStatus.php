<?php

namespace App\Livewire\SchoolManager;

use App\Models\SchoolStudentGrade;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * وضعیت تحصیلی دانش‌آموزان مدرسه: پایه، رشته، طبقه‌بندی (star)، وضعیت مدرسه،
 * و آخرین میانگین نمره (نرمال‌شده بر مبنای ۱۰۰).
 */
class AcademicStatus extends Component
{
    use WithPagination;

    public string $gradeFilter = '';
    public string $starFilter = '';

    public function updatingGradeFilter(): void { $this->resetPage(); }
    public function updatingStarFilter(): void { $this->resetPage(); }

    public function render()
    {
        $school = auth('school-manager')->user()?->school;

        // خلاصه‌ی تعداد در هر طبقه‌بندی
        $starCounts = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0];
        if ($school) {
            $counts = $school->students()
                ->selectRaw('star, COUNT(*) as c')
                ->groupBy('star')->pluck('c', 'star');
            foreach ($starCounts as $k => $v) {
                $starCounts[$k] = (int) ($counts[$k] ?? 0);
            }
        }

        $students = $school
            ? $school->students()
                ->with('user.personalInformation')
                ->when($this->gradeFilter, fn($q) => $q->where('grade', $this->gradeFilter))
                ->when($this->starFilter, fn($q) => $q->where('star', $this->starFilter))
                ->orderBy('grade')
                ->paginate(20)
            : null;

        // آخرین میانگین نمره (نرمال‌شده) برای دانش‌آموزان این صفحه
        $averages = collect();
        if ($students && $students->count()) {
            $ids = $students->pluck('id');
            $averages = SchoolStudentGrade::whereIn('student_id', $ids)
                ->get(['student_id', 'score', 'scale'])
                ->groupBy('student_id')
                ->map(function ($rows) {
                    $sum = $rows->sum(fn($r) => $r->scale === '20' ? ((float) $r->score) * 5 : (float) $r->score);
                    return round($sum / max($rows->count(), 1), 1);
                });
        }

        return view('livewire.school-manager.academic-status', [
            'students'   => $students,
            'starCounts' => $starCounts,
            'averages'   => $averages,
        ])->layout('layouts.school-manager.app');
    }
}
