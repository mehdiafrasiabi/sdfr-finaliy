<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * قیمت پایه‌ای دوره برای یک «پایه تحصیلی» (9, 10, 11, 12).
 *
 * مدل قیمت‌گذاری:
 *   - مدیر مبلغ کل (`total_amount`)، تاریخ شروع و تاریخ پایان را مشخص می‌کند.
 *   - سیستم خودش تعداد ماه‌ها را از تفاضل دو تاریخ محاسبه می‌کند.
 *   - قیمت ثابت هر ماه پلکانی است:
 *           priceAtMonth(i) = total_amount − floor(total_amount / months) × i
 *     این یعنی هر ماه که می‌گذرد، (total ÷ months) تومان از قیمت کم می‌شود تا
 *     دانش‌آموزی که دیرتر می‌پیوندد، فقط معادل خدماتی که می‌گیرد بپردازد.
 *   - مدیر می‌تواند برای هر ماه (`GradePriceMonthDiscount`) درصد تخفیف اضافه
 *     بگذارد و علاوه بر آن یک «روز خاص» (`GradePriceDailyDiscount`) با تخفیف
 *     ویژه تعریف کند.
 */
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

    public function monthDiscounts(): HasMany
    {
        return $this->hasMany(GradePriceMonthDiscount::class);
    }

    public function dailyDiscounts(): HasMany
    {
        return $this->hasMany(GradePriceDailyDiscount::class);
    }

    public function getGradeLabelAttribute(): string
    {
        return TrialWeek::GRADE_LABELS[$this->grade] ?? "پایه {$this->grade}";
    }

    // ─────────────────────────────────────────────────────────────────
    // محاسبهٔ تعداد ماه‌ها و قیمت پلکانی
    // ─────────────────────────────────────────────────────────────────

    /**
     * تعداد ماهِ کامل از start_at تا end_at (حداقل ۱).
     * مثال: 1405/04/01 تا 1406/03/29  →  12 ماه.
     */
    public function getMonthsCountAttribute(): int
    {
        if (! $this->start_at || ! $this->end_at) {
            return 1;
        }

        $start = $this->start_at instanceof Carbon ? $this->start_at : Carbon::parse($this->start_at);
        $end   = $this->end_at   instanceof Carbon ? $this->end_at   : Carbon::parse($this->end_at);

        $months = (int) $start->copy()->startOfDay()->diffInMonths($end->copy()->endOfDay()) + 1;
        return max(1, $months);
    }

    /**
     * مبلغی که هر ماه از قیمت کل کاسته می‌شود.
     */
    public function getMonthlyReductionAttribute(): int
    {
        $months = $this->months_count;
        if ($months <= 0) {
            return 0;
        }
        return (int) floor($this->total_amount / $months);
    }

    /**
     * چندمین ماه از شروع پلن گذشته‌ایم (۰ = همان ماه اول).
     */
    public function monthIndex(?Carbon $at = null): int
    {
        $at    = $at ?? Carbon::now();
        $start = $this->start_at instanceof Carbon ? $this->start_at : Carbon::parse($this->start_at);

        if ($at->lessThan($start)) {
            return 0;
        }

        $diff = (int) $start->copy()->startOfDay()->diffInMonths($at);
        return min($diff, max($this->months_count - 1, 0));
    }

    /**
     * قیمت ثابت پلکانی ماه n‌ام (n=0 → ماه اول).
     * این مقدار «سقف» قیمت آن ماه است.
     */
    public function priceAtMonth(int $monthIndex): int
    {
        $monthIndex = max(0, $monthIndex);
        $price = $this->total_amount - ($this->monthly_reduction * $monthIndex);
        return max(0, (int) $price);
    }

    /**
     * بازهٔ تاریخی ماه n‌ام (start, end).
     */
    public function monthRange(int $monthIndex): array
    {
        $start = ($this->start_at instanceof Carbon ? $this->start_at : Carbon::parse($this->start_at))
            ->copy()->startOfDay()->addMonths($monthIndex);

        $months = $this->months_count;
        if ($monthIndex >= $months - 1) {
            $end = ($this->end_at instanceof Carbon ? $this->end_at : Carbon::parse($this->end_at))
                ->copy()->endOfDay();
        } else {
            $end = $start->copy()->addMonth()->subDay()->endOfDay();
        }

        return [$start, $end];
    }

    // ─────────────────────────────────────────────────────────────────
    // اعمال تخفیف ماهانه + تخفیف روز خاص
    // ─────────────────────────────────────────────────────────────────

    public function monthDiscountFor(int $monthIndex): ?GradePriceMonthDiscount
    {
        return $this->monthDiscounts()->where('month_index', $monthIndex)->first();
    }

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
     * قیمت نهایی ماه n‌ام = قیمت پلکانی × (1 − month_discount%) × (1 − daily_discount%)
     * تخفیف روزانه فقط در صورتی اعمال می‌شود که روز جاری در همان ماه `i` افتاده باشد.
     */
    public function effectivePriceForMonth(int $monthIndex, ?Carbon $at = null): int
    {
        $at      = $at ?? Carbon::now();
        $stepped = $this->priceAtMonth($monthIndex);

        $multiplier = 1.0;

        $md = $this->monthDiscountFor($monthIndex);
        if ($md && $md->discount_percentage > 0) {
            $multiplier *= (1 - $md->discount_percentage / 100);
        }

        [$monthStart, $monthEnd] = $this->monthRange($monthIndex);
        if ($at->between($monthStart, $monthEnd)) {
            $daily = $this->activeDailyDiscount($at);
            if ($daily) {
                $multiplier *= (1 - $daily->discount_percentage / 100);
            }
        }

        return (int) round($stepped * $multiplier);
    }

    /**
     * قیمت نهایی برای زمان فعلی (یا $at).
     */
    public function effectivePrice(?Carbon $at = null): int
    {
        return $this->effectivePriceForMonth($this->monthIndex($at), $at);
    }

    /**
     * قیمت پلکانی ماه جاری (بدون تخفیف).
     */
    public function getCurrentSteppedPriceAttribute(): int
    {
        return $this->priceAtMonth($this->monthIndex());
    }

    /**
     * قیمت پلکانی ماه بعد (بدون تخفیف روز خاص).
     */
    public function getNextMonthSteppedPriceAttribute(): int
    {
        return $this->priceAtMonth($this->monthIndex() + 1);
    }

    // ─────────────────────────────────────────────────────────────────
    // یافتن قیمت فعال برای یک پایه
    // ─────────────────────────────────────────────────────────────────

    public static function activeFor(int $grade): ?self
    {
        return self::where('grade', $grade)
            ->where('is_active', true)
            ->where('start_at', '<=', now()->toDateString())
            ->where(fn($q) => $q->whereNull('end_at')->orWhere('end_at', '>=', now()->toDateString()))
            ->orderByDesc('start_at')
            ->first();
    }
}
