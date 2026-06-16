<?php

namespace App\Livewire\Admin\SchoolManager;

use App\Exports\SchoolGradesReportExport;
use App\Exports\SchoolRankingExport;
use App\Models\SchoolStudentGrade;
use App\Models\SmartReportCard;
use App\Models\Student;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Morilog\Jalali\Jalalian;

/**
 * نمای مدیر مدرسه برای کارنامهٔ ماهانه:
 *  - مشاهدهٔ نمرات هر درس هر دانش‌آموز (فعالیت کلاسی، امتحان، معدل، نظر دبیر)
 *  - رتبه‌بندی به‌ازای هر پایه+رشته بر اساس فرمول (فعالیت×۱ + امتحان×۳) ÷ ۴
 *  - خروجی اکسل از نمرات و رتبه‌بندی
 */
class ReportCards extends Component
{
    public const FIELD_LABELS = [
        'math'         => 'ریاضی',
        'experimental' => 'تجربی',
        'human'        => 'انسانی',
    ];

    public int $jalaliYear;
    public int $jalaliMonth;

    public string $gradeFilter = '';
    public string $fieldFilter = '';

    public ?int $expandedStudentId = null;

    public function mount(): void
    {
        $this->schoolId(); // اعتبارسنجی دسترسی
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

    public function jalaliMonthKey(): string
    {
        return sprintf('%04d-%02d', $this->jalaliYear, $this->jalaliMonth);
    }

    public function toggleStudent(int $studentId): void
    {
        $this->expandedStudentId = $this->expandedStudentId === $studentId ? null : $studentId;
    }

    /**
     * محاسبهٔ رتبه‌بندی + جزئیات نمرات برای ماه و فیلترهای جاری.
     * خروجی: collection گروه‌بندی‌شده بر اساس «پایه + رشته».
     */
    private function buildRanking()
    {
        $schoolId = $this->schoolId();
        $month    = $this->jalaliMonthKey();

        $students = Student::with('user')
            ->where('school_id', $schoolId)
            ->whereNotNull('grade')
            ->when($this->gradeFilter, fn($q) => $q->where('grade', $this->gradeFilter))
            ->when($this->fieldFilter, fn($q) => $q->where('field', $this->fieldFilter))
            ->get();

        $grades = SchoolStudentGrade::with('subject')
            ->whereIn('student_id', $students->pluck('id'))
            ->where('jalali_month', $month)
            ->get()
            ->groupBy('student_id');

        $rows = $students->map(function (Student $student) use ($grades) {
            $studentGrades = $grades->get($student->id) ?? collect();

            $subjects = $studentGrades->map(fn($g) => [
                'subject'         => $g->subject?->name ?? '—',
                'class_activity'  => $g->class_activity !== null ? (float) $g->class_activity : null,
                'exam'            => $g->exam !== null ? (float) $g->exam : null,
                'average'         => $g->subject_average,
                'teacher_comment' => $g->teacher_comment,
            ])->values();

            $averages = $subjects->pluck('average')->filter(fn($v) => $v !== null);
            $overall  = $averages->isNotEmpty() ? round($averages->avg(), 2) : null;

            return [
                'student_id'  => $student->id,
                'name'        => $student->user?->name ?? '—',
                'grade'       => $student->grade,
                'field'       => $student->field,
                'field_label' => self::FIELD_LABELS[$student->field] ?? ($student->field ?: 'بدون رشته'),
                'subjects'    => $subjects,
                'overall'     => $overall,
            ];
        });

        // گروه‌بندی بر اساس پایه+رشته و سپس رتبه‌دهی داخل هر گروه.
        return $rows
            ->groupBy(fn($r) => $r['grade'] . '|' . ($r['field'] ?: 'none'))
            ->map(function ($group) {
                $sorted = $group->sortByDesc(fn($r) => $r['overall'] ?? -1)->values();
                return $sorted->map(function ($r, $i) {
                    $r['rank'] = $r['overall'] !== null ? $i + 1 : null;
                    return $r;
                });
            })
            ->sortKeys();
    }

    public function exportGrades()
    {
        return Excel::download(
            new SchoolGradesReportExport($this->buildRanking()),
            'school-grades-' . $this->jalaliMonthKey() . '.xlsx'
        );
    }

    public function exportRanking()
    {
        return Excel::download(
            new SchoolRankingExport($this->buildRanking()),
            'school-ranking-' . $this->jalaliMonthKey() . '.xlsx'
        );
    }

    public function render()
    {
        return view('livewire.admin.school-manager.report-cards', [
            'groups'      => $this->buildRanking(),
            'monthNames'  => SmartReportCard::MONTH_NAMES,
            'yearOptions' => range($this->jalaliYear, $this->jalaliYear - 2),
            'fieldLabels' => self::FIELD_LABELS,
        ])->layout('layouts.admin.app');
    }
}
