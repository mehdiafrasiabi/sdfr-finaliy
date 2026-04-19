<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminWorkSchedule extends Model
{
    protected $fillable = [
        'admin_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * روزهای هفته به ترتیب هفته ایرانی (شنبه = 0).
     */
    public const DAYS = [
        0 => 'شنبه',
        1 => 'یکشنبه',
        2 => 'دوشنبه',
        3 => 'سه‌شنبه',
        4 => 'چهارشنبه',
        5 => 'پنجشنبه',
        6 => 'جمعه',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function getDayNameAttribute(): string
    {
        return self::DAYS[$this->day_of_week] ?? '';
    }

    /**
     * بررسی می‌کند که آیا در یک روز و ساعت مشخص، ادمین در ساعت کاری است یا خیر.
     *
     * @param  int    $dayOfWeek  0..6
     * @param  string $time       HH:MM یا HH:MM:SS
     */
    public function coversTime(int $dayOfWeek, string $time): bool
    {
        if (!$this->is_active || $this->day_of_week !== $dayOfWeek) {
            return false;
        }

        return $time >= substr($this->start_time, 0, 5)
            && $time <  substr($this->end_time, 0, 5);
    }
}
