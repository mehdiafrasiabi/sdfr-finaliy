<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudySession extends Model
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
    public function programPart()
    {
        return $this->belongsTo(ProgramPart::class);
    }
}
