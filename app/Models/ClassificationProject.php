<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;


class ClassificationProject extends Model

{

    use HasFactory;


    protected $guarded = [];


    protected $casts = [

        'start_at' => 'datetime',

        'end_at' => 'datetime',

        'is_active' => 'boolean',

    ];


    public function gradeSettings()

    {

        return $this->hasMany(ProjectGradeSetting::class);

    }


    public function classifications()

    {

        return $this->hasMany(StudentClassification::class);

    }


    public function submissions()

    {

        return $this->hasMany(StudentClassificationSubmission::class);

    }


    public function scopeActive($query)

    {

        return $query->where('is_active', true);

    }


    public function scopeCurrent($query)

    {

        $now = Carbon::now();

        return $query->where('start_at', '<=', $now)
            ->where('end_at', '>=', $now);

    }


    public function scopeUpcoming($query)

    {

        return $query->where('start_at', '>', Carbon::now());

    }


    public function scopeEnded($query)

    {

        return $query->where('end_at', '<', Carbon::now());

    }


    public function getStatusAttribute()

    {

        $now = Carbon::now();


        if ($now < $this->start_at) {

            return 'upcoming';

        }


        if ($now > $this->end_at) {

            return 'ended';

        }


        return 'active';

    }


    public function getStatusNameAttribute()

    {

        return match ($this->status) {

            'upcoming' => 'در انتظار',

            'active' => 'در حال اجرا',

            'ended' => 'پایان یافته',

        };

    }


    public function getDaysRemainingAttribute()

    {

        if ($this->status === 'ended') {

            return 0;

        }


        return max(0, Carbon::now()->diffInDays($this->end_at, false));

    }


    /**
     * Get grade settings for a specific student grade
     */

    public function getSettingsForStudentGrade($studentGrade)

    {

        return $this->gradeSettings()
            ->where('student_grade', $studentGrade)
            ->get();

    }

}
