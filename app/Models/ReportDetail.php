<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportDetail extends Model
{
    protected $guarded = [];


    public function dailyReport(): BelongsTo

    {

        return $this->belongsTo(DailyReport::class, 'report_id');

    }
}
