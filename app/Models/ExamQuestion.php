<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExamQuestion extends Model
{
    protected $table = 'example_questions';
    use HasFactory;

    protected $guarded =[];

    public function examCategory()
    {
        return $this->belongsTo(ExamCategory::class);
    }
}
