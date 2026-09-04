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
        'connected'             => 'boolean',
        'attempt_number'        => 'integer',
        'talk_duration_seconds' => 'integer',
        'spoke_with_people'     => 'array',
        'called_at'             => 'datetime',
        'answered_at'           => 'datetime',
        'follow_up_at'          => 'datetime',
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
    const RESULT_REGISTRATION_FOLLOW_UP = 'reg_fu';
    const RESULT_FOLLOW_UP   = 'fu';
    const RESULT_NO_INTEREST = 'no_int';

    const DISINTEREST_TEMPORARY = 'temporary';
    const DISINTEREST_DEFINITIVE = 'definitive';

    const RESULT_LABELS = [
        'registered' => 'ثبت نام',
        'reg_fu'     => 'نیاز به پیگیری مجدد ثبت نام',
        'registration_follow_up' => 'نیاز به پیگیری مجدد ثبت نام',
        'fu'         => 'نیاز پیگیری مجدد',
        'follow_up'  => 'نیاز پیگیری مجدد',
        'no_int'     => 'عدم تمایل',
        'no_interest' => 'عدم تمایل',
    ];

    const DISINTEREST_LABELS = [
        self::DISINTEREST_TEMPORARY => 'عدم تمایل موقت',
        self::DISINTEREST_DEFINITIVE => 'عدم تمایل قطعی',
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
        return self::labelForOutcome($this->result);
    }

    public static function labelForOutcome(?string $outcome): string
    {
        if (! $outcome) {
            return '—';
        }

        return self::FAIL_LABELS[$outcome]
            ?? self::RESULT_LABELS[$outcome]
            ?? self::DISINTEREST_LABELS[$outcome]
            ?? 'نامشخص';
    }

    public function getDisinterestStatusLabelAttribute(): string
    {
        return self::DISINTEREST_LABELS[$this->disinterest_status] ?? '—';
    }

    /**
     * با چه شخصی صحبت شد — هم‌راستا با AcquisitionContact::FOLLOW_UP_LABELS.
     */
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

    /**
     * رنگ تماس بر اساس شمارهٔ تلاش (هم‌راستا با رنگ‌بندی لید).
     */
    public function getColorAttribute(): string
    {
        $n = max(1, min((int) $this->attempt_number, 5));

        return PhoneLead::COLOR_BY_ATTEMPT[$n] ?? 'secondary';
    }

    /**
     * مدت مکالمه به‌صورت mm:ss (برای نمایش در لیست‌ها/جزئیات).
     */
    public function getTalkDurationLabelAttribute(): string
    {
        $s = (int) $this->talk_duration_seconds;
        if ($s <= 0) {
            return '—';
        }
        return sprintf('%02d:%02d', intdiv($s, 60), $s % 60);
    }
}
