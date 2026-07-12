<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TypedExamAttemptAnswer extends Model
{
    use HasFactory;

    protected $guarded = [];



    protected $casts = [

        'is_correct' => 'boolean',

        'answered_at' => 'datetime',

    ];



    /**

     * تلاش مرتبط

     */

    public function attempt(): BelongsTo

    {

        return $this->belongsTo(TypedExamAttempt::class, 'attempt_id');

    }



    /**

     * سوال مرتبط

     */

    public function question(): BelongsTo

    {

        return $this->belongsTo(Question::class);

    }



    /**

     * آیا پاسخ داده شده؟

     */

    public function isAnswered(): bool

    {

        return $this->selected_option !== null;

    }



    /**

     * بررسی صحت پاسخ و آپدیت

     */

    public function checkCorrectness(): void

    {

        if ($this->selected_option === null) {

            $this->update(['is_correct' => null]);

            return;

        }



        $correctOption = $this->question->options()
            ->where('is_correct', true)
            ->first();

        $correctOptionNumber = $correctOption?->option_number ?? $this->question?->correct_option;



        $this->update([

            'is_correct' => $correctOptionNumber !== null
                ? (int) $correctOptionNumber === (int) $this->selected_option
                : false

        ]);

    }
}
