<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassSchedule extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_finalized' => 'boolean',
        'finalized_at' => 'datetime',
    ];

    // نام‌های روزهای هفته
    const DAY_NAMES = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];

    // حداکثر پارت در هر روز
    const MAX_PARTS_PER_DAY = 5;

    // حداقل پارت مورد نیاز برای ثبت نهایی کل برنامه
    const MIN_REQUIRED_PARTS = 1;

    // روزهای اجباری (غیرفعال)
    const MANDATORY_DAYS = [];
    // روزهای اختیاری (همه روزها)
    const OPTIONAL_DAYS = [0, 1, 2, 3, 4, 5, 6];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function parts(): HasMany
    {
        return $this->hasMany(ClassSchedulePart::class);
    }

    public function partsForDay(int $dayOfWeek): HasMany
    {
        return $this->parts()->where('day_of_week', $dayOfWeek)->orderBy('part_order');
    }

    /**
     * بررسی اینکه آیا شرایط ثبت نهایی رعایت شده
     */
    public function canFinalize(): bool
    {
        // شنبه تا چهارشنبه باید حداقل 3 پارت داشته باشند
        return $this->parts()->count() >= self::MIN_REQUIRED_PARTS;
    }

    /**
     * تعداد پارت‌های یک روز
     */
    public function partsCountForDay(int $dayOfWeek): int
    {
        return $this->parts()->where('day_of_week', $dayOfWeek)->count();
    }

    /**
     * شماره پارت بعدی قابل باز شدن برای یک روز
     */
    public function nextAvailablePartOrder(int $dayOfWeek): int
    {
        $lastPart = $this->parts()
            ->where('day_of_week', $dayOfWeek)
            ->orderByDesc('part_order')
            ->first();

        return $lastPart ? $lastPart->part_order + 1 : 1;
    }

    /**
     * نام روز فارسی
     */
    public static function getDayName(int $dayOfWeek): string
    {
        return self::DAY_NAMES[$dayOfWeek] ?? '-';
    }
}
