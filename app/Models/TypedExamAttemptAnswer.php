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

        'selected_option' => 'integer',

        'correct_option' => 'integer',

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

        return $this->belongsTo(Question::class)->withTrashed();

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



        $correctOptionNumber = $this->correctOptionNumber();



        $this->update([

            'is_correct' => $correctOptionNumber !== null
                ? (int) $correctOptionNumber === (int) $this->selected_option
                : false

        ]);

    }

    /**
     * The answer key is snapshotted when the attempt starts. This keeps a
     * finished attempt stable even if the question is edited afterwards.
     */
    public function correctOptionNumber(): ?int
    {
        $snapshot = (int) $this->correct_option;

        if ($snapshot >= 1 && $snapshot <= 4) {
            return $snapshot;
        }

        return $this->question?->correct_option_number;
    }
}
