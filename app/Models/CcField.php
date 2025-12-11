<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CcField extends Model
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



    public function subjects()

    {

        return $this->hasMany(CcSubject::class);

    }



    public function scopeActive($query)

    {

        return $query->where('is_active', true);

    }



    public function scopeOrdered($query)

    {

        return $query->orderBy('order');

    }



    /**

     * Map personal_information field to cc_fields slug

     */

    public static function mapFromPersonalInfo($field)

    {

        $mapping = [

            'math' => 'math',

            'experimental' => 'experimental',

            'human' => 'human',

        ];



        return $mapping[$field] ?? null;

    }
}
