<?php

namespace Tests\Feature;

use App\Livewire\Client\Profile\Consultation\WeeklyProgramView;
use App\Models\AdvisingSession;
use App\Models\ProgramPart;
use App\Models\Student;
use App\Models\User;
use App\Models\WeeklyProgram;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * این تست، جریان کامل شروع/توقف/ادامه/لغوِ تایمرِ صفحه‌ی نمایش برنامه‌ی
 * هفتگی را از طریق خودِ کامپوننت لیوایر (یعنی با اجرای واقعی mount + render
 * روی هر اکشن) اجرا می‌کند تا مطمئن شویم بهینه‌سازیِ کوئری‌های تکراری
 * (accessor های آماری WeeklyProgram و کوئری دوباره‌ی getProgramDays)
 * هیچ تغییری در رفتار/منطق این عملیات‌ها ایجاد نکرده است؛ و علاوه‌بر آن،
 * تعداد کوئری‌های اجراشده روی یک عملیات ساده مثل «توقف» را به‌عنوان گارد
 * رگرسیون کارایی زیر یک سقف منطقی نگه می‌دارد.
 */
class WeeklyProgramViewTimerActionsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_pause_resume_and_cancel_preserve_existing_timer_behavior(): void
    {
        [$user, $program, $part] = $this->setUpActiveProgramWithPart();

        session(['study_permission_granted' => true]);

        $test = Livewire::actingAs($user)->test(WeeklyProgramView::class, ['program' => $program]);

        $test->assertSet('isActiveProgram', true);

        $test->call('startPart', $part->id)
            ->assertSet('currentPartId', $part->id)
            ->assertSet('isRunning', true)
            ->assertSet('pausedAtTs', null);

        $test->call('pausePart')
            ->assertSet('isRunning', false)
            ->assertSet('currentPartId', $part->id);
        $this->assertNotNull($test->get('pausedAtTs'));

        $test->call('resumePart')
            ->assertSet('isRunning', true)
            ->assertSet('pausedAtTs', null);

        $test->call('openCancelConfirm')->assertSet('showCancelConfirmModal', true);

        $test->call('cancelPart')
            ->assertSet('showCancelConfirmModal', false)
            ->assertSet('currentPartId', null)
            ->assertSet('isRunning', false);
    }

    public function test_pausing_the_timer_does_not_run_a_flood_of_duplicate_queries(): void
    {
        [$user, $program, $part] = $this->setUpActiveProgramWithPart();

        session(['study_permission_granted' => true]);

        $test = Livewire::actingAs($user)->test(WeeklyProgramView::class, ['program' => $program]);
        $test->call('startPart', $part->id);

        DB::flushQueryLog();
        DB::enableQueryLog();
        $test->call('pausePart');
        $queryCount = count(DB::getQueryLog());
        DB::disableQueryLog();

        // پیش از بهینه‌سازی، فقط accessor های آماری WeeklyProgram (۱۲ کوئری) بعلاوه‌ی
        // یک کوئری تکراری در getProgramDays روی هر توقف/ادامه اضافه می‌شد. این سقف
        // به‌قدر کافی سخت‌گیرانه هست که اگر آن کوئری‌های تکراری برگردند، تست fail شود.
        $this->assertLessThan(
            25,
            $queryCount,
            "توقف تایمر {$queryCount} کوئری اجرا کرد؛ این نشان‌دهنده‌ی بازگشتِ کوئری‌های تکراری N+1 است."
        );
    }

    /**
     * @return array{0: User, 1: WeeklyProgram, 2: ProgramPart}
     */
    private function setUpActiveProgramWithPart(): array
    {
        $user = User::query()->create([
            'name' => 'Weekly Program Tester',
            'email' => uniqid('weekly-program-test-', true) . '@example.test',
            'mobile' => '09' . random_int(100000000, 999999999),
            'password' => 'password',
        ]);

        $student = Student::query()->create([
            'user_id' => $user->id,
            'advisor_id' => null,
            'payment_id' => null,
            'star' => 'D',
            'is_trial' => true,
        ]);

        $advisingSession = AdvisingSession::factory()->create([
            'student_id' => $student->id,
            'result_status' => AdvisingSession::RESULT_HELD,
            'activation_date' => now()->subDay()->format('Y-m-d'),
        ]);

        $program = WeeklyProgram::factory()->create([
            'student_id' => $student->id,
            'advising_session_id' => $advisingSession->id,
        ]);

        $part = ProgramPart::factory()->for($program, 'weeklyProgram')->create([
            'duration_minutes' => 10,
        ]);

        return [$user, $program, $part];
    }
}
