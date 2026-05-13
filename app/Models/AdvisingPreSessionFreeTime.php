<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvisingPreSessionFreeTime extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected $casts = [

        'exam_date' => 'date',

    ];



    public function preSession(): BelongsTo

    {

        return $this->belongsTo(AdvisingPreSession::class, 'pre_session_id');

    }
}
