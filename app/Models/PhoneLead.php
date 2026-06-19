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

    /**
     * رنگ نمایش لید (کلاس رنگ بوت‌استرپ). مردهٔ خاکستری اگر مرده باشد یا ۵ بار تماس گرفته شده باشد.
     */
    public function getColorAttribute(): string
    {
        if ($this->status === self::STATUS_DEAD || $this->attempts_count >= self::MAX_ATTEMPTS) {
            return 'secondary';
        }

        return self::COLOR_BY_ATTEMPT[max($this->attempts_count, 1)] ?? 'secondary';
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

    public function isExhausted(): bool
    {
        return $this->status === self::STATUS_DEAD || $this->attempts_count >= self::MAX_ATTEMPTS;
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
            // ۵ بار تلاش ناموفق → خاکستری
            if ($attempt >= self::MAX_ATTEMPTS) {
                return self::STATUS_DEAD;
            }
            // عدم پاسخ/خاموش/رد تماس → کادر زنده می‌ماند
            return self::STATUS_ACTIVE;
        }

        // ثبت‌نام یا عدم تمایل → بسته‌شده
        if (in_array($result, [PhoneCall::RESULT_REGISTERED, PhoneCall::RESULT_NO_INTEREST], true)) {
            return self::STATUS_CLOSED;
        }

        // پیگیری → فعال می‌ماند
        return self::STATUS_ACTIVE;
    }
}
