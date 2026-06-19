<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * یک تلاش تماس مشاور جذب تلفنی با یک شماره.
 */
class PhoneCall extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'connected'      => 'boolean',
        'attempt_number' => 'integer',
        'willingness'    => 'integer',
        'called_at'      => 'datetime',
        'follow_up_at'   => 'datetime',
    ];

    // دلایل ناموفق بودن تماس
    const FAIL_NO_ANSWER = 'no_answer';
    const FAIL_OFF       = 'off';
    const FAIL_REJECTED  = 'rejected';
    const FAIL_WRONG     = 'wrong';

    const FAIL_LABELS = [
        'no_answer' => 'عدم پاسخ',
        'off'       => 'خاموش',
        'rejected'  => 'رد تماس',
        'wrong'     => 'شماره اشتباه',
    ];

    // تماس‌های ناموفقی که قابل پیگیری مجدد هستند (کادر زنده می‌مانند)
    const RETRYABLE_FAILS = [
        self::FAIL_NO_ANSWER,
        self::FAIL_OFF,
        self::FAIL_REJECTED,
    ];

    // نتیجهٔ تماس موفق
    const RESULT_REGISTERED  = 'registered';
    const RESULT_FOLLOW_UP   = 'follow_up';
    const RESULT_NO_INTEREST = 'no_interest';

    const RESULT_LABELS = [
        'registered'  => 'ثبت‌نام',
        'follow_up'   => 'پیگیری',
        'no_interest' => 'عدم تمایل',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(PhoneLead::class, 'phone_lead_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function getFailLabelAttribute(): string
    {
        return self::FAIL_LABELS[$this->fail_reason] ?? '—';
    }

    public function getResultLabelAttribute(): string
    {
        return self::RESULT_LABELS[$this->result] ?? '—';
    }

    /**
     * با چه شخصی صحبت شد — هم‌راستا با AcquisitionContact::FOLLOW_UP_LABELS.
     */
    public function getSpokeWithLabelAttribute(): string
    {
        if (! $this->spoke_with) {
            return '—';
        }
        return AcquisitionContact::FOLLOW_UP_LABELS[$this->spoke_with] ?? $this->spoke_with;
    }
}
