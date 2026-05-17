<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradePriceMonthDiscount extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'month_starts_on' => 'date',
        'month_ends_on'   => 'date',
    ];

    public function gradePrice(): BelongsTo
    {
        return $this->belongsTo(GradePrice::class);
    }

    /**
     * آیا تاریخ مشخص داخل بازهٔ این ماه است؟
     */
    public function coversDate(?Carbon $at = null): bool
    {
        $at = $at ?? Carbon::now();
        return $at->between($this->month_starts_on->startOfDay(), $this->month_ends_on->endOfDay());
    }
}
