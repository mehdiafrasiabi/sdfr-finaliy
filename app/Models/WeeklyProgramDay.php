<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyProgramDay extends Model
{
    protected $guarded = [];

    protected $casts = [
        'program_date' => 'date',
        'is_rest' => 'boolean',
    ];

    public function weeklyProgram(): BelongsTo
    {
        return $this->belongsTo(WeeklyProgram::class);
    }
}
