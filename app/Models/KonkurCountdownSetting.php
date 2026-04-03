<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KonkurCountdownSetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'countdown_start_at' => 'datetime',
        'countdown_end_at' => 'datetime',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(KonkurCountdownEvent::class)->orderBy('sort_order')->orderBy('event_date');
    }
}
