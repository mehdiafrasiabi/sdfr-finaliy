<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypedExamSetting extends Model
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

     * آیا سوالات باید تصادفی باشند؟

     */

    public function shouldRandomizeQuestions(): bool

    {

        return in_array($this->randomization_type, ['questions_only', 'both']);

    }



    /**

     * آیا گزینه‌ها باید تصادفی باشند؟

     */

    public function shouldRandomizeOptions(): bool

    {

        return in_array($this->randomization_type, ['options_only', 'both']);

    }



    /**

     * ترجمه نوع تصادفی‌سازی

     */

    public function getRandomizationLabelAttribute(): string

    {

        $labels = [

            'none' => 'خیر',

            'questions_only' => 'بله، فقط سوالات',

            'options_only' => 'بله، فقط گزینه‌ها',

            'both' => 'بله، هم سوالات و هم گزینه‌ها',

        ];

        return $labels[$this->randomization_type] ?? 'خیر';

    }
}
