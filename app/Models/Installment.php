<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * یک قسط ماهانه از یک طرح اقساطی.
 */
class Installment extends Model
{
    protected $guarded = [];

    protected $casts = [
        'due_date'      => 'date',
        'paid_at'       => 'datetime',
        'amount'        => 'integer',
        'paid_manually' => 'boolean',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID    = 'paid';

    public function plan(): BelongsTo
    {
        return $this->belongsTo(InstallmentPlan::class, 'installment_plan_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function manualAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'manual_admin_id');
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isOverdue(?Carbon $at = null): bool
    {
        if ($this->isPaid()) {
            return false;
        }
        $at = $at ?? Carbon::now();
        return $this->due_date && $this->due_date->lt($at->copy()->startOfDay());
    }
}
