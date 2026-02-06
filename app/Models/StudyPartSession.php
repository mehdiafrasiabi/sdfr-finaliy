<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StudyPartSession extends Model
{
    protected $guarded = [];
    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_completed' => 'boolean',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function programPart(): BelongsTo
    {
        return $this->belongsTo(ProgramPart::class);
    }
    public function weeklyProgram(): BelongsTo
    {
        return $this->belongsTo(WeeklyProgram::class);
    }
    public function timing(): HasOne
    {
        return $this->hasOne(SpsTiming::class);
    }

    public function feedback(): HasOne
    {
        return $this->hasOne(SessionFeedback::class, 'sps_id');
    }

}
