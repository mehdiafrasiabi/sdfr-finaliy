<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CcChapter extends Model
{
    use HasFactory;



    protected $guarded = [];



    protected $casts = [

        'is_active' => 'boolean',

    ];



    public function subject()

    {

        return $this->belongsTo(CcSubject::class, 'cc_subject_id');

    }



    public function topics()

    {

        return $this->hasMany(CcTopic::class);

    }

    public function questions()

    {

        return $this->hasMany(Question::class, 'cc_chapter_id');

    }



    public function classifications()

    {

        return $this->morphMany(StudentClassification::class, 'ratable');

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

        return $this->subject->name . ' - ' . $this->name;

    }
}
