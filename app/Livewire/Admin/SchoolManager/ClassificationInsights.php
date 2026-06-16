<?php

namespace App\Livewire\Admin\SchoolManager;

use App\Models\Student;
use App\Support\ClassificationProgress;
use Livewire\Component;

/**
 * تحلیل طبقه‌بندی دروس برای مدیر مدرسه:
 *  - هشدار: در هر پایه+رشته دانش‌آموزان در کدام دروس ضعیف‌اند (M6)
 *  - دروسی که بیشترین پیشرفت در آن‌ها رخ داده (M7)
 */
class ClassificationInsights extends Component
{
    public const FIELD_LABELS = [
        'math'         => 'ریاضی',
        'experimental' => 'تجربی',
        'human'        => 'انسانی',
    ];

    public string $gradeFilter = '';
    public string $fieldFilter = '';

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

    public function render()
    {
        $userIds = Student::where('school_id', $this->schoolId())
            ->whereNotNull('grade')
            ->when($this->gradeFilter, fn($q) => $q->where('grade', $this->gradeFilter))
            ->when($this->fieldFilter, fn($q) => $q->where('field', $this->fieldFilter))
            ->pluck('user_id')
            ->filter()
            ->values()
            ->all();

        return view('livewire.admin.school-manager.classification-insights', [
            'weakSubjects'  => ClassificationProgress::weakSubjects($userIds),
            'topProgress'   => ClassificationProgress::topProgressSubjects($userIds),
            'fieldLabels'   => self::FIELD_LABELS,
            'studentCount'  => count($userIds),
        ])->layout('layouts.admin.app');
    }
}
