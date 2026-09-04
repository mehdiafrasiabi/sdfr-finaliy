<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * شمارهٔ «مشاور جذب تلفنی».
 */
class PhoneLead extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'grade'          => 'integer',
        'attempts_count' => 'integer',
        'next_call_at'   => 'datetime',
        'disinterest_at' => 'datetime',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_CLOSED = 'closed';
    const STATUS_DEAD   = 'dead';

    const MAX_ATTEMPTS = 5;

    /**
     * رنگ لید بر اساس تعداد تماس‌ها (کلاس رنگ بوت‌استرپ):
     * ۱ آبی، ۲ سبز، ۳ زرد، ۴ قرمز، ۵ خاکستری.
     */
    const COLOR_BY_ATTEMPT = [
        1 => 'primary',   // آبی
        2 => 'success',   // سبز
        3 => 'warning',   // زرد
        4 => 'danger',    // قرمز
        5 => 'secondary', // خاکستری
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(PhoneLeadAssignment::class);
    }

    public function activeAssignment(): HasOne
    {
        return $this->hasOne(PhoneLeadAssignment::class)
            ->where('status', PhoneLeadAssignment::STATUS_ACTIVE)
            ->latestOfMany();
    }

    public function calls(): HasMany
    {
        return $this->hasMany(PhoneCall::class)->orderBy('called_at');
    }

    public function registrationLinks(): HasMany
    {
        return $this->hasMany(PhoneRegistrationLink::class, 'phone_lead_id');
    }

    public function latestRegistrationLink(): HasOne
    {
        return $this->hasOne(PhoneRegistrationLink::class, 'phone_lead_id')->latestOfMany();
    }

    /**
     * رنگ نمایش لید (کلاس رنگ بوت‌استرپ). خاکستری اگر شماره مرده/خاکستری باشد،
     * در غیر این صورت بر اساس تعداد تماس (سقف رنگِ قرمز برای ۴ به بالا).
     */
    public function getColorAttribute(): string
    {
        if ($this->status === self::STATUS_DEAD) {
            return 'secondary';
        }

        $n = max(1, min((int) $this->attempts_count, 4));

        return self::COLOR_BY_ATTEMPT[$n] ?? 'primary';
    }

    /** برچسب فارسی علت خاکستری‌شدن. */
    public function getGreyReasonLabelAttribute(): ?string
    {
        if (! $this->grey_reason) {
            return null;
        }

        return PhoneCall::FAIL_LABELS[$this->grey_reason] ?? $this->grey_reason;
    }

    public function getLastOutcomeLabelAttribute(): string
    {
        return PhoneCall::labelForOutcome($this->last_outcome);
    }

    public function getGradeLabelAttribute(): string
    {
        if (! $this->grade) {
            return '—';
        }
        return TrialWeek::GRADE_LABELS[$this->grade] ?? "پایه {$this->grade}";
    }

    public function getFieldLabelAttribute(): string
    {
        if (! $this->field) {
            return '—';
        }
        return TrialWeek::FIELD_LABELS[$this->field] ?? $this->field;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'در جریان',
            self::STATUS_CLOSED => 'بسته‌شده',
            self::STATUS_DEAD   => 'خاکستری',
            default             => 'نامشخص',
        };
    }

    public function getDisinterestStatusLabelAttribute(): string
    {
        return PhoneCall::DISINTEREST_LABELS[$this->disinterest_status] ?? '—';
    }

    public function canStartPhoneAcquisitionCall(bool $allowEarlyDisinterestCall = false): bool
    {
        if ($this->isExhausted()) {
            return false;
        }

        if ($this->disinterest_status === PhoneCall::DISINTEREST_DEFINITIVE) {
            return false;
        }

        if (
            $this->disinterest_status === PhoneCall::DISINTEREST_TEMPORARY
            && $this->next_call_at
            && $this->next_call_at->isFuture()
            && ! $allowEarlyDisinterestCall
        ) {
            return false;
        }

        return true;
    }

    public function getDisinterestCallLockMessageAttribute(): ?string
    {
        if ($this->disinterest_status === PhoneCall::DISINTEREST_DEFINITIVE) {
            return 'برای این شماره عدم تمایل قطعی ثبت شده و اجازه تماس مجدد وجود ندارد.';
        }

        if (
            $this->disinterest_status === PhoneCall::DISINTEREST_TEMPORARY
            && $this->next_call_at
            && $this->next_call_at->isFuture()
        ) {
            return 'این شماره تا موعد عدم تمایل موقت قفل است. برای تماس قبل از موعد، گزینه تماس زودهنگام را بزنید.';
        }

        return null;
    }

    public function isExhausted(): bool
    {
        return $this->status === self::STATUS_DEAD;
    }

    /**
     * شماره‌هایی که موعد تماس بعدی‌شان رسیده (سررسیدهٔ پیگیری/تماس مجدد).
     */
    public function scopeDueForCall($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
            ->whereNotNull('next_call_at')
            ->where('next_call_at', '<=', now());
    }

    /**
     * وضعیت لید پس از یک تماس را تعیین می‌کند (منطق چرخهٔ حیات).
     *
     * @param int         $attempt   شمارهٔ این تلاش (پس از افزایش)
     * @param bool        $connected آیا تماس برقرار شد؟
     * @param string|null $failReason در صورت ناموفق
     * @param string|null $result     در صورت موفق
     */
    public static function statusAfterOutcome(int $attempt, bool $connected, ?string $failReason, ?string $result): string
    {
        if (! $connected) {
            // شماره اشتباه → خاکستری فوری
            if ($failReason === PhoneCall::FAIL_WRONG) {
                return self::STATUS_DEAD;
            }
            // عدم پاسخ/خاموش/رد تماس → کادر زنده می‌ماند
            return self::STATUS_ACTIVE;
        }

        // عدم تمایل قطعی → بسته‌شده؛ ثبت نام تا زمان مصرف لینک در صف ثبت‌نام می‌ماند.
        if ($result === PhoneCall::RESULT_NO_INTEREST) {
            return self::STATUS_CLOSED;
        }

        // پیگیری → فعال می‌ماند
        return self::STATUS_ACTIVE;
    }
}
