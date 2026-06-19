<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * یک تماس در جریان «مشاور جذب یک هفته آزمایشی».
 */
class TrialAcquisitionCall extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'answered'                 => 'boolean',
        'attempt_number'           => 'integer',
        'checklist'                => 'array',
        'registration_probability' => 'integer',
        'is_definitive'            => 'boolean',
        'reminder_at'              => 'datetime',
        'called_at'                => 'datetime',
    ];

    const STAGE_DAY1      = 'day1';
    const STAGE_DAY3      = 'day3';
    const STAGE_DAY7      = 'day7';
    const STAGE_EMERGENCY = 'emergency';

    const STAGE_LABELS = [
        'day1'      => 'روز اول (ثبت‌نام)',
        'day3'      => 'روز سوم',
        'day7'      => 'روز هفتم',
        'emergency' => 'تماس اضطراری',
    ];

    /** حداکثر تعداد تماس در هر مرحله */
    const MAX_ATTEMPTS_PER_STAGE = 2;

    /** ترتیب مراحل اصلی و روزِ شروعِ سررسید آن‌ها از ابتدای هفتهٔ آزمایشی (۰-based). */
    const STAGE_DUE_DAY = [
        'day1' => 0,
        'day3' => 2,
        'day7' => 6,
    ];

    public function trialWeek(): BelongsTo
    {
        return $this->belongsTo(TrialWeek::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function getStageLabelAttribute(): string
    {
        return self::STAGE_LABELS[$this->stage] ?? $this->stage;
    }

    public function getSpokeWithLabelAttribute(): string
    {
        if (! $this->spoke_with) {
            return '—';
        }
        return AcquisitionContact::FOLLOW_UP_LABELS[$this->spoke_with] ?? $this->spoke_with;
    }
}
