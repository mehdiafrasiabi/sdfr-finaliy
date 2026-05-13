<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TypedExamRandomConfig extends Model
{
    use HasFactory;

    protected $guarded = [];



    /**

     * آزمون مرتبط

     */

    public function typedExam(): BelongsTo

    {

        return $this->belongsTo(TypedExam::class);

    }



    /**

     * ترجمه درجه سختی

     */

    public function getDifficultyLabelAttribute(): string

    {

        $labels = [

            'easy' => 'آسان',

            'medium' => 'متوسط',

            'hard' => 'سخت',

            'special' => 'ویژه',

        ];

        return $labels[$this->difficulty] ?? $this->difficulty;

    }
}
