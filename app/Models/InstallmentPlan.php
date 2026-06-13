<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * طرح اقساطی یک دانش‌آموز: پیش‌پرداخت + اقساط ماهانه تا پایان خرداد.
 */
class InstallmentPlan extends Model
{
    protected $guarded = [];

    protected $casts = [
        'purchase_date'   => 'date',
        'access_ends_at'  => 'datetime',
        'total_amount'    => 'integer',
        'initial_amount'  => 'integer',
        'monthly_amount'  => 'integer',
    ];

    public const STATUS_PENDING   = 'pending';   // در انتظار پرداخت پیش‌پرداخت
    public const STATUS_ACTIVE    = 'active';     // پیش‌پرداخت انجام شده، اقساط جاری
    public const STATUS_COMPLETED = 'completed';  // همهٔ اقساط تسویه
    public const STATUS_DEFAULTED = 'defaulted';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function gradePrice(): BelongsTo
    {
        return $this->belongsTo(GradePrice::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class)->orderBy('sequence');
    }

    public function initialPayment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'initial_payment_id');
    }

    /** قسطِ جاری = کم‌ترین sequenceِ پرداخت‌نشده (پرداخت باید به‌ترتیب باشد). */
    public function currentDue(): ?Installment
    {
        return $this->installments()
            ->where('status', Installment::STATUS_PENDING)
            ->orderBy('sequence')
            ->first();
    }

    /** آیا قسطِ سررسیدشده و پرداخت‌نشده‌ای دارد؟ */
    public function hasOverdue(?Carbon $at = null): bool
    {
        $at = $at ?? Carbon::now();
        return $this->installments()
            ->where('status', Installment::STATUS_PENDING)
            ->whereDate('due_date', '<', $at->toDateString())
            ->exists();
    }

    public function paidCount(): int
    {
        return $this->installments()->where('status', Installment::STATUS_PAID)->count();
    }

    /** اگر همهٔ اقساط تسویه شده‌اند وضعیت را completed کن. */
    public function refreshCompletion(): void
    {
        $remaining = $this->installments()->where('status', Installment::STATUS_PENDING)->count();
        if ($remaining === 0 && $this->status !== self::STATUS_PENDING) {
            $this->update(['status' => self::STATUS_COMPLETED]);
        }
    }
}
