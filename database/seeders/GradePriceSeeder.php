<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\GradePrice;
use App\Models\GradePriceMonthDiscount;
use Illuminate\Database\Seeder;
use Morilog\Jalali\Jalalian;

class GradePriceSeeder extends Seeder
{
    private const INITIAL_PERCENT = 30;
    private const DEFAULT_BASE_PRICE = 19800000;

    public function run(): void
    {
        // साल-ए-खिदमत-ए जारी (अगर हम खोरदाद के बाद हैं, तो यह साल, वरना पिछला साल).
        $now  = Jalalian::now();
        $year = (int) $now->getMonth() >= 4 ? (int) $now->getYear() : (int) $now->getYear() - 1;

        $startAt = Jalalian::fromFormat('Y/m/d', sprintf('%d/04/01', $year))->toCarbon()->startOfDay();
        $endAt   = Jalalian::fromFormat('Y/m/d', sprintf('%d/12/29', $year))->toCarbon()->endOfDay();

        $createdBy = Admin::query()->min('id'); // created_by कॉलम अनिवार्य है

        foreach ([9, 10, 11, 12] as $grade) {
            $basePrice = $this->getBasePriceForGrade($grade);
            $price = GradePrice::updateOrCreate(
                ['grade' => $grade],
                [
                    'base_price'         => $basePrice,
                    'monthly_rate'       => (int) round($basePrice / GradePrice::SERVICE_MONTH_COUNT),
                    'initial_percentage' => self::INITIAL_PERCENT,
                    'total_amount'       => $basePrice,
                    'start_at'           => $startAt->toDateString(),
                    'end_at'             => $endAt->toDateString(),
                    'is_active'          => true,
                    'created_by'         => $createdBy,
                ],
            );

            $this->seedMonthDiscounts($price, $year);
        }
    }

    private function getBasePriceForGrade(int $grade): int
    {
        return match ($grade) {
            12 => (int) round(self::DEFAULT_BASE_PRICE * 1.20),
            9 => (int) round(self::DEFAULT_BASE_PRICE * 0.93),
            default => self::DEFAULT_BASE_PRICE,
        };
    }

    /** १२ मासिक छूट पंक्तियाँ प्रत्येक सौर महीने की तारीख सीमा के साथ। */
    private function seedMonthDiscounts(GradePrice $price, int $year): void
    {
        for ($i = 0; $i < GradePrice::SERVICE_MONTH_COUNT; $i++) {
            $month = GradePrice::SERVICE_MONTHS[$i];          // 4..12
            $my    = $year;

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
