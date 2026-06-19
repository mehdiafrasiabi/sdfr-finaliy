<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * رسید شارژ مشاور جذب تلفنی (تایید/رد توسط مدیر آموزشی).
 */
class ChargeReceipt extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'amount'      => 'integer',
        'reviewed_at' => 'datetime',
    ];

    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    const STATUS_LABELS = [
        'pending'  => 'در انتظار بررسی',
        'approved' => 'تایید شده',
        'rejected' => 'رد شده',
    ];

    const STATUS_COLORS = [
        'pending'  => 'warning',
        'approved' => 'success',
        'rejected' => 'danger',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'secondary';
    }

    public function getImageUrlAttribute(): string
    {
        return asset($this->image_path);
    }
}
