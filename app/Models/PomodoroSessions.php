<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PomodoroSessions extends Model
{
    protected $guarded = [];
    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
