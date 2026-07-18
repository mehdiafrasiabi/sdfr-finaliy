<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminNotification extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function phoneLead(): BelongsTo
    {
        return $this->belongsTo(PhoneLead::class);
    }

    public function getIsReadAttribute(): bool
    {
        return $this->read_at !== null;
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function markAsRead(): bool
    {
        if ($this->read_at) {
            return true;
        }

        return $this->update(['read_at' => now()]);
    }
}
