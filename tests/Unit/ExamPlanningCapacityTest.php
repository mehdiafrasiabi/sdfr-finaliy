<?php

namespace Tests\Unit;

use App\Models\StudentExamSchedule;
use App\Models\StudentExamScheduleDay;
use App\Services\ExamPlanningService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Tests\TestCase;

class ExamPlanningCapacityTest extends TestCase
{
    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_tomorrow_exam_keeps_one_full_daily_capacity(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-07-25 09:00:00'));

        $capacity = $this->capacityForExamDate('2026-07-26', 10);

        $this->assertSame(1, $capacity['day_count']);
        $this->assertSame(600, $capacity['capacity_minutes']);
        $this->assertSame(600, $capacity['remaining_minutes']);
        $this->assertSame(90, $capacity['required_night_before_minutes']);
    }

    public function test_exam_after_tomorrow_keeps_today_and_tomorrow_capacity(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-07-25 09:00:00'));

        $capacity = $this->capacityForExamDate('2026-07-27', 10);

        $this->assertSame(2, $capacity['day_count']);
        $this->assertSame(1200, $capacity['capacity_minutes']);
        $this->assertSame(1200, $capacity['remaining_minutes']);
        $this->assertSame(90, $capacity['required_night_before_minutes']);
    }

    public function test_exam_program_report_card_unlocks_one_day_before_access_expires(): void
    {
        $service = new ExamPlanningService();
        $schedule = new StudentExamSchedule([
            'access_expires_at' => Carbon::parse('2026-07-30 23:59:59'),
        ]);

        $this->assertFalse($service->examScheduleReportCardUnlocked(
            $schedule,
            Carbon::parse('2026-07-28 12:00:00')
        ));

        $this->assertTrue($service->examScheduleReportCardUnlocked(
            $schedule,
            Carbon::parse('2026-07-29 00:00:00')
        ));
    }

    public function test_exam_program_report_card_unlock_uses_exam_end_when_access_expiry_is_missing(): void
    {
        $service = new ExamPlanningService();
        $schedule = new StudentExamSchedule([
            'exam_ends_at' => '2026-07-29',
        ]);

        $this->assertFalse($service->examScheduleReportCardUnlocked(
            $schedule,
            Carbon::parse('2026-07-28 12:00:00')
        ));

        $this->assertTrue($service->examScheduleReportCardUnlocked(
            $schedule,
            Carbon::parse('2026-07-29 00:00:00')
        ));
    }

    public function test_automatic_trial_report_card_unlock_rule_is_one_day_before_access_expires(): void
    {
        $service = new ExamPlanningService();
        $accessExpiresAt = Carbon::parse('2026-07-08 23:59:59');

        $this->assertFalse($service->reportCardUnlockedForAccessExpiry(
            $accessExpiresAt,
            Carbon::parse('2026-07-06 12:00:00')
        ));

        $this->assertTrue($service->reportCardUnlockedForAccessExpiry(
            $accessExpiresAt,
            Carbon::parse('2026-07-07 00:00:00')
        ));
    }

    private function capacityForExamDate(string $examDate, int $dailyHours): array
    {
        $subjectId = 123;
        $schedule = new StudentExamSchedule([
            'max_daily_study_hours' => $dailyHours,
        ]);

        $schedule->setRelation('days', new EloquentCollection([
            new StudentExamScheduleDay([
                'cc_subject_id' => $subjectId,
                'exam_date' => $examDate,
            ]),
        ]));
        $schedule->setRelation('allocations', new EloquentCollection());

        $capacityData = (new ExamPlanningService())->buildCapacityData($schedule);

        return $capacityData['subjects'][$subjectId];
    }
}
