<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GradePrice extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'start_at'  => 'date',
        'end_at'    => 'date',
        'is_active' => 'boolean',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function dailyDiscounts(): HasMany
    {
        return $this->hasMany(GradePriceDailyDiscount::class);
    }

    public function getGradeLabelAttribute(): string
    {
        return TrialWeek::GRADE_LABELS[$this->grade] ?? "پایه {$this->grade}";
    }

    public function getFieldLabelAttribute(): string
    {
        if (! $this->field) {
            return 'همه رشته‌ها';
        }
        return TrialWeek::FIELD_LABELS[$this->field] ?? $this->field;
    }

    // ─────────────────────────────────────────────────────────────────
    // قیمت پلکانی ماهانه
    // ─────────────────────────────────────────────────────────────────

    /**
     * مبلغی که در هر ماه از قیمت کل کاسته می‌شود.
     */
    public function getMonthlyReductionAttribute(): int
    {
        if ($this->months <= 0) {
            return 0;
        }
        return (int) floor($this->total_amount / $this->months);
    }

    /**
     * چندمین ماه از شروع پایه گذشته‌ایم (۰ یعنی همان ماه اول).
     */
    public function monthIndex(?Carbon $at = null): int
    {
        $at    = $at ?? Carbon::now();
        $start = $this->start_at instanceof Carbon ? $this->start_at : Carbon::parse($this->start_at);

        if ($at->lessThan($start)) {
            return 0;
        }

        $months = (int) $start->diffInMonths($at);

        // نباید از تعداد ماه‌های پلن بزرگ‌تر شود
        return min($months, max($this->months - 1, 0));
    }

    /**
     * قیمت ماهِ n-ام (n=0 → ماه اول).
     */
    public function priceAtMonth(int $monthIndex): int
    {
        $monthIndex = max(0, $monthIndex);
        $price = $this->total_amount - ($this->monthly_reduction * $monthIndex);
        return max(0, (int) $price);
    }

    /**
     * قیمت پلکانی برای ماه جاری (قبل از تخفیف روزانه و درصد ثابت).
     */
    public function getCurrentSteppedPriceAttribute(): int
    {
        return $this->priceAtMonth($this->monthIndex());
    }

    /**
     * قیمت پلکانی برای ماه بعد.
     */
    public function getNextMonthSteppedPriceAttribute(): int
    {
        return $this->priceAtMonth($this->monthIndex() + 1);
    }

    // ─────────────────────────────────────────────────────────────────
    // تخفیف
    // ─────────────────────────────────────────────────────────────────

    /**
     * تخفیف روز-خاص فعال (در صورت وجود).
     */
    public function activeDailyDiscount(?Carbon $at = null): ?GradePriceDailyDiscount
    {
        $at = $at ?? Carbon::now();
        return $this->dailyDiscounts()
            ->where('is_active', true)
            ->where('starts_on', '<=', $at->toDateString())
            ->where('ends_on',   '>=', $at->toDateString())
            ->orderByDesc('discount_percentage')
            ->first();
    }

    /**
     * قیمت نهایی = قیمت پلکانی − درصد ثابت (discount_percentage)
     *                                  − درصد روز خاص (در صورت وجود).
     * تخفیف‌ها به‌صورت ضرب‌شونده اعمال می‌شوند.
     */
    public function effectivePrice(?Carbon $at = null): int
    {
        $at      = $at ?? Carbon::now();
        $stepped = $this->priceAtMonth($this->monthIndex($at));

        $multiplier = 1.0;

        if ($this->discount_percentage > 0) {
            $multiplier *= (1 - $this->discount_percentage / 100);
        }

        $daily = $this->activeDailyDiscount($at);
        if ($daily) {
            $multiplier *= (1 - $daily->discount_percentage / 100);
        }

        return (int) round($stepped * $multiplier);
    }

    /**
     * قیمت نهایی برای ماه بعد (همان منطق ولی روی month_index + 1).
     */
    public function effectivePriceForNextMonth(?Carbon $at = null): int
    {
        $at      = $at ?? Carbon::now();
        $stepped = $this->priceAtMonth($this->monthIndex($at) + 1);

        // تخفیف روز-خاص فقط برای امروز اعمال می‌شود، نه برای ماه بعد.
        $multiplier = 1.0;
        if ($this->discount_percentage > 0) {
            $multiplier *= (1 - $this->discount_percentage / 100);
        }

        return (int) round($stepped * $multiplier);
    }

    /**
     * یافتن قیمت فعال برای پایه و رشته مشخص در تاریخ جاری.
     */
    public static function activeFor(int $grade, ?string $field = null): ?self
    {
        return self::where('grade', $grade)
            ->where(fn($q) => $q->whereNull('field')->orWhere('field', $field))
            ->where('is_active', true)
            ->where('start_at', '<=', now()->toDateString())
            ->where(fn($q) => $q->whereNull('end_at')->orWhere('end_at', '>=', now()->toDateString()))
            ->orderByDesc('start_at')
            ->first();
    }
}
