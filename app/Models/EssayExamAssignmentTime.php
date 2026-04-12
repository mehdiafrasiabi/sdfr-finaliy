<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EssayExamAssignmentTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'start_at',
        'end_at',
        'duration_minutes',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
        'duration_minutes' => 'integer',
    ];

    public function assignment()
    {
        return $this->belongsTo(EssayExamAssignment::class, 'assignment_id');
    }
}

