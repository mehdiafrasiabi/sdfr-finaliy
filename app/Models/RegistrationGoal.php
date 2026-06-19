<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * هدف‌گذاری ثبت‌نام جذب تلفنی. admin_id خالی = هدف کل تیم.
 */
class RegistrationGoal extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'target_count' => 'integer',
        'goal_date'    => 'date',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function scopeTeam(Builder $query): Builder
    {
        return $query->whereNull('admin_id');
    }

    public function scopeForAdmin(Builder $query, int $adminId): Builder
    {
        return $query->where('admin_id', $adminId);
    }

    public function isTeamGoal(): bool
    {
        return $this->admin_id === null;
    }
}
