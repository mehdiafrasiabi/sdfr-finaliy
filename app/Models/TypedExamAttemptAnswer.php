<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TypedExamAttemptAnswer extends Model
{
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



        $this->update([

            'is_correct' => $correctOption && $correctOption->option_number === $this->selected_option

        ]);

    }
}
