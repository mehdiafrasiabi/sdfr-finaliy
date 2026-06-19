<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * یک ردیف اختصاص شماره به مشاور جذب تلفنی.
 */
class PhoneLeadAssignment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_DONE   = 'done';

    public function lead(): BelongsTo
    {
        return $this->belongsTo(PhoneLead::class, 'phone_lead_id');
    }

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'assigned_by');
    }
}
