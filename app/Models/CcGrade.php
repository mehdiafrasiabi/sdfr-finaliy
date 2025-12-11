<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CcGrade extends Model
{
    use HasFactory;



    protected $guarded = [];



    protected $casts = [

        'is_active' => 'boolean',

    ];



    public function educationLevel()

    {

        return $this->belongsTo(EducationLevel::class);

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



    public function getFullNameAttribute()

    {

        return $this->educationLevel->name . ' - ' . $this->name;

    }
}
