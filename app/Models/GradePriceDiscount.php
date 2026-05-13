<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradePriceDiscount extends Model
{
    protected $fillable = [
        'grade_price_id',
        'month_index',
        'percent',
    ];

    protected $casts = [
        'month_index' => 'integer',
        'percent' => 'integer',
    ];

    public function gradePrice(): BelongsTo
    {
        return $this->belongsTo(GradePrice::class);
    }
}
