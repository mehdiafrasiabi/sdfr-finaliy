<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
class AdvisingPreSessionQa extends Model

{

    protected $table = 'advising_pre_session_qas';

    use HasFactory;

    protected $guarded = [];


    protected $casts = [

        'qa_date' => 'date',

    ];


    public function preSession(): BelongsTo

    {

        return $this->belongsTo(AdvisingPreSession::class, 'pre_session_id');

    }

}
