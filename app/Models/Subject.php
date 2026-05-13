<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Subject extends Model
{
    use HasFactory;

    protected $guarded = [];



    protected static function boot()

    {

        parent::boot();



        static::creating(function ($subject) {

            if (empty($subject->slug)) {

                $subject->slug = Str::slug($subject->name, '-', null);

            }

        });

    }



    /**

     * سوالات مرتبط با این درس

     */

    public function questions(): HasMany

    {

        return $this->hasMany(Question::class);

    }



    /**

     * فقط درس‌های فعال

     */

    public function scopeActive($query)

    {

        return $query->where('is_active', true);

    }
}
