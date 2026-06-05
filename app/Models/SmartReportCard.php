<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Morilog\Jalali\Jalalian;

class SmartReportCard extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'activated_at' => 'datetime',
    ];

    public const MONTH_NAMES = [
        1 => 'فروردین',
        2 => 'اردیبهشت',
        3 => 'خرداد',
        4 => 'تیر',
        5 => 'مرداد',
        6 => 'شهریور',
        7 => 'مهر',
        8 => 'آبان',
        9 => 'آذر',
        10 => 'دی',
        11 => 'بهمن',
        12 => 'اسفند',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function getMonthNameAttribute(): string
    {
        return self::MONTH_NAMES[$this->jalali_month] ?? '-';
    }

    public function getJalaliStartAttribute(): string
    {
        return jdate($this->start_date)->format('Y/m/d');
    }

    public function getJalaliEndAttribute(): string
    {
        return jdate($this->end_date)->format('Y/m/d');
    }

    public static function monthDaysCount(int $month): int
    {
        if ($month >= 1 && $month <= 6) {
            return 31;
        }
        if ($month >= 7 && $month <= 12) {
            return 30;
        }
        return 30;
    }

    public static function jalaliMonthRange(int $year, int $month): array
    {
        $days = self::monthDaysCount($month);
        $start = Jalalian::fromFormat('Y/m/d', sprintf('%04d/%02d/%02d', $year, $month, 1))->toCarbon()->startOfDay();
        $end = Jalalian::fromFormat('Y/m/d', sprintf('%04d/%02d/%02d', $year, $month, $days))->toCarbon()->endOfDay();
        return ['start' => $start, 'end' => $end, 'days' => $days];
    }
}
