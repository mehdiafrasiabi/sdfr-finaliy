<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradePrice extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'start_at'    => 'date',
        'end_at'      => 'date',
        'is_active'   => 'boolean',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    // قیمت نهایی پس از تخفیف
    public function getFinalAmountAttribute(): int
    {
        if ($this->discount_percentage <= 0) {
            return $this->total_amount;
        }
        return (int) round($this->total_amount * (1 - $this->discount_percentage / 100));
    }

    // قسط ماهانه = مبلغ نهایی ÷ تعداد ماه
    public function getMonthlyAmountAttribute(): int
    {
        if ($this->months <= 0) return $this->finalAmount;
        return (int) round($this->finalAmount / $this->months);
    }

    public function getGradeLabelAttribute(): string
    {
        return TrialWeek::GRADE_LABELS[$this->grade] ?? "پایه {$this->grade}";
    }

    public function getFieldLabelAttribute(): string
    {
        if (!$this->field) return 'همه رشته‌ها';
        return TrialWeek::FIELD_LABELS[$this->field] ?? $this->field;
    }

    // یافتن قیمت فعال برای پایه و رشته مشخص در تاریخ جاری
    public static function activeFor(int $grade, ?string $field = null): ?self
    {
        return self::where('grade', $grade)
            ->where(fn($q) => $q->whereNull('field')->orWhere('field', $field))
            ->where('is_active', true)
            ->where('start_at', '<=', now()->toDateString())
            ->where(fn($q) => $q->whereNull('end_at')->orWhere('end_at', '>=', now()->toDateString()))
            ->orderByDesc('start_at')
            ->first();
    }
}
