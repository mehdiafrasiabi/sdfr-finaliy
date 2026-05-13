<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyProgramRestDay extends Model
{
    use HasFactory;

    protected $guarded = [];



    public function weeklyProgram(): BelongsTo

    {

        return $this->belongsTo(WeeklyProgram::class);

    }
}
