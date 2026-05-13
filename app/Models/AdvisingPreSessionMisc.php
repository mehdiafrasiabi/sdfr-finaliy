<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvisingPreSessionMisc extends Model
{
    protected $table = 'advising_pre_session_misc';
    use HasFactory;

    protected $guarded = [];
    public function preSession(): BelongsTo
    {
        return $this->belongsTo(AdvisingPreSession::class, 'pre_session_id');
    }
}
