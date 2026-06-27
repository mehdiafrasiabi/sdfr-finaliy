<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Morilog\Jalali\Jalalian;

/**
 * قیمت‌گذاری دوره برای یک «پایه تحصیلی» (9, 10, 11, 12) — مدل «ماه ورود و تخفیف»
 * مطابق فایل اکسل مجموعه.
 *
 * منطق:
 *   - سال خدمت از «تیر» (اندیس ۰) تا «خرداد» (اندیس ۱۱) است (۱۲ ماه).
 *   - مدیر برای هر پایه «نرخ ماهانه» (monthly_rate) و «درصد پیش‌پرداخت»
 *     (initial_percentage) و سالِ خدمت (start_at = تیر۱، end_at = پایان خرداد) را
 *     مشخص می‌کند، و برای هر ماهِ ورود یک «تخفیف زودهنگام» در
 *     `GradePriceMonthDiscount` تعریف می‌کند (پیش‌فرض: تیر۱۵٪، مرداد۱۲٪، شهریور۹٪،
 *     مهر۶٪، بقیه ۰).
 *   - برای ماهِ ورودِ i:
 *       remainingMonths = 12 − i
 *       effectiveRate   = monthly_rate × (1 − discount(i)/100)
 *       total           = effectiveRate × remainingMonths      (کل پرداختی سال)
 *       initial         = total × initial_percentage/100        (پیش‌پرداخت)
 *       installmentCount= remainingMonths − 1
 *       installment     = (total − initial) / installmentCount  (مبلغ هر قسط)
 *   - دسترسی همهٔ دانش‌آموزان در پایان خرداد (end_at) تمام می‌شود.
 */
