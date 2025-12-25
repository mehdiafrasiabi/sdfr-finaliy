<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyProgramRestDay extends Model
{
    protected $guarded = [];



    public function weeklyProgram(): BelongsTo

    {

        return $this->belongsTo(WeeklyProgram::class);

    }
}
