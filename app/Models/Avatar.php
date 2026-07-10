<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'gender',
        'image_path',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForGender(Builder $query, string $gender): Builder
    {
        return $query->where('gender', $gender);
    }

    public static function imagePathsForGender(string $gender): array
    {
        return static::query()
            ->active()
            ->forGender($gender)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->pluck('image_path')
            ->filter()
            ->values()
            ->all();
    }
}
