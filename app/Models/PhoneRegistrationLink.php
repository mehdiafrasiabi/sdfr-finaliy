<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * لینک یکتای ثبت‌نام مشاور جذب تلفنی (ردیابی تبدیل و پاداش).
 */
class PhoneRegistrationLink extends Model
{
    public const PLAN_DEFAULT = 'default';
    public const PLAN_TRIAL = 'trial';
    public const PLAN_EXAM = 'exam';

    protected $guarded = [];

    protected $casts = [
        'sent_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(PhoneLead::class, 'phone_lead_id');
    }

    /** مشاور جذب تلفنی که لینک را ارسال کرده است. */
    public function consultant(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /** کاربری که با این لینک ثبت‌نام کرده است. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_user_id');
    }

    public function isConverted(): bool
    {
        return $this->used_at !== null;
    }

    public static function isValidPlan(string $plan): bool
    {
        return in_array($plan, [
            self::PLAN_DEFAULT,
            self::PLAN_TRIAL,
            self::PLAN_EXAM,
        ], true);
    }

    /** نشانی کامل لینک یکتا. */
    public function getUrlAttribute(): string
    {
        return rtrim(config('services.melipayamak.public_url', 'https://sdfr.me'), '/') . '/r/' . $this->token;
    }
}
