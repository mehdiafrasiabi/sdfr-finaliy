<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;


class StudentClassification extends Model

{

    use HasFactory;


    protected $guarded = [];


    /**
     * Rating mapping: 1-8 corresponds to D through A+
     */

    public const RATINGS = [

        1 => 'D',

        2 => 'D+',

        3 => 'C',

        4 => 'C+',

        5 => 'B',

        6 => 'B+',

        7 => 'A',

        8 => 'A+',

    ];


    public function user()

    {

        return $this->belongsTo(User::class);

    }


    public function project()

    {

        return $this->belongsTo(ClassificationProject::class, 'classification_project_id');

    }


    public function topic()

    {

        return $this->belongsTo(CcTopic::class, 'cc_topic_id');

    }


    public function getRatingLabelAttribute()

    {

        return self::RATINGS[$this->rating] ?? 'نامشخص';

    }


    public function getRatingColorAttribute()

    {

        return match ($this->rating) {

            8, 7 => 'success', // A+, A

            6, 5 => 'info',    // B+, B

            4, 3 => 'warning', // C+, C

            2, 1 => 'danger',  // D+, D

            default => 'secondary',

        };

    }


    public static function getRatingOptions()

    {

        return array_map(function ($rating, $label) {

            return [

                'value' => $rating,

                'label' => $label,

            ];

        }, array_keys(self::RATINGS), self::RATINGS);

    }

}
