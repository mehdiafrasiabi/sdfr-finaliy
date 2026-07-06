<?php

namespace App\Livewire\Admin\EducationalManager\IncompleteRegistrations;

use App\Models\TrialWeek;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * مدیر آموزشی — ثبت‌نام‌های ناقص:
 *   not_started : ثبت‌نام کرده ولی هیچ آزمونی را شروع نکرده.
 *   abandoned   : آزمون را نیمه‌رها کرده و حداقل ۱ روز گذشته.
 *   pre_session : آزمون‌ها را تکمیل کرده ولی هنوز پیش‌جلسه را پر نکرده.
 */
class Index extends Component
{
    use WithPagination;

    public string $tab = 'not_started';

    public function setTab(string $tab): void
    {
        $this->tab = in_array($tab, ['not_started', 'abandoned', 'pre_session'], true) ? $tab : 'not_started';
        $this->resetPage();
    }

    /** زیرکوئری: آیا این کاربر حداقل یک تلاش آزمون دارد؟ */
    protected function hasAttemptExists($query, bool $exists)
    {
        $sub = fn ($q) => $q->select(DB::raw(1))
            ->from('student_assessment_attempts')
            ->whereColumn('student_assessment_attempts.user_id', 'trial_weeks.user_id');

        return $exists ? $query->whereExists($sub) : $query->whereNotExists($sub);
    }

    protected function baseQuery(string $tab)
    {
        $q = TrialWeek::query()->with(['user.personalInformation']);

        return match ($tab) {
            // ثبت‌نام کرده ولی هیچ آزمونی شروع نشده
            'not_started' => $this->hasAttemptExists(
                $q->whereNull('assessments_completed_at'),
                false
            ),
            // آزمون نیمه‌رها + حداقل ۱ روز گذشته
            'abandoned' => $this->hasAttemptExists(
                $q->whereNull('assessments_completed_at')
                  ->where('created_at', '<=', now()->subDay()),
                true
            ),
            // آزمون تمام، پیش‌جلسه پر نشده
            'pre_session' => $q->whereNotNull('assessments_completed_at')
                ->whereNull('pre_session_completed_at')
                ->where('status', '!=', TrialWeek::STATUS_PROGRAM_BUILT),
            default => $q->whereRaw('1 = 0'),
        };
    }

    public function render()
    {
        $counts = [
            'not_started' => $this->baseQuery('not_started')->count(),
            'abandoned'   => $this->baseQuery('abandoned')->count(),
            'pre_session' => $this->baseQuery('pre_session')->count(),
        ];

        $trials = $this->baseQuery($this->tab)->latest()->paginate(15);

        return view('livewire.admin.educational-manager.incomplete-registrations.index', [
            'trials' => $trials,
            'counts' => $counts,
        ])->layout('layouts.admin.app');
    }
}
