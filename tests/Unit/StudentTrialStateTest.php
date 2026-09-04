<?php

namespace Tests\Unit;

use App\Models\Student;
use App\Models\TrialWeek;
use Carbon\Carbon;
use Tests\TestCase;

class StudentTrialStateTest extends TestCase
{
    public function test_historical_trial_does_not_lock_chat_for_paid_student(): void
    {
        $student = new Student(['is_trial' => false]);
        $student->setRelation('trialWeek', new TrialWeek([
            'status' => TrialWeek::STATUS_PENDING,
            'expires_at' => Carbon::now()->addWeek(),
        ]));

        $this->assertFalse($student->isAdvisorChatLocked());
        $this->assertFalse($student->hasActiveTrialAccess());
    }

    public function test_incomplete_trial_locks_chat_for_trial_student(): void
    {
        $student = new Student(['is_trial' => true]);
        $student->setRelation('trialWeek', new TrialWeek([
            'status' => TrialWeek::STATUS_PENDING,
            'expires_at' => Carbon::now()->addWeek(),
        ]));

        $this->assertTrue($student->isAdvisorChatLocked());
        $this->assertTrue($student->hasActiveTrialAccess());
    }

    public function test_completed_active_trial_unlocks_chat_without_becoming_paid(): void
    {
        $student = new Student(['is_trial' => true]);
        $student->setRelation('trialWeek', new TrialWeek([
            'status' => TrialWeek::STATUS_PROGRAM_BUILT,
            'expires_at' => Carbon::now()->addWeek(),
        ]));

        $this->assertFalse($student->isAdvisorChatLocked());
        $this->assertTrue($student->hasActiveTrialAccess());
    }
}
