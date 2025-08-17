<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    protected $table = 'example_questions';
    protected $guarded =[];

    public function examCategory()
    {
        return $this->belongsTo(ExamCategory::class);
    }
}
