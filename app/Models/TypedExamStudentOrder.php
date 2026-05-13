<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TypedExamStudentOrder extends Model
{
    use HasFactory;

    protected $guarded = [];



    protected $casts = [

        'options_order' => 'array',

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

     * گرفتن گزینه‌ها با ترتیب خاص این دانش‌آموز

     */

    public function getOrderedOptions()

    {

        $options = $this->question->options->keyBy('option_number');

        $orderedOptions = collect();



        if ($this->options_order) {

            foreach ($this->options_order as $optionNumber) {

                if (isset($options[$optionNumber])) {

                    $orderedOptions->push($options[$optionNumber]);

                }

            }

        } else {

            $orderedOptions = $options->values();

        }



        return $orderedOptions;

    }
}
