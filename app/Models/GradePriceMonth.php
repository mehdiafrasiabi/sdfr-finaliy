<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradePriceMonth extends Model
{
    protected $fillable = [
        'grade_price_id',
        'month_index',
        'jalali_month',
        'jalali_year',
        'price',
    ];

    protected $casts = [
        'month_index' => 'integer',
        'jalali_month' => 'integer',
        'jalali_year' => 'integer',
        'price' => 'integer',
    ];

    public function gradePrice(): BelongsTo
    {
        return $this->belongsTo(GradePrice::class);
    }
}
