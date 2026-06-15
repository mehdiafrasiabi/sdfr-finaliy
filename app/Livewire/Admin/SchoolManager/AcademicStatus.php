<?php

namespace App\Livewire\Admin\SchoolManager;

use App\Models\SchoolStudentGrade;
use App\Models\Student;
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

    protected $paginationTheme = 'bootstrap';

    public function updatingGradeFilter(): void { $this->resetPage(); }
    public function updatingStarFilter(): void { $this->resetPage(); }

    private function schoolId(): ?int
    {
        $admin = auth('admin')->user();
        abort_unless($admin?->school_id || $admin?->hasRole('super admin'), 403);
        return $admin?->school_id;
    }

    public function render()
    {
        $schoolId = $this->schoolId();

        $base = Student::where('school_id', $schoolId);

        $starCounts = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0];
        $counts = (clone $base)->selectRaw('star, COUNT(*) as c')->groupBy('star')->pluck('c', 'star');
        foreach ($starCounts as $k => $v) {
            $starCounts[$k] = (int) ($counts[$k] ?? 0);
        }

        $students = (clone $base)
            ->with('user.personalInformation')
            ->when($this->gradeFilter, fn($q) => $q->where('grade', $this->gradeFilter))
            ->when($this->starFilter, fn($q) => $q->where('star', $this->starFilter))
            ->orderBy('grade')
            ->paginate(20);

        $averages = collect();
        if ($students->count()) {
            $averages = SchoolStudentGrade::whereIn('student_id', $students->pluck('id'))
                ->get(['student_id', 'score', 'scale'])
                ->groupBy('student_id')
                ->map(function ($rows) {
                    $sum = $rows->sum(fn($r) => $r->scale === '20' ? ((float) $r->score) * 5 : (float) $r->score);
                    return round($sum / max($rows->count(), 1), 1);
                });
        }

        return view('livewire.admin.school-manager.academic-status', [
            'students'   => $students,
            'starCounts' => $starCounts,
            'averages'   => $averages,
        ])->layout('layouts.admin.app');
    }
}
