<?php

namespace Tests\Unit;

use App\Models\ProgramPart;
use App\Models\WeeklyProgram;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * صفحه‌ی نمایش برنامه هفتگی (weekly-program-view) روی هر عملیات تایمر
 * (شروع/توقف/ادامه/پایان) یک render() کامل لیوایر اجرا می‌کند. قبل از این
 * بهینه‌سازی، هرکدام از accessor های آماری مدل WeeklyProgram (total_hours,
 * total_parts, test_parts_count, ...) حتی وقتی رابطه‌ی parts از قبل با
 * eager loading لود شده بود، دوباره یک کوئری COUNT/SUM جداگانه به دیتابیس
 * می‌زدند؛ یعنی روی هر کلیک ساده مثل «توقف» ده‌ها کوئری اضافه و تکراری.
 *
 * این تست تضمین می‌کند که:
 *  ۱) مقدار خروجی این accessor ها دقیقاً همانی است که قبل از بهینه‌سازی بود
 *     (چه رابطه‌ی parts لود شده باشد چه نشده باشد) — یعنی هیچ تغییری در
 *     منطق/نتیجه ایجاد نشده.
 *  ۲) وقتی رابطه‌ی parts از قبل لود شده، دیگر کوئری اضافه‌ای زده نمی‌شود
 *     (گارد رگرسیون کارایی).
 */
class WeeklyProgramStatsPerformanceTest extends TestCase
{
    use DatabaseTransactions;

    private const STAT_ATTRIBUTES = [
        'total_hours',
        'total_tests',
        'total_parts',
        'total_plans',
        'test_parts_count',
        'descriptive_parts_count',
        'video_parts_count',
        'general_parts_count',
        'specialized_parts_count',
        'grade_10_parts_count',
        'grade_11_parts_count',
        'grade_12_parts_count',
    ];

    public function test_stat_accessors_match_with_and_without_eager_loaded_parts(): void
    {
        $program = $this->createProgramWithParts();

        $lazy = WeeklyProgram::query()->find($program->id);
        $eager = WeeklyProgram::query()->with('parts')->find($program->id);

        $this->assertFalse($lazy->relationLoaded('parts'));
        $this->assertTrue($eager->relationLoaded('parts'));

        foreach (self::STAT_ATTRIBUTES as $attribute) {
            $this->assertSame(
                $lazy->{$attribute},
                $eager->{$attribute},
                "مقدار {$attribute} باید صرف‌نظر از eager-load بودن رابطه‌ی parts یکسان بماند."
            );
        }
    }

    public function test_eager_loaded_parts_relation_avoids_redundant_stat_queries(): void
    {
        $program = $this->createProgramWithParts();

        $lazy = WeeklyProgram::query()->find($program->id);
        DB::flushQueryLog();
        DB::enableQueryLog();
        foreach (self::STAT_ATTRIBUTES as $attribute) {
            $lazy->{$attribute};
        }
        $lazyQueryCount = count(DB::getQueryLog());
        DB::disableQueryLog();

        $eager = WeeklyProgram::query()->with('parts')->find($program->id);
        DB::flushQueryLog();
        DB::enableQueryLog();
        foreach (self::STAT_ATTRIBUTES as $attribute) {
            $eager->{$attribute};
        }
        $eagerQueryCount = count(DB::getQueryLog());
        DB::disableQueryLog();

        // قبل از بهینه‌سازی، این عدد برابر با تعداد accessor ها (۱۲ کوئری) بود.
        $this->assertSame(12, $lazyQueryCount, 'رفتار قدیمی (بدون eager load) باید دست‌نخورده بماند.');

        // بعد از بهینه‌سازی، وقتی رابطه از قبل لود شده، دیگر نباید هیچ کوئری اضافه‌ای زده شود.
        $this->assertSame(0, $eagerQueryCount, 'وقتی parts از قبل eager-load شده، محاسبه‌ی آمار نباید کوئری جدیدی بزند.');
    }

    private function createProgramWithParts(): WeeklyProgram
    {
        /** @var WeeklyProgram $program */
        $program = WeeklyProgram::factory()->create();

        $specs = [
            ['duration_minutes' => 60, 'test_count' => 10, 'part_type' => 'test', 'lesson_type' => 'general', 'grade' => '10'],
            ['duration_minutes' => 90, 'test_count' => 5, 'part_type' => 'descriptive', 'lesson_type' => 'specialized', 'grade' => '11'],
            ['duration_minutes' => 45, 'test_count' => null, 'part_type' => 'video', 'lesson_type' => 'general', 'grade' => '12'],
            ['duration_minutes' => 30, 'test_count' => 20, 'part_type' => 'test', 'lesson_type' => 'specialized', 'grade' => '10'],
        ];

        foreach ($specs as $spec) {
            ProgramPart::factory()->for($program, 'weeklyProgram')->create($spec);
        }

        return $program;
    }
}
