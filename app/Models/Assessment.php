<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active'   => 'boolean',
        'is_required' => 'boolean',
    ];

    const KIND_MBTI   = 'mbti';
    const KIND_VARK   = 'vark';
    const KIND_CUSTOM = 'custom';

    const AUDIENCE_STUDENT = 'student';
    const AUDIENCE_PARENT  = 'parent';

    public function questions(): HasMany
    {
        return $this->hasMany(AssessmentQuestion::class)->orderBy('order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(StudentAssessmentAttempt::class);
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function scopeOrdered(Builder $q): Builder
    {
        return $q->orderBy('display_order')->orderBy('id');
    }

    public function scopeForStudent(Builder $q): Builder
    {
        return $q->where('audience', self::AUDIENCE_STUDENT);
    }

    public function getKindLabelAttribute(): string
    {
        return match ($this->kind) {
            self::KIND_MBTI   => 'MBTI',
            self::KIND_VARK   => 'VARK',
            self::KIND_CUSTOM => 'اختصاصی',
            default           => $this->kind,
        };
    }
}
