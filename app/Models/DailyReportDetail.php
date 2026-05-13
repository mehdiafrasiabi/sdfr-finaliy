<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyReportDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function dailyReport(): BelongsTo
    {
        return $this->belongsTo(DailyReport::class);
    }
}
