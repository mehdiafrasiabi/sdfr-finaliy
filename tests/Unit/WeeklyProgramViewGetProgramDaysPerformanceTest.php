<?php

namespace Tests\Unit;

use App\Livewire\Client\Profile\Consultation\WeeklyProgramView;
use App\Models\ProgramPart;
use App\Models\WeeklyProgram;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * WeeklyProgramView::render() هر پارت برنامه را یک‌بار با eager loading کامل
 * می‌خواند (parts.lesson, parts.ccSubject, ...)، اما تا قبل از این بهینه‌سازی،
 * getProgramDays() دوباره از صفر یک کوئری ProgramPart::where(...)->with([...])
 * می‌زد تا همان پارت‌ها را برای ساخت جدول روزهای هفته بخواند — یعنی روی هر
 * render (هر کلیک روی توقف/ادامه/...) یک کوئری کاملاً تکراری.
 *
 * این تست مستقیماً متد عمومی getProgramDays() را روی کامپوننت صدا می‌زند و
 * تضمین می‌کند که:
 *  ۱) وقتی رابطه‌ی parts از قبل (مثل render()) لود شده، خروجی دقیقاً همان
 *     چیزی است که با کوئری جداگانه به‌دست می‌آمد (بدون تغییر منطق).
 *  ۲) در آن حالت دیگر هیچ کوئری اضافه‌ای زده نمی‌شود، و وقتی لود نشده،
 *     رفتار قبلی (زدن کوئری) دست‌نخورده باقی می‌ماند.
 */
class WeeklyProgramViewGetProgramDaysPerformanceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_get_program_days_matches_with_and_without_eager_loaded_parts(): void
    {
        $program = $this->createProgramWithParts();

        $lazyDays = $this->buildComponent($program->fresh())->getProgramDays();
        $eagerDays = $this->buildComponent($program->fresh()->load('parts'))->getProgramDays();

        $this->assertSame(count($lazyDays), count($eagerDays));

        foreach ($lazyDays as $index => $day) {
            $this->assertSame($day['jalali_date'], $eagerDays[$index]['jalali_date']);
            $this->assertSame($day['parts_count'], $eagerDays[$index]['parts_count']);
            $this->assertSame($day['total_hours'], $eagerDays[$index]['total_hours']);
            $this->assertSame($day['total_tests'], $eagerDays[$index]['total_tests']);
            $this->assertSame(
                $day['parts']->pluck('id')->sort()->values()->all(),
                $eagerDays[$index]['parts']->pluck('id')->sort()->values()->all()
            );
        }
    }

    public function test_get_program_days_uses_preloaded_parts_without_extra_query(): void
    {
        $program = $this->createProgramWithParts();

        $eagerProgram = $program->fresh()->load('parts');
        $eagerComponent = $this->buildComponent($eagerProgram);
        DB::flushQueryLog();
        DB::enableQueryLog();
        $eagerComponent->getProgramDays();
        $eagerQueryCount = count(DB::getQueryLog());
        DB::disableQueryLog();

        $this->assertSame(0, $eagerQueryCount, 'وقتی parts از قبل eager-load شده، getProgramDays نباید کوئری جدیدی بزند.');

        $lazyProgram = $program->fresh();
        $lazyComponent = $this->buildComponent($lazyProgram);
        DB::flushQueryLog();
        DB::enableQueryLog();
        $lazyComponent->getProgramDays();
        $lazyQueryCount = count(DB::getQueryLog());
        DB::disableQueryLog();

        $this->assertGreaterThanOrEqual(1, $lazyQueryCount, 'رفتار قدیمی (بدون eager load) باید دست‌نخورده بماند.');
    }

    private function buildComponent(WeeklyProgram $weeklyProgram): WeeklyProgramView
    {
        $component = new WeeklyProgramView();
        $component->weeklyProgram = $weeklyProgram;
        $component->restDays = [];

        return $component;
    }

    private function createProgramWithParts(): WeeklyProgram
    {
        /** @var WeeklyProgram $program */
        $program = WeeklyProgram::factory()->create([
            'start_date' => '2026-01-03',
            'end_date' => '2026-01-09',
        ]);

        $specs = [
            ['part_date' => '2026-01-03', 'day_of_week' => 0, 'part_order' => 2, 'duration_minutes' => 60, 'test_count' => 10],
            ['part_date' => '2026-01-03', 'day_of_week' => 0, 'part_order' => 1, 'duration_minutes' => 30, 'test_count' => 5],
            ['part_date' => '2026-01-05', 'day_of_week' => 2, 'part_order' => 1, 'duration_minutes' => 90, 'test_count' => null],
        ];

        foreach ($specs as $spec) {
            ProgramPart::factory()->for($program, 'weeklyProgram')->create($spec);
        }

        return $program;
    }
}
