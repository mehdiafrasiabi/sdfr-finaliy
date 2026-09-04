<?php

namespace App\Livewire\Admin\TrialAcquisition;

use App\Models\PhoneRegistrationLink;
use App\Models\TrialAcquisitionCall;
use App\Models\TrialWeek;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * داشبورد مرحله‌ای «مشاور جذب یک هفته آزمایشی».
 */
class Dashboard extends Index
{
    public ?string $selectedDashboardStage = null;
    public bool $showStageStudentsModal = false;
    public string $dashboardCallTitle = '';

    public function showStageStudents(string $stage): void
    {
        if (! array_key_exists($stage, $this->stageDefinitions())) {
            $this->dispatch('warning', 'مرحله انتخاب‌شده معتبر نیست.');
            return;
        }

        $this->selectedDashboardStage = $stage;
        $this->showStageStudentsModal = true;
    }

    public function closeStageStudentsModal(): void
    {
        $this->reset(['selectedDashboardStage', 'showStageStudentsModal']);
    }

    public function promptRegistrationFollowUp(int $trialId, string $stage = ''): void
    {
        $this->dashboardCallTitle = $this->stageDefinitions()[$stage]['label'] ?? 'پیگیری ثبت نام';
        $this->closeStageStudentsModal();
        $this->promptCall($trialId, TrialAcquisitionCall::STAGE_REGISTRATION_FOLLOW_UP);
    }

    protected function dashboardBaseQuery()
    {
        $query = TrialWeek::query()
            ->visibleForAcquisition()
            ->with([
                'user.personalInformation',
                'user.profile',
                'student.classSchedules',
                'student.examSchedules' => fn ($scheduleQuery) => $scheduleQuery
                    ->with(['days', 'weeklyProgram'])
                    ->latest('program_built_at')
                    ->latest('updated_at'),
            ]);

        if (! Auth::guard('admin')->user()?->hasRole('super admin')) {
            $query->where('acquisition_supporter_id', Auth::guard('admin')->id());
        }

        return $query;
    }

    protected function loadTrial(?int $trialId): ?TrialWeek
    {
        if (! $trialId) {
            return null;
        }

        return $this->dashboardBaseQuery()->find($trialId);
    }

    public function stageDefinitions(): array
    {
        return [
            'mindset' => [
                'number' => 1,
                'label' => 'پیگیری بخش تست مایندت',
                'color' => '#6f42c1',
            ],
            'classification' => [
                'number' => 2,
                'label' => 'پیگیری بخش طبقه بندی',
                'color' => '#198754',
            ],
            'pre_session' => [
                'number' => 3,
                'label' => 'پیگیری بخش برنامه کلاسی و پیش جلسه',
                'color' => '#fd7e14',
            ],
            'program_built' => [
                'number' => 4,
                'label' => 'پیگیری بخش ساخت برنامه',
                'color' => '#20c997',
            ],
            'exam_range' => [
                'number' => 5,
                'label' => 'پیگیری بخش وارد کرده بازه امتحانات',
                'color' => '#dc3545',
            ],
            'exam_days' => [
                'number' => 6,
                'label' => 'پیگیری بخش وارد کردن روز های امتحان',
                'color' => '#0dcaf0',
            ],
        ];
    }

    protected function dashboardStudents(): Collection
    {
        return $this->dashboardBaseQuery()
            ->latest()
            ->get()
            ->map(function (TrialWeek $trial) {
                $trial->current_dashboard_stage = $this->currentStageFor($trial);

                return $trial;
            });
    }

    public function currentStageFor(TrialWeek $trial): ?string
    {
        if (! $trial->hasCompletedAssessments()) {
            return 'mindset';
        }

        $schedule = $trial->student?->examSchedules?->first();
        if ($this->isExamProgramStudent($trial)) {
            if (! $schedule || ! $schedule->exam_starts_at || ! $schedule->exam_ends_at) {
                return 'exam_range';
            }

            if ($schedule->days->isEmpty()) {
                return 'exam_days';
            }

            return null;
        }

        $classificationDone = $trial->classification_locked_at !== null
            || in_array($trial->status, [
                TrialWeek::STATUS_CLASSIFICATION_DONE,
                TrialWeek::STATUS_PRE_SESSION_DONE,
                TrialWeek::STATUS_PROGRAM_BUILT,
            ], true);

        if (! $classificationDone) {
            return 'classification';
        }

        $preSessionDone = $trial->pre_session_completed_at !== null
            || in_array($trial->status, [
                TrialWeek::STATUS_PRE_SESSION_DONE,
                TrialWeek::STATUS_PROGRAM_BUILT,
            ], true);
        $hasClassSchedule = ! $trial->needsClassSchedule()
            || (bool) $trial->student?->classSchedules?->isNotEmpty();

        if (! $preSessionDone || ! $hasClassSchedule) {
            return 'pre_session';
        }

        if ($trial->program_built_at !== null || $trial->status === TrialWeek::STATUS_PROGRAM_BUILT) {
            return null;
        }

        return 'program_built';
    }

