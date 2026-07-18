<?php

namespace App\Livewire\Admin\TrialAcquisition;

use App\Models\TrialAcquisitionCall;
use App\Models\TrialWeek;
use Carbon\Carbon;

class ExamProgramStudents extends Index
{
    public const STAGE_WEEK_PREFIX = 'exam_week_';

    protected function applyStudentTypeScope($query)
    {
        return $query->whereHas(
            'student.examSchedules',
            fn ($scheduleQuery) => $this->builtExamProgramConstraint($scheduleQuery)
        );
    }

    protected function builtExamProgramConstraint($query): void
    {
        $query->whereNotNull('weekly_program_id')
            ->whereNotNull('program_built_at');
    }

    protected function pageView(): string
    {
        return 'livewire.admin.trial-acquisition.exam-program-students';
    }

    protected function pageMeta(): array
    {
        return [
            'pageBreadcrumb' => 'دانش‌آموزان برنامه امتحانی',
            'pageTitle' => 'دانش‌آموزان برنامه امتحانی من',
            'pageSubtitle' => 'فقط دانش‌آموزانی که برنامه امتحانی‌شان ساخته و نهایی شده است.',
            'emptyMessage' => 'دانش‌آموز برنامه امتحانی برای نمایش وجود ندارد.',
        ];
    }

    protected function isAllowedCallStage(string $stage): bool
    {
        return $stage === TrialAcquisitionCall::STAGE_EMERGENCY
            || $stage === TrialAcquisitionCall::STAGE_DAY1
            || (bool) preg_match('/^' . self::STAGE_WEEK_PREFIX . '[1-9][0-9]*$/', $stage);
    }

    public function requiresCallSubject(string $stage): bool
    {
        return false;
    }

    public function requiresProbability(string $stage): bool
    {
        return false;
    }

    public function requiresDefinitiveConfirmation(string $stage): bool
    {
        return false;
    }

    public function stageLabel(string $stage): string
    {
        if (str_starts_with($stage, self::STAGE_WEEK_PREFIX)) {
            $week = (int) str_replace(self::STAGE_WEEK_PREFIX, '', $stage);

            return "تماس اجباری هفته {$week}";
        }

        return $stage === TrialAcquisitionCall::STAGE_DAY1
            ? 'تماس اولیه و خوش‌آمدگویی'
            : parent::stageLabel($stage);
    }

    public function stageMetaFor(TrialWeek $trial, $now): array
    {
        $schedule = $trial->student?->examSchedules?->first();
        $program = $schedule?->weeklyProgram;
        $start = $program?->start_date
            ? Carbon::parse($program->start_date)->startOfDay()
            : ($schedule?->exam_starts_at?->copy()->startOfDay() ?: Carbon::parse($trial->program_built_at ?: $trial->created_at)->startOfDay());
        $end = $program?->end_date
            ? Carbon::parse($program->end_date)->startOfDay()
            : ($schedule?->exam_ends_at?->copy()->startOfDay() ?: ($schedule?->access_expires_at?->copy()->subDay()->startOfDay() ?: $start->copy()));

        $programDays = max(1, (int) $start->diffInDays($end) + 1);
        $weeklyCallCount = max(1, intdiv($programDays, 7));
        $daysSinceStart = (int) $start->diffInDays($now->copy()->startOfDay(), false);

        $meta = [
            TrialAcquisitionCall::STAGE_DAY1 => [
                'label' => 'تماس اولیه و خوش‌آمدگویی',
                'due' => true,
            ],
        ];

        for ($week = 1; $week <= $weeklyCallCount; $week++) {
            $meta[self::STAGE_WEEK_PREFIX . $week] = [
                'label' => "تماس هفته {$week}",
                'due' => $daysSinceStart >= (($week * 7) - 1),
            ];
        }

        return $meta;
    }
}
