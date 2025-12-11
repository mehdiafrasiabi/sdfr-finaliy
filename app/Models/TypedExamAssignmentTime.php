<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;



class TypedExamAssignmentTime extends Model

{

    protected $guarded = [];



    protected $casts = [

        'start_date' => 'date',

        'end_date' => 'date',
        'duration_minutes' => 'integer',

    ];



    /**

     * اختصاص مرتبط

     */

    public function assignment(): BelongsTo

    {

        return $this->belongsTo(TypedExamAssignment::class, 'assignment_id');

    }



    /**

     * تاریخ و ساعت شروع به صورت کامل

     */

    public function getStartDateTimeAttribute(): string

    {

        return $this->start_date->format('Y-m-d') . ' ' . $this->start_time;

    }



    /**

     * تاریخ و ساعت پایان به صورت کامل

     */

    public function getEndDateTimeAttribute(): string

    {

        return $this->end_date->format('Y-m-d') . ' ' . $this->end_time;

    }



    /**

     * فرمت شده تاریخ شروع (شمسی)

     */

    public function getFormattedStartDateAttribute(): string

    {

        return verta($this->start_date)->format('Y/m/d');

    }



    /**

     * فرمت شده تاریخ پایان (شمسی)

     */

    public function getFormattedEndDateAttribute(): string

    {

        return verta($this->end_date)->format('Y/m/d');

    }

}

