<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreAdvisingSession extends Model
{
    use HasFactory;



    protected $fillable = ['student_id', 'homeworks', 'exams', 'free_times'];

    protected $casts = [
        'homeworks' => 'array',
        'exams' => 'array',
        'free_times' => 'array',
    ];
}
