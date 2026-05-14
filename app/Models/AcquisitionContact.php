<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcquisitionContact extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'answered'    => 'boolean',
        'contacted_at' => 'datetime',
    ];

    const TYPE_INITIAL       = 'initial';
    const TYPE_SECONDARY     = 'secondary';
    const TYPE_SUPPLEMENTARY = 'supplementary';

    const TYPE_LABELS = [
        'initial'       => 'تماس اولیه',
        'secondary'     => 'تماس ثانویه',
        'supplementary' => 'تماس جانبی',
    ];

    public function trialWeek(): BelongsTo
    {
        return $this->belongsTo(TrialWeek::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPE_LABELS[$this->type] ?? $this->type;
    }
}
