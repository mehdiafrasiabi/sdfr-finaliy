<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StudySession extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    public function programPart()
    {
        return $this->belongsTo(ProgramPart::class);
    }
    public function timing(): HasOne
    {
        return $this->hasOne(SsTiming::class);
    }
}
