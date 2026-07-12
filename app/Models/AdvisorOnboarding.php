<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvisorOnboarding extends Model
{
    use HasFactory;

    public const STATUS_PENDING_CALL = 'pending_call';
    public const STATUS_PENDING_LINK = 'pending_link';
    public const STATUS_PENDING_REVIEW = 'pending_review';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $guarded = [];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at'  => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function advisor(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'advisor_id');
    }

    public function advisorSelection(): BelongsTo
    {
        return $this->belongsTo(AdvisorSelection::class);
    }

    public function call(): BelongsTo
    {
        return $this->belongsTo(ContactDocumentation::class, 'contact_documentation_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING_CALL   => 'در انتظار تماس اتمام حجت',
            self::STATUS_PENDING_LINK   => 'در انتظار ثبت لینک بله',
            self::STATUS_PENDING_REVIEW => 'در انتظار تایید مدیر آموزشی',
            self::STATUS_APPROVED       => 'تایید شده',
            self::STATUS_REJECTED       => 'رد شده',
            default                     => 'نامشخص',
        };
    }
}
