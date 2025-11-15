<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $guarded = [];

    protected $casts = [
        'advisor_commented_at' => 'datetime',
        'student_replied_at' => 'datetime',
        'student_reply_seen_at' => 'datetime',
    ];
    public function student()
    {
        return $this->belongsTo(\App\Models\Student::class);
    }
}
