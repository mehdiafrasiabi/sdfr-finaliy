<?php

namespace App\Livewire\Manager\GradePrice;

use App\Models\GradePrice;
use App\Models\GradePriceMonthDiscount;
use Livewire\Component;

/**
 * صفحهٔ جزئیات قیمت یک پایه — بازتولید زندهٔ شیت «ماه ورود و تخفیف» اکسل.
 *
 * مدیر برای هر ماهِ ورود (تیر..خرداد) فقط «درصد تخفیف زودهنگام» را وارد می‌کند و
 * بقیهٔ ستون‌ها (نرخ مؤثر، ماه‌های باقی‌مانده، کل پرداختی، پیش‌پرداخت، قسط) به‌صورت
 * زنده محاسبه و نمایش داده می‌شوند.
 */
class Show extends Component
{
    public GradePrice $price;

    /** درصد تخفیف هر ماه — کلید: month_index (۰=تیر … ۱۱=خرداد) */
    public array $monthDiscounts = [];

    public function mount(GradePrice|int $price): void
    {
        $this->price = $price instanceof GradePrice
            ? $price->load('monthDiscounts')
            : GradePrice::with('monthDiscounts')->findOrFail($price);

        $this->seedMonthRows();
        $this->loadMonthDiscounts();
    }

    /** اطمینان از وجود ۱۲ ردیف؛ ردیف‌های نو با تخفیف پیش‌فرض اکسل ساخته می‌شوند. */
    protected function seedMonthRows(): void
    {
        for ($i = 0; $i < GradePrice::SERVICE_MONTH_COUNT; $i++) {
            GradePriceMonthDiscount::firstOrCreate(
                ['grade_price_id' => $this->price->id, 'month_index' => $i],
                ['discount_percentage' => GradePrice::DEFAULT_DISCOUNTS[$i] ?? 0],
            );
        }
        $this->price->load('monthDiscounts');
    }

    protected function loadMonthDiscounts(): void
    {
        $this->monthDiscounts = [];
        for ($i = 0; $i < GradePrice::SERVICE_MONTH_COUNT; $i++) {
            $md = $this->price->monthDiscounts->firstWhere('month_index', $i);
            $this->monthDiscounts[$i] = (int) ($md->discount_percentage ?? 0);
        }
    }

    public function saveMonthDiscount(int $monthIndex): void
    {
        $pct = (int) ($this->monthDiscounts[$monthIndex] ?? 0);
        if ($pct < 0 || $pct > 100) {
            $this->addError('month_' . $monthIndex, 'درصد باید بین ۰ تا ۱۰۰ باشد.');
            return;
        }

        GradePriceMonthDiscount::where('grade_price_id', $this->price->id)
            ->where('month_index', $monthIndex)
            ->update(['discount_percentage' => $pct]);

        session()->flash('success', 'تخفیف ماه «' . GradePrice::monthLabel($monthIndex) . '» ذخیره شد.');
        $this->price->load('monthDiscounts');
    }

    public function saveAll(): void
    {
        foreach ($this->monthDiscounts as $i => $pct) {
            $pct = max(0, min(100, (int) $pct));
            GradePriceMonthDiscount::where('grade_price_id', $this->price->id)
                ->where('month_index', (int) $i)
                ->update(['discount_percentage' => $pct]);
        }
        session()->flash('success', 'همهٔ تخفیف‌های ماهانه ذخیره شد.');
        $this->price->load('monthDiscounts');
        $this->loadMonthDiscounts();
    }

    public function render()
    {
        // ردیف‌ها بر اساس مقادیرِ در حالِ ویرایش (پیش‌نمایش زنده) محاسبه می‌شوند.
        $rows = [];
        for ($i = 0; $i < GradePrice::SERVICE_MONTH_COUNT; $i++) {
            $pct = max(0, min(100, (int) ($this->monthDiscounts[$i] ?? 0)));
            $rows[] = [
                'index'             => $i,
                'label'             => GradePrice::monthLabel($i),
                'discount'          => $pct,
                'effective_rate'    => $this->price->effectiveRate($i, $pct),
                'remaining_months'  => $this->price->remainingMonths($i),
                'total'             => $this->price->totalFor($i, $pct),
                'initial'           => $this->price->initialPayment($i, $pct),
                'installment_count' => $this->price->installmentCount($i),
                'installment'       => $this->price->installmentAmount($i, $pct),
            ];
        }

        return view('livewire.manager.grade-price.show', [
            'rows' => $rows,
        ])->layout('layouts.manager.app');
    }
}
