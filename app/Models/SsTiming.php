<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SsTiming extends Model
{
    public $timestamps = false;

    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function studySession(): BelongsTo
    {
        return $this->belongsTo(StudySession::class);
    }
}