    protected function isExamProgramStudent(TrialWeek $trial): bool
    {
        if ($trial->student?->examSchedules?->isNotEmpty()) {
            return true;
        }

        return PhoneRegistrationLink::query()
            ->where('registered_user_id', $trial->user_id)
            ->where('plan', PhoneRegistrationLink::PLAN_EXAM)
            ->exists();
    }

    protected function dashboardStages(Collection $students): Collection
    {
        return collect($this->stageDefinitions())
            ->map(function (array $stage, string $key) use ($students) {
                $stage['key'] = $key;
                $stage['count'] = $students->where('current_dashboard_stage', $key)->count();

                return $stage;
            })
            ->values();
    }

    public function subjectLabel(TrialWeek $trial): string
    {
        return ($trial->student?->examSchedules?->isNotEmpty() ?? false)
            ? 'بازه امتحانات'
            : 'یک هفته آزمایشی';
    }

    protected function uniqueLinkRegistrationStats(): array
    {
        $links = PhoneRegistrationLink::query()
            ->with(['user.trialWeek.student.examSchedules'])
            ->where('admin_id', Auth::guard('admin')->id())
            ->whereNotNull('registered_user_id')
            ->latest('used_at')
            ->get()
            ->unique('registered_user_id')
            ->filter(fn (PhoneRegistrationLink $link) => $this->hasCompletedUniqueLinkRegistration($link))
            ->values();

        $examCount = $links->filter(function (PhoneRegistrationLink $link) {
            $hasExamProgram = $link->user?->trialWeek?->student?->examSchedules?->isNotEmpty() ?? false;

            return $link->plan === PhoneRegistrationLink::PLAN_EXAM || $hasExamProgram;
        })->count();

        $trialCount = $links->filter(function (PhoneRegistrationLink $link) {
            $hasExamProgram = $link->user?->trialWeek?->student?->examSchedules?->isNotEmpty() ?? false;

            return $link->plan === PhoneRegistrationLink::PLAN_TRIAL
                || ($link->plan === PhoneRegistrationLink::PLAN_DEFAULT && ! $hasExamProgram);
        })->count();

        return [
            'total' => $links->count(),
            'trial' => $trialCount,
            'exam' => $examCount,
        ];
    }

    protected function hasCompletedUniqueLinkRegistration(PhoneRegistrationLink $link): bool
    {
        $trial = $link->user?->trialWeek;

        if (! $trial || $trial->acq_disinterest_status !== null) {
            return false;
        }

        if ($this->isExamProgramStudent($trial)) {
            return (bool) $trial->student?->examSchedules?->contains(function ($schedule) {
                return $schedule->weekly_program_id !== null && $schedule->program_built_at !== null;
            });
        }

        return $trial->program_built_at !== null || $trial->status === TrialWeek::STATUS_PROGRAM_BUILT;
    }

    protected function registrationSourceMeta(Collection $students): array
    {
        $userIds = $students->pluck('user_id')->filter()->unique()->values();

        if ($userIds->isEmpty()) {
            return [];
        }

        $phoneAcquisitionUserIds = PhoneRegistrationLink::query()
            ->where('admin_id', Auth::guard('admin')->id())
            ->whereIn('registered_user_id', $userIds)
            ->pluck('registered_user_id')
            ->unique()
            ->flip();

        return $students->mapWithKeys(function (TrialWeek $trial) use ($phoneAcquisitionUserIds) {
            $isMine = $trial->user_id && $phoneAcquisitionUserIds->has($trial->user_id);

            return [
                $trial->id => [
                    'label' => $isMine ? 'از طریق جذب تلفنی من' : 'مراجعه به سایت',
                    'class' => $isMine ? 'bg-primary-subtle text-primary border-primary-subtle' : 'bg-body-tertiary text-body border',
                ],
            ];
        })->all();
    }

    public function render()
    {
        $students = $this->dashboardStudents();
        $selectedStage = $this->selectedDashboardStage
            ? ($this->stageDefinitions()[$this->selectedDashboardStage] ?? null)
            : null;

        $selectedStudents = $this->selectedDashboardStage
            ? $students->where('current_dashboard_stage', $this->selectedDashboardStage)->take(100)->values()
            : collect();

        $activeTrial = $this->activeTrialId ? $this->loadTrial($this->activeTrialId) : null;

        return view('livewire.admin.trial-acquisition.dashboard', [
            'stages' => $this->dashboardStages($students),
            'uniqueLinkRegistrationStats' => $this->uniqueLinkRegistrationStats(),
            'selectedStage' => $selectedStage,
            'selectedStudents' => $selectedStudents,
            'registrationSources' => $this->registrationSourceMeta($selectedStudents),
            'activeTrial' => $activeTrial,
        ])->layout('layouts.admin.app');
    }
}
