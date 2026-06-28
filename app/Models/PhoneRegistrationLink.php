<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * لینک یکتای ثبت‌نام مشاور جذب تلفنی (ردیابی تبدیل و پاداش).
 */
class PhoneRegistrationLink extends Model
{
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

    /** نشانی کامل لینک یکتا. */
    public function getUrlAttribute(): string
    {
        return route('client.phone-ref', ['token' => $this->token]);
    }
}
