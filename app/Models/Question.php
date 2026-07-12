<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Relations\HasOne;

use Illuminate\Database\Eloquent\SoftDeletes;


class Question extends Model

{

    use SoftDeletes;


    use HasFactory;

    protected $guarded = [];

    /**
     * درس مرتبط
     */

    public function subject(): BelongsTo

    {

        return $this->belongsTo(Subject::class);

    }


    /**
     * مبحث مرتبط
     */

    public function topic(): BelongsTo

    {

        return $this->belongsTo(CcTopic::class, 'cc_topic_id');

    }

    public function chapter(): BelongsTo

    {

        return $this->belongsTo(CcChapter::class, 'cc_chapter_id');

    }


    /**
     * محتوای سوال (عکس و توضیحات)
     */

    public function content(): HasOne

    {

        return $this->hasOne(QuestionContent::class);

    }


    /**
     * گزینه‌های سوال
     */

    public function options(): HasMany

    {

        return $this->hasMany(QuestionOption::class)->orderBy('option_number');

    }


    /**
     * گزینه صحیح (1-4)
     */

    public function getCorrectOptionNumberAttribute(): ?int

    {

        return $this->correct_option;

    }

    /**
     * آزمون‌هایی که این سوال در آن‌ها هست
     */

    public function typedExams(): BelongsToMany

    {

        return $this->belongsToMany(TypedExam::class, 'typed_exam_questions')
            ->withPivot('order')
            ->withTimestamps();

    }


    /**
     * تولید کد یکتای 4 رقمی
     */

    public static function generateUniqueCode(): string

    {

        do {

            $code = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        } while (self::withTrashed()->where('code', $code)->exists());


        return $code;

    }


    /**
     * دسترسی به متن سوال
     */

    public function getBodyAttribute(): ?string

    {

        return $this->content?->body;

    }


    /**
     * دسترسی به توضیحات
     */

    public function getExplanationAttribute(): ?string

    {

        return $this->content?->explanation;

    }


    /**
     * فیلتر بر اساس درس
     */

    public function scopeBySubject($query, $subjectId)

    {

        return $query->where('subject_id', $subjectId);

    }


    /**
     * فیلتر بر اساس سختی
     */

    public function scopeByDifficulty($query, $difficulty)

    {

        return $query->where('difficulty', $difficulty);

    }


    /**
     * جستجو در متن سوال و گزینه‌ها
     */

    public function scopeSearchKeyword($query, $keyword)

    {

        return $query->where(function ($q) use ($keyword) {

            $q->whereHas('content', function ($contentQuery) use ($keyword) {

                $contentQuery->where('body', 'like', "%{$keyword}%");

            })->orWhereHas('options', function ($optionsQuery) use ($keyword) {

                $optionsQuery->where('content', 'like', "%{$keyword}%");

            });

        });

    }

}
