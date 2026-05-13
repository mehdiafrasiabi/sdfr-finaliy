<?php

namespace App\Services;

use App\Models\WeeklyProgram;

/**
 * قوانین ساخت برنامه هفتگی:
 *  1. در هر یک از ۷ روز برنامه، حداقل یک پارت از انواع منبع
 *     (روز خوانی / پیش‌خوانی / طبقه‌بندی / …) باید وجود داشته باشد. — بدون استثنا.
 *  2. حداقل یک ساعت (60 دقیقه) مطالعه در هر روز.
 *  3. هر پارت حداقل ۱۵ دقیقه.
 *
 * @return string[] لیست خطاها (خالی = ولید)
 */
class WeeklyProgramValidator
{
    public const MIN_PART_MINUTES = 15;
    public const MIN_DAILY_MINUTES = 60;
    public const DAYS_OF_WEEK = [0, 1, 2, 3, 4, 5, 6];

    public function validate(WeeklyProgram $program): array
    {
        $errors = [];
        $parts = $program->parts()->get()->groupBy('day_of_week');

        foreach (self::DAYS_OF_WEEK as $day) {
            $dayParts = $parts->get($day, collect());

            if ($dayParts->isEmpty()) {
                $errors[] = "روز {$day}: حداقل یک پارت باید تعریف شود.";
                continue;
            }

            $totalMinutes = $dayParts->sum('duration_minutes');
            if ($totalMinutes < self::MIN_DAILY_MINUTES) {
                $errors[] = "روز {$day}: مجموع زمان مطالعه باید حداقل ۶۰ دقیقه باشد (فعلی: {$totalMinutes}).";
            }

            foreach ($dayParts as $part) {
                if ((int) $part->duration_minutes < self::MIN_PART_MINUTES) {
                    $errors[] = "روز {$day}: پارت با زمان کمتر از ۱۵ دقیقه مجاز نیست.";
                    break;
                }
            }
        }

        return $errors;
    }
}
