<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamKey extends Model
{
    protected $guarded=[];
    // app/Models/ExamKey.php
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

}
