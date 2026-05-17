<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradePriceDailyDiscount extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'starts_on' => 'date',
        'ends_on'   => 'date',
        'is_active' => 'boolean',
    ];

    public function gradePrice(): BelongsTo
    {
        return $this->belongsTo(GradePrice::class);
    }

    /**
     * آیا این تخفیف در تاریخ مشخص فعال است؟
     */
    public function isActiveOn(?Carbon $at = null): bool
    {
        if (! $this->is_active) {
            return false;
        }
        $at = $at ?? Carbon::now();
        return $at->between($this->starts_on->startOfDay(), $this->ends_on->endOfDay());
    }
}
