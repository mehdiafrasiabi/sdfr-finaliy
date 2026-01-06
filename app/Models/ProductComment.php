<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductComment extends Model
{
    use HasFactory;


    protected $fillable = [

        'product_id',

        'user_id',

        'comment',

        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reply(): HasOne
    {
        return $this->hasOne(CommentReply::class, 'comment_id');
    }
    public function likes(): HasMany
    {
        return $this->hasMany(CommentLike::class, 'comment_id');
    }
    public function likesCount(): int
    {
        return $this->likes()->count();
    }
    public function isLikedBy(?int $userId): bool
    {
        if (!$userId) return false;
        return $this->likes()->where('user_id', $userId)->exists();
    }
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    public function scopeReported($query)
    {
        return $query->where('status', 'reported');
    }
}
