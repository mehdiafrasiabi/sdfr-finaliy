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
        'spoke_with_people'        => 'array',
        'registration_probability' => 'integer',
        'is_definitive'            => 'boolean',
        'reminder_at'              => 'datetime',
        'called_at'                => 'datetime',
        'talk_duration_seconds'    => 'integer',
    ];

    const STAGE_DAY1      = 'day1';
    const STAGE_DAY3      = 'day3';
    const STAGE_DAY7      = 'day7';
    const STAGE_EMERGENCY = 'emergency';
    const STAGE_EXTRA     = 'extra';

    const STAGE_LABELS = [
        'day1'      => 'روز اول (ثبت‌نام)',
        'day3'      => 'روز سوم',
        'day7'      => 'روز هفتم',
        'emergency' => 'تماس اضطراری',
        'extra'     => 'تماس اضافه',
    ];

    /** حداکثر تعداد تماس در هر مرحله */
    const MAX_ATTEMPTS_PER_STAGE = 2;

    /** ترتیب مراحل اصلی و روزِ شروعِ سررسید آن‌ها از ابتدای هفتهٔ آزمایشی (۰-based). */
    const STAGE_DUE_DAY = [
        'day1' => 0,
        'day3' => 2,
        'day7' => 6,
    ];

    const FAIL_NO_ANSWER = 'no_answer';
    const FAIL_OFF = 'off';
    const FAIL_REJECTED = 'rejected';

    const FAIL_LABELS = [
        self::FAIL_NO_ANSWER => 'عدم پاسخ',
        self::FAIL_OFF => 'خاموش',
        self::FAIL_REJECTED => 'رد تماس',
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
        if (str_starts_with($this->stage, 'exam_week_')) {
            $week = (int) str_replace('exam_week_', '', $this->stage);

            return "تماس اجباری هفته {$week}";
        }

        return self::STAGE_LABELS[$this->stage] ?? $this->stage;
    }

    public function getSpokeWithLabelAttribute(): string
    {
        $people = collect($this->spoke_with_people ?? [])->filter()->values();
        if ($people->isNotEmpty()) {
            return $people->map(function ($person) {
                if ($person === 'other') {
                    return $this->spoke_with_other ?: 'سایر';
                }

                return AcquisitionContact::FOLLOW_UP_LABELS[$person] ?? $person;
            })->implode('، ');
        }

        if (! $this->spoke_with) {
            return '—';
        }
        return AcquisitionContact::FOLLOW_UP_LABELS[$this->spoke_with] ?? $this->spoke_with;
    }

    public function getFailLabelAttribute(): string
    {
        return self::FAIL_LABELS[$this->fail_reason] ?? '—';
    }

    public function getTalkDurationLabelAttribute(): string
    {
        $s = (int) $this->talk_duration_seconds;
        if ($s <= 0) {
            return '—';
        }

        return sprintf('%02d:%02d', intdiv($s, 60), $s % 60);
    }
}
