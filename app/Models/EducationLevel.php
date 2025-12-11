<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationLevel extends Model
{
    use HasFactory;



    protected $guarded = [];



    protected $casts = [

        'is_active' => 'boolean',

    ];



    protected static function boot()

    {

        parent::boot();



        static::creating(function ($model) {

            if (empty($model->slug)) {

                $model->slug = Str::slug($model->name);

            }

        });

    }



    public function grades()

    {

        return $this->hasMany(CcGrade::class);

    }



    public function scopeActive($query)

    {

        return $query->where('is_active', true);

    }



    public function scopeOrdered($query)

    {

        return $query->orderBy('order');

    }
}
