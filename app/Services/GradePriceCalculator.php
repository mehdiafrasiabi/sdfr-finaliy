<?php

namespace App\Services;

use App\Models\GradePrice;
use App\Models\User;
use Carbon\Carbon;

class GradePriceCalculator
{
    /**
     * مبلغ پلکانی هر ماه را تولید می‌کند: ماه اول = base_price، ماه بعد = base_price - (base_price/months) و...
     */
    public function generateMonthlyPrices(GradePrice $gp): array
    {
        $monthly = (int) floor($gp->base_price / max(1, $gp->months_count));
        $prices = [];
        for ($i = 0; $i < $gp->months_count; $i++) {
            $prices[] = [
                'month_index' => $i,
                'price'       => max(0, $gp->base_price - ($monthly * $i)),
            ];
        }
        return $prices;
    }

    /**
     * مبلغ نهایی برای پرداخت یک کاربر در تاریخ مرجع (با اعمال تخفیف ماه فعلی).
     */
    public function priceForUser(User $user, ?Carbon $at = null): ?array
    {
        $at = $at ?? Carbon::now();
        $grade = optional($user->personalInformation)->grade;
        if (!$grade) {
            return null;
        }

        $gp = GradePrice::with(['months', 'discounts'])
            ->where('grade', $grade)
            ->where('is_active', true)
            ->where('start_date', '<=', $at->toDateString())
            ->where('end_date', '>=', $at->toDateString())
            ->latest('id')
            ->first();

        if (!$gp) {
            return null;
        }

        // index ماهی که الان درون آن هستیم (offset از start)
        $monthIndex = Carbon::parse($gp->start_date)->diffInMonths($at);
        $monthIndex = min($monthIndex, $gp->months_count - 1);

        $month = $gp->months->firstWhere('month_index', $monthIndex);
        $base = $month?->price ?? $gp->base_price;

        $discount = $gp->discounts->firstWhere('month_index', $monthIndex);
        $discountPercent = $discount?->percent ?? 0;
        $finalPrice = (int) round($base * (100 - $discountPercent) / 100);

        return [
            'grade_price'      => $gp,
            'month_index'      => $monthIndex,
            'base_price'       => $base,
            'discount_percent' => $discountPercent,
            'final_price'      => $finalPrice,
        ];
    }
}
