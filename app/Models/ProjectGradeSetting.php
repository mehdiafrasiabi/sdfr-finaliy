<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;


class ProjectGradeSetting extends Model

{

    use HasFactory;


    protected $guarded = [];


    protected $casts = [

        'has_general' => 'boolean',

    ];


    public function project()

    {

        return $this->belongsTo(ClassificationProject::class, 'classification_project_id');

    }


    public function getTypeNameAttribute()

    {

        return $this->type === 'progress' ? 'پیشروی' : 'جمع‌بندی';

    }


    public function getGradeNameAttribute()

    {

        $names = [

            10 => 'دهم',

            11 => 'یازدهم',

            12 => 'دوازدهم',

        ];


        return $names[$this->target_grade] ?? $this->target_grade;

    }


    /**
     * Check if this setting allows access to specialized subjects
     */

    public function hasSpecialized()

    {

        return true; // تخصصی همیشه فعال است

    }


    /**
     * Get the default settings based on student grade
     */

    public static function getDefaultSettings($studentGrade)

    {

        $settings = [];


        if ($studentGrade == 12) {

            $settings = [

                ['target_grade' => 12, 'type' => 'progress', 'has_general' => true],

                ['target_grade' => 11, 'type' => 'review', 'has_general' => false],

                ['target_grade' => 10, 'type' => 'review', 'has_general' => false],

            ];

        } elseif ($studentGrade == 11) {

            $settings = [

                ['target_grade' => 11, 'type' => 'progress', 'has_general' => true],

                ['target_grade' => 10, 'type' => 'review', 'has_general' => false],

            ];

        } elseif ($studentGrade == 10) {

            $settings = [

                ['target_grade' => 10, 'type' => 'progress', 'has_general' => true],

            ];

        }


        return $settings;

    }

}
