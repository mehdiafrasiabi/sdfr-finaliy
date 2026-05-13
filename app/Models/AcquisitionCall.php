<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcquisitionCall extends Model
{
    public const TYPE_INITIAL = 'initial';
    public const TYPE_SECONDARY = 'secondary';
    public const TYPE_SIDE = 'side';

    public const STATUS_PENDING = 'pending';
    public const STATUS_NO_ANSWER = 'no_answer';
    public const STATUS_ANSWERED = 'answered';

    protected $fillable = [
        'trial_week_id',
        'acquisition_supporter_id',
        'type',
        'status',
        'description',
        'called_at',
        'due_at',
        'prediction_percent',
        'attraction_plan',
    ];

    protected $casts = [
        'called_at' => 'datetime',
        'due_at' => 'datetime',
        'prediction_percent' => 'integer',
    ];

    public function trialWeek(): BelongsTo
    {
        return $this->belongsTo(TrialWeek::class);
    }

    public function acquisitionSupporter(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'acquisition_supporter_id');
    }
}
