<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassSchedulePart extends Model
{
    protected $guarded = [];

    public function classSchedule(): BelongsTo
    {
        return $this->belongsTo(ClassSchedule::class);
    }

    public function ccSubject(): BelongsTo
    {
        return $this->belongsTo(CcSubject::class);
    }

    /**
     * نام روز هفته فارسی
     */
    public function getDayNameAttribute(): string
    {
        return ClassSchedule::getDayName($this->day_of_week);
    }
}

