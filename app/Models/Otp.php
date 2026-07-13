<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Otp extends Model
{
    use HasFactory;

    public const TTL_SECONDS = 90;

    protected $guarded = [];
    public $timestamps = true;

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function scopeForMobile(Builder $query, string $mobile): Builder
    {
        return $query->where('mobile', $mobile);
    }

    public function scopeUnused(Builder $query): Builder
    {
        return $query->where('is_used', false);
    }

    public static function generateCode(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function isExpired(): bool
    {
        return $this->expires_at === null || $this->expires_at->lte(now());
    }

    public function remainingSeconds(): int
    {
        if ($this->expires_at === null) {
            return 0;
        }

        return max(now()->diffInSeconds($this->expires_at, false), 0);
    }
}
