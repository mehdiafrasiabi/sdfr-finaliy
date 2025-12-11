<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;


class CcTopic extends Model

{

    use HasFactory;


    protected $guarded = [];


    protected $casts = [

        'is_active' => 'boolean',

    ];


    public function chapter()

    {

        return $this->belongsTo(CcChapter::class, 'cc_chapter_id');

    }


    public function classifications()

    {

        return $this->hasMany(StudentClassification::class);

    }


    public function scopeActive($query)

    {

        return $query->where('is_active', true);

    }


    public function scopeOrdered($query)

    {

        return $query->orderBy('order');

    }


    public function getFullPathAttribute()

    {

        $chapter = $this->chapter;

        $subject = $chapter->subject;

        $grade = $subject->grade;


        return $grade->name . ' » ' . $subject->name . ' » ' . $chapter->name . ' » ' . $this->name;

    }

}
