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
        'has_subtopics' => 'boolean',
    ];


    public function chapter()
    {
        return $this->belongsTo(CcChapter::class, 'cc_chapter_id');
    }


    public function questions()
    {
        return $this->hasMany(Question::class, 'cc_topic_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
    public function parent()
    {
        return $this->belongsTo(CcTopic::class, 'parent_id');
    }
    public function scopeMainTopics($query)
    {
        return $query->whereNull('parent_id');
    }
    public function scopeSubtopics($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function children()
    {
        return $this->hasMany(CcTopic::class, 'parent_id');
    }
    public function getFullPathAttribute()
    {
        $chapter = $this->chapter;
        $subject = $chapter->subject;
        $grade = $subject->grade;
        return $grade->name . ' » ' . $subject->name . ' » ' . $chapter->name . ' » ' . $this->name;
    }

}
