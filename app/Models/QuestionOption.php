<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionOption extends Model
{
    use HasFactory;

    protected $guarded = [];



    protected $casts = [

        'is_correct' => 'boolean',

    ];



    /**

     * سوال مرتبط

     */

    public function question(): BelongsTo

    {

        return $this->belongsTo(Question::class);

    }



    /**

     * برچسب گزینه (گزینه ۱، گزینه ۲، ...)

     */

    public function getLabelAttribute(): string

    {

        $labels = [1 => 'گزینه ۱', 2 => 'گزینه ۲', 3 => 'گزینه ۳', 4 => 'گزینه ۴'];

        return $labels[$this->option_number] ?? 'گزینه';

    }
}
