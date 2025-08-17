<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAnswer extends Model
{

    protected $guarded = [];
    public function answers()
    {
        return $this->hasMany(ExamAnswer::class);
    }
}
