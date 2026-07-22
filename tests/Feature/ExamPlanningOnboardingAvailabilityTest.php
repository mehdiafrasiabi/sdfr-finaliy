<?php

namespace Tests\Feature;

use App\Models\ExamPlanningSetting;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ExamPlanningOnboardingAvailabilityTest extends TestCase
{
    use DatabaseTransactions;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_exam_onboarding_allows_manager_calendar_when_exam_range_is_open(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-07-22 12:00:00', config('app.timezone')));

        $studentInput = $this->setting([
            'grade' => 10,
            'field' => 'math',
            'input_mode' => ExamPlanningSetting::INPUT_STUDENT,
            'activation_starts_at' => '2026-07-20',
            'activation_ends_at' => '2026-07-30',
        ]);

        $managerInExamRange = $this->setting([
            'grade' => 11,
            'field' => 'experimental',
            'input_mode' => ExamPlanningSetting::INPUT_MANAGER,
            'activation_starts_at' => '2026-06-01',
            'activation_ends_at' => '2026-06-30',
            'exam_starts_at' => '2026-07-20',
            'exam_ends_at' => '2026-07-30',
        ]);

        $managerOutOfExamRange = $this->setting([
            'grade' => 12,
            'field' => 'human',
            'input_mode' => ExamPlanningSetting::INPUT_MANAGER,
            'activation_starts_at' => '2026-06-01',
            'activation_ends_at' => '2026-06-30',
            'exam_starts_at' => '2026-08-01',
            'exam_ends_at' => '2026-08-10',
        ]);

        $availableIds = ExamPlanningSetting::query()
            ->whereKey([$studentInput->id, $managerInExamRange->id, $managerOutOfExamRange->id])
            ->availableForExamOnboarding(now())
            ->pluck('id')
            ->all();

        $this->assertContains($studentInput->id, $availableIds);
        $this->assertContains($managerInExamRange->id, $availableIds);
        $this->assertNotContains($managerOutOfExamRange->id, $availableIds);
    }

    private function setting(array $attributes): ExamPlanningSetting
    {
        return ExamPlanningSetting::query()->create(array_merge([
            'term_type' => ExamPlanningSetting::TERM_FINAL,
            'max_daily_study_hours' => 12,
            'is_active' => true,
        ], $attributes));
    }
}
