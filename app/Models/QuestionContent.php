<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionContent extends Model
{
    protected $guarded = [];



    /**

     * سوال مرتبط

     */

    public function question(): BelongsTo

    {

        return $this->belongsTo(Question::class);

    }
}
