<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\GradePrice;
use App\Models\GradePriceMonthDiscount;
use Illuminate\Database\Seeder;
use Morilog\Jalali\Jalalian;

/**
 * قیمتِ پایهٔ همهٔ پایه‌ها (۹/۱۰/۱۱/۱۲) با «قیمت خام سالانه = ۱۹٬۸۰۰٬۰۰۰»
 * (= نرخ ماهانه ۱٬۶۵۰٬۰۰۰ × ۱۲) و تخفیف‌های زودهنگامِ پیش‌فرضِ اکسل
 * (تیر ۱۵٪، مرداد ۱۲٪، شهریور ۹٪، مهر ۶٪، بقیه ۰٪).
 *
 * خروجی (تیر): کل = ۱۶٬۸۳۰٬۰۰۰، پیش‌پرداخت ۳۰٪ = ۵٬۰۴۹٬۰۰۰، ۱۱ قسط × ۱٬۰۷۱٬۰۰۰.
 */
class GradePriceSeeder extends Seeder
{
    private const BASE_PRICE        = 19800000;
    private const INITIAL_PERCENT   = 30;

    public function run(): void
    {
        // سالِ خدمتِ جاری (اگر بعد از خرداد هستیم همین سال، وگرنه سال قبل).
        $now  = Jalalian::now();
        $year = (int) $now->getMonth() >= 4 ? (int) $now->getYear() : (int) $now->getYear() - 1;

        $startAt = Jalalian::fromFormat('Y/m/d', sprintf('%d/04/01', $year))->toCarbon()->startOfDay();
        $endAt   = Jalalian::fromFormat('Y/m/d', sprintf('%d/03/31', $year + 1))->toCarbon()->endOfDay();

        $createdBy = Admin::query()->min('id'); // ستون created_by الزامی است

        foreach ([9, 10, 11, 12] as $grade) {
            $price = GradePrice::updateOrCreate(
                ['grade' => $grade],
                [
                    'base_price'         => self::BASE_PRICE,
                    'monthly_rate'       => (int) round(self::BASE_PRICE / GradePrice::SERVICE_MONTH_COUNT),
                    'initial_percentage' => self::INITIAL_PERCENT,
                    'total_amount'       => self::BASE_PRICE,
                    'start_at'           => $startAt->toDateString(),
                    'end_at'             => $endAt->toDateString(),
                    'is_active'          => true,
                    'created_by'         => $createdBy,
                ],
            );

            $this->seedMonthDiscounts($price, $year);
        }
    }

    /** ۱۲ ردیفِ تخفیفِ ماهانه با بازهٔ تاریخِ هر ماهِ شمسی. */
    private function seedMonthDiscounts(GradePrice $price, int $year): void
    {
        for ($i = 0; $i < GradePrice::SERVICE_MONTH_COUNT; $i++) {
            $month = GradePrice::SERVICE_MONTHS[$i];          // 4..12 سپس 1..3
            $my    = $i <= 8 ? $year : $year + 1;             // نیمهٔ دومِ سال → سال بعد

            $startJ = Jalalian::fromFormat('Y/m/d', sprintf('%d/%02d/01', $my, $month));
            $start  = $startJ->toCarbon()->startOfDay();
            $end    = $startJ->addMonths(1)->toCarbon()->subDay()->endOfDay();

            GradePriceMonthDiscount::updateOrCreate(
                ['grade_price_id' => $price->id, 'month_index' => $i],
                [
                    'month_starts_on'     => $start->toDateString(),
                    'month_ends_on'       => $end->toDateString(),
                    'discount_percentage' => GradePrice::DEFAULT_DISCOUNTS[$i] ?? 0,
                ],
            );
        }
    }
}