class GradePrice extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'start_at'           => 'date',
        'end_at'             => 'date',
        'is_active'          => 'boolean',
        'base_price'         => 'integer',
        'monthly_rate'       => 'integer',
        'initial_percentage' => 'integer',
    ];

    /** شمارهٔ ماهِ شمسی به‌ازای هر اندیسِ سال خدمت (۰=تیر … ۱۱=خرداد). */
    public const SERVICE_MONTHS = [4, 5, 6, 7, 8, 9, 10, 11, 12, 1, 2, 3];

    public const PERSIAN_MONTH_NAMES = [
        1 => 'فروردین', 2 => 'اردیبهشت', 3 => 'خرداد', 4 => 'تیر',
        5 => 'مرداد', 6 => 'شهریور', 7 => 'مهر', 8 => 'آبان',
        9 => 'آذر', 10 => 'دی', 11 => 'بهمن', 12 => 'اسفند',
    ];

    /** تخفیف‌های پیش‌فرض اکسل بر حسب اندیس ماهِ ورود. */
    public const DEFAULT_DISCOUNTS = [0 => 15, 1 => 12, 2 => 9, 3 => 6];

    public const SERVICE_MONTH_COUNT = 12;

    // ───────────────────────── روابط ─────────────────────────

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

    // ────────────────── کمک‌متدهای ماهِ ورود ──────────────────

    /**
     * نرخ ماهانهٔ مؤثرِ مبنا (قبل از تخفیف).
     * منبعِ حقیقت: «قیمت خام سالانه» (base_price) ÷ ۱۲. اگر base_price تهی بود
     * (داده‌های قدیمی)، به ستون monthly_rate برمی‌گردیم.
     */
    public function monthlyRate(): int
    {
        if (! empty($this->base_price)) {
            return (int) round($this->base_price / self::SERVICE_MONTH_COUNT);
        }
        return (int) $this->monthly_rate;
    }

    /** قیمت خامِ سالانه (قبل از تخفیف) — برای نمایش و فرمِ مدیر. */
    public function basePrice(): int
    {
        if (! empty($this->base_price)) {
            return (int) $this->base_price;
        }
        return (int) $this->monthly_rate * self::SERVICE_MONTH_COUNT;
    }

    /**
     * قیمتِ بدونِ تخفیفِ همان ماهِ ورود (= نرخ ماهانه × ماه‌های باقی‌مانده).
     * برای نمایشِ «قیمتِ خط‌خورده» در صفحهٔ خرید.
     */
    public function originalTotalFor(int $i): int
    {
        return $this->monthlyRate() * $this->remainingMonths($i);
    }

    public function initialPercentage(): int
    {
        $pct = (int) ($this->initial_percentage ?? 30);
        return max(0, min(100, $pct));
    }

    /** شمارهٔ ماهِ شمسی (۱..۱۲) برای اندیس سال خدمت. */
    public static function persianMonthForIndex(int $i): int
    {
        $i = self::clampIndex($i);
        return self::SERVICE_MONTHS[$i];
    }

    /** نام فارسی ماهِ ورود برای اندیس. */
    public static function monthLabel(int $i): string
    {
        return self::PERSIAN_MONTH_NAMES[self::persianMonthForIndex($i)] ?? '';
    }

    /**
     * اندیس ماهِ ورود برای یک تاریخ (پیش‌فرض: امروز = ماهِ خرید).
     * نگاشت: تیر(۴)→۰، مرداد(۵)→۱ … خرداد(۳)→۱۱.
     */
    public function entryMonthIndex(?Carbon $at = null): int
    {
        $jMonth = (int) Jalalian::fromCarbon($at ?? Carbon::now())->getMonth();
        return (($jMonth - 4) + 12) % 12;
    }

    /** درصد تخفیف زودهنگامِ ماهِ ورودِ i. */
    public function discountFor(int $i): int
    {
        $i = self::clampIndex($i);

        if ($this->relationLoaded('monthDiscounts')) {
            $md = $this->monthDiscounts->firstWhere('month_index', $i);
            return (int) ($md->discount_percentage ?? 0);
        }

        return (int) ($this->monthDiscounts()->where('month_index', $i)->value('discount_percentage') ?? 0);
    }

    /** تعداد ماه‌های باقی‌مانده تا پایان خرداد (با احتساب ماهِ ورود). */
    public function remainingMonths(int $i): int
    {
        return self::SERVICE_MONTH_COUNT - self::clampIndex($i);
    }

    /** نرخ مؤثر ماهانه = نرخ پایه × (۱ − تخفیف). */
    public function effectiveRate(int $i, ?int $discountPct = null): int
    {
        $pct = $discountPct ?? $this->discountFor($i);
        $pct = max(0, min(100, $pct));
        return (int) round($this->monthlyRate() * (1 - $pct / 100));
    }

    /** کل پرداختی سال برای ماهِ ورودِ i. */
    public function totalFor(int $i, ?int $discountPct = null): int
    {
        return $this->effectiveRate($i, $discountPct) * $this->remainingMonths($i);
    }

    /** مبلغ پیش‌پرداخت (۳۰٪ پیش‌فرض). */
    public function initialPayment(int $i, ?int $discountPct = null): int
    {
        return (int) round($this->totalFor($i, $discountPct) * $this->initialPercentage() / 100);
    }

    /** تعداد اقساط (بدون احتساب پیش‌پرداخت). */
    public function installmentCount(int $i): int
    {
        return max(0, $this->remainingMonths($i) - 1);
    }

    /** مبلغ هر قسط. اگر فقط یک ماه مانده باشد، قسطی نیست (۰). */
    public function installmentAmount(int $i, ?int $discountPct = null): int
    {
        $count = $this->installmentCount($i);
        if ($count <= 0) {
            return 0;
        }
        return (int) round(($this->totalFor($i, $discountPct) - $this->initialPayment($i, $discountPct)) / $count);
    }

    /** تاریخ پایان دسترسی (پایان خرداد سالِ خدمت). */
    public function accessEndsAt(): ?Carbon
    {
        if (! $this->end_at) {
            return null;
        }
        $end = $this->end_at instanceof Carbon ? $this->end_at : Carbon::parse($this->end_at);
        return $end->copy()->endOfDay();
    }

    /** سالِ خدمت (سال شمسیِ تیر). */
    public function serviceYear(): ?int
    {
        if (! $this->start_at) {
            return null;
        }
        $start = $this->start_at instanceof Carbon ? $this->start_at : Carbon::parse($this->start_at);
        return (int) Jalalian::fromCarbon($start)->getYear();
    }

    /**
     * جدول کاملِ ۱۲ ماه با همهٔ مقادیر محاسبه‌شده — برای UI مدیریت و صفحهٔ خرید.
     */
    public function entryMonthsTable(?Carbon $at = null): array
    {
        $current = $this->entryMonthIndex($at);
        $rows = [];
        for ($i = 0; $i < self::SERVICE_MONTH_COUNT; $i++) {
            $pct = $this->discountFor($i);
            $total = $this->totalFor($i, $pct);
            $original = $this->originalTotalFor($i);
            $rows[] = [
                'index'             => $i,
                'persian_month'     => self::persianMonthForIndex($i),
                'label'             => self::monthLabel($i),
                'discount'          => $pct,
                'effective_rate'    => $this->effectiveRate($i, $pct),
                'remaining_months'  => $this->remainingMonths($i),
                'original_total'    => $original,
                'total'             => $total,
                'savings'           => max(0, $original - $total),
                'initial'           => $this->initialPayment($i, $pct),
                'installment_count' => $this->installmentCount($i),
                'installment'       => $this->installmentAmount($i, $pct),
                'is_current'        => $i === $current,
            ];
        }
        return $rows;
    }

    // ────────────────── یافتن قیمت فعال ──────────────────

    public static function activeFor(int $grade): ?self
    {
        return self::where('grade', $grade)
            ->where('is_active', true)
            ->where('start_at', '<=', now()->toDateString())
            ->where(fn ($q) => $q->whereNull('end_at')->orWhere('end_at', '>=', now()->toDateString()))
            ->orderByDesc('start_at')
            ->first();
    }

    private static function clampIndex(int $i): int
    {
        return max(0, min(self::SERVICE_MONTH_COUNT - 1, $i));
    }
}
