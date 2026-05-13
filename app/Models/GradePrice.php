<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GradePrice extends Model
{
    protected $fillable = [
        'grade',
        'base_price',
        'start_date',
        'end_date',
        'months_count',
        'created_by',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'base_price' => 'integer',
        'months_count' => 'integer',
        'is_active' => 'boolean',
    ];

    public function months(): HasMany
    {
        return $this->hasMany(GradePriceMonth::class)->orderBy('month_index');
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(GradePriceDiscount::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
