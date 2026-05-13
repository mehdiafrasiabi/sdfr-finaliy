<?php

namespace App\Models;

use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradePricing extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'starts_on' => 'date',
        'ends_on'   => 'date',
        'is_active' => 'boolean',
    ];

    public function ccGrade()
    {
        return $this->belongsTo(CcGrade::class);
    }

    /**
     * تعداد ماه‌های شمسی دوره (شامل ماه شروع، حداقل ۱).
     */
    public function monthsCount(): int
    {
        $start = Verta::instance($this->starts_on);
        $end   = Verta::instance($this->ends_on);

        $months = ($end->year - $start->year) * 12 + ($end->month - $start->month) + 1;

        return max(1, $months);
    }

    /**
     * مبلغ کسر شده برای هر ماه شمسی گذشته.
     */
    public function monthlyStep(): int
    {
        return (int) floor($this->total_price / $this->monthsCount());
    }

    /**
     * تعداد ماه‌های شمسی گذشته از تاریخ شروع تا «اکنون» (clamped 0..monthsCount-1).
     */
    public function elapsedMonths(?Carbon $now = null): int
    {
        $now   = $now ?? now();
        $start = Verta::instance($this->starts_on);
        $cur   = Verta::instance($now);

        $elapsed = ($cur->year - $start->year) * 12 + ($cur->month - $start->month);

        return max(0, min($elapsed, $this->monthsCount() - 1));
    }

    /**
     * محاسبه breakdown قیمت در زمان مشخص. مقادیر مطلق به تومان.
     */
    public function computeFor(?Carbon $now = null): array
    {
        $now             = $now ?? now();
        $monthlyStep     = $this->monthlyStep();
        $elapsedMonths   = $this->elapsedMonths($now);
        $steppedDiscount = $monthlyStep * $elapsedMonths;
        $gradeDiscount   = (int) $this->discount_amount;

        $final = (int) $this->total_price - $steppedDiscount - $gradeDiscount;
        $final = max(0, $final);

        return [
            'base_price'        => (int) $this->total_price,
            'months_count'      => $this->monthsCount(),
            'monthly_step'      => $monthlyStep,
            'elapsed_months'    => $elapsedMonths,
            'stepped_discount'  => $steppedDiscount,
            'grade_discount'    => $gradeDiscount,
            'final_amount'      => $final,
        ];
    }
}
