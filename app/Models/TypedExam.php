<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TypedExam extends Model
{
    protected $guarded = [];



    protected $casts = [

        'is_random_selection' => 'boolean',

        'is_published' => 'boolean',

    ];



    /**

     * تنظیمات آزمون

     */

    public function settings(): HasOne

    {

        return $this->hasOne(TypedExamSetting::class);

    }



    /**

     * سوالات آزمون

     */

    public function questions(): BelongsToMany

    {

        return $this->belongsToMany(Question::class, 'typed_exam_questions')

            ->withPivot('order')

            ->withTimestamps()

            ->orderBy('typed_exam_questions.order');

    }



    /**

     * تنظیمات انتخاب تصادفی

     */

    public function randomConfigs(): HasMany

    {

        return $this->hasMany(TypedExamRandomConfig::class);

    }



    /**

     * اختصاص‌ها به دانش‌آموزان

     */

    public function assignments(): HasMany

    {

        return $this->hasMany(TypedExamAssignment::class);

    }



    /**

     * تلاش‌های آزمون

     */

    public function attempts(): HasMany

    {

        return $this->hasMany(TypedExamAttempt::class, 'assignment_id');

    }



    /**

     * آزمون‌های منتشر شده

     */

    public function scopePublished($query)

    {

        return $query->where('is_published', true);

    }



    /**

     * تعداد سوالات آزمون

     */

    public function getQuestionsCountAttribute(): int

    {

        return $this->questions()->count();

    }



    /**

     * گرفتن توضیحات

     */

    public function getDescriptionAttribute(): ?string

    {

        return $this->settings?->description;

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

            'comprehensive' => 'جامع',

        ];

        return $labels[$this->difficulty] ?? $this->difficulty;

    }
}
