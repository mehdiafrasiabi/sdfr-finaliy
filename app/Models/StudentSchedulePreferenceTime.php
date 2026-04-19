<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentSchedulePreferenceTime extends Model
{
    protected $fillable = [
        'student_schedule_preference_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
    ];

    public function preference(): BelongsTo
    {
        return $this->belongsTo(StudentSchedulePreference::class, 'student_schedule_preference_id');
    }

    public function getDayNameAttribute(): string
    {
        return AdminWorkSchedule::DAYS[$this->day_of_week] ?? '';
    }
}
