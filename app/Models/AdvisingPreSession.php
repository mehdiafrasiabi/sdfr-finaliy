<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class AdvisingPreSession extends Model
{
    protected $guarded=[];
    public function advisingSession(): BelongsTo
    {
        return $this->belongsTo(AdvisingSession::class);
    }
}
