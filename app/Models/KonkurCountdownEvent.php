<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KonkurCountdownEvent extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function setting(): BelongsTo
    {
        return $this->belongsTo(KonkurCountdownSetting::class, 'konkur_countdown_setting_id');
    }
}
