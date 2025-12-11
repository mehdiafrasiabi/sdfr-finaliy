<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypedExamAssignment extends Model
{
    use SoftDeletes;



    protected $guarded = [];



    /**

     * آزمون مرتبط

     */

    public function typedExam(): BelongsTo

    {

        return $this->belongsTo(TypedExam::class);

    }



    /**

     * دانش‌آموز

     */

    public function student(): BelongsTo

    {

        return $this->belongsTo(Student::class);

    }



    /**

     * ادمین/مشاور که اختصاص داده

     */

    public function admin(): BelongsTo

    {

        return $this->belongsTo(Admin::class);

    }



    /**

     * زمان‌بندی آزمون

     */

    public function time(): HasOne

    {

        return $this->hasOne(TypedExamAssignmentTime::class, 'assignment_id');

    }



    /**

     * تلاش‌های آزمون

     */

    public function attempts(): HasMany

    {

        return $this->hasMany(TypedExamAttempt::class, 'assignment_id');

    }



    /**

     * آخرین تلاش

     */

    public function latestAttempt(): HasOne

    {

        return $this->hasOne(TypedExamAttempt::class, 'assignment_id')->latestOfMany();

    }



    /**

     * آیا آزمون شروع شده؟

     */

    public function hasStarted(): bool

    {

        return $this->status !== 'pending';

    }



    /**

     * آیا آزمون تمام شده؟

     */

    public function isCompleted(): bool

    {

        return $this->status === 'completed';

    }


    public function isWithinTimeWindow(): bool
    {
        if (!$this->time) {
            return false;
        }

        $now = now();
        return $now->between($this->time->start_date_time, $this->time->end_date_time);
    }

    public function isExpired(): bool
    {
        if (!$this->time) {
            return false;
        }

        return now()->isAfter($this->time->end_date_time);
    }
    /**

     * ترجمه وضعیت

     */

    public function getStatusLabelAttribute(): string

    {

        $labels = [

            'pending' => 'در انتظار',

            'started' => 'شروع شده',

            'completed' => 'تکمیل شده',

            'expired' => 'منقضی شده',

        ];

        return $labels[$this->status] ?? $this->status;

    }
}
