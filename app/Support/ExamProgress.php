<?php

namespace App\Support;

use App\Models\SchoolStudentGrade;
use App\Models\TypedExamAttempt;
use Illuminate\Support\Collection;

/**
 * محاسبه‌ی روند رشد/پسرفت دانش‌آموزان بر اساس دو منبع نمره:
 *  - نمرات ماهانه‌ی مدرسه (SchoolStudentGrade) که توسط مدیر/پشتیبان ثبت می‌شود.
 *  - آزمون‌های تایپی سیستم (TypedExamAttempt) با درصد ۰ تا ۱۰۰.
 *
 * خروجی هر متد سری زمانی ماهانه (شمسی) به‌همراه دلتای نسبت به بازه‌ی قبل است
 * تا در داشبورد و صفحه‌ی پیشرفت دانش‌آموز بازاستفاده شود.
 */
class ExamProgress
{
    /**
     * سری زمانی ماهانه‌ی نمرات مدرسه (نرمال‌شده بر مبنای ۱۰۰) برای مجموعه‌ای از دانش‌آموزان.
     *
     * @param  array|Collection  $studentIds
     * @return array<int, array{label:string, avg:float, count:int, delta:?float}>
     */
    public static function schoolGradeTrend($studentIds, int $months = 6): array
    {
        $studentIds = collect($studentIds);
        if ($studentIds->isEmpty()) {
            return [];
        }

        $rows = SchoolStudentGrade::whereIn('student_id', $studentIds)
            ->whereNotNull('recorded_at')
            ->orderBy('recorded_at')
            ->get(['score', 'scale', 'recorded_at']);

        $buckets = [];
        foreach ($rows as $row) {
            $key = self::jalaliMonthKey($row->recorded_at);
            // نرمال‌سازی نمره بر مبنای ۱۰۰
            $normalized = $row->scale === '20'
                ? ((float) $row->score) * 5
                : (float) $row->score;

            $buckets[$key]['sum'] = ($buckets[$key]['sum'] ?? 0) + $normalized;
            $buckets[$key]['count'] = ($buckets[$key]['count'] ?? 0) + 1;
        }

        return self::finalizeBuckets($buckets, $months);
    }

    /**
     * سری زمانی ماهانه‌ی درصد آزمون‌های تایپی (۰ تا ۱۰۰) برای مجموعه‌ای از دانش‌آموزان.
     *
     * @param  array|Collection  $studentIds
     * @return array<int, array{label:string, avg:float, count:int, delta:?float}>
     */
    public static function typedExamTrend($studentIds, int $months = 6): array
    {
        $studentIds = collect($studentIds);
        if ($studentIds->isEmpty()) {
            return [];
        }

        $rows = TypedExamAttempt::whereIn('student_id', $studentIds)
            ->where('is_finished', true)
            ->whereNotNull('submitted_at')
            ->orderBy('submitted_at')
            ->get(['score', 'submitted_at']);

        $buckets = [];
        foreach ($rows as $row) {
            $key = self::jalaliMonthKey($row->submitted_at);
            $buckets[$key]['sum'] = ($buckets[$key]['sum'] ?? 0) + (float) $row->score;
            $buckets[$key]['count'] = ($buckets[$key]['count'] ?? 0) + 1;
        }

        return self::finalizeBuckets($buckets, $months);
    }

    /**
     * یک عدد جمع‌بندی روند: دلتای میانگین آخرین بازه نسبت به بازه‌ی قبل.
     * مثبت = رشد، منفی = پسرفت، null = داده‌ی کافی نیست.
     */
    public static function latestDelta(array $trend): ?float
    {
        $last = end($trend);
        return $last['delta'] ?? null;
    }

    /**
     * تبدیل bucketها به سری مرتب با میانگین و دلتا، و محدود کردن به n ماه آخر.
     */
    private static function finalizeBuckets(array $buckets, int $months): array
    {
        ksort($buckets);

        $series = [];
        foreach ($buckets as $key => $b) {
            $series[] = [
                'key'   => $key,
                'label' => self::jalaliMonthLabel($key),
                'avg'   => round($b['sum'] / max($b['count'], 1), 1),
                'count' => $b['count'],
                'delta' => null,
            ];
        }

        $series = array_slice($series, -$months);

        // محاسبه‌ی دلتا نسبت به بازه‌ی قبل
        foreach ($series as $i => &$item) {
            if ($i > 0) {
                $item['delta'] = round($item['avg'] - $series[$i - 1]['avg'], 1);
            }
        }

        return $series;
    }

    /** کلید مرتب‌سازی ماه شمسی به‌صورت YYYYMM. */
    private static function jalaliMonthKey($date): string
    {
        $j = jalali($date);
        return $j->format('Y') . str_pad($j->format('m'), 2, '0', STR_PAD_LEFT);
    }

    /** برچسب خوانای ماه شمسی به‌صورت YYYY/MM. */
    private static function jalaliMonthLabel(string $key): string
    {
        return substr($key, 0, 4) . '/' . substr($key, 4, 2);
    }
}
