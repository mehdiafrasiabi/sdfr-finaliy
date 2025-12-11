<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;


class StudentClassificationSubmission extends Model

{

    use HasFactory;


    protected $guarded = [];


    protected $casts = [

        'is_completed' => 'boolean',

        'submitted_at' => 'datetime',

    ];


    public function user()

    {

        return $this->belongsTo(User::class);

    }


    public function project()

    {

        return $this->belongsTo(ClassificationProject::class, 'classification_project_id');

    }


    public function getStatusAttribute()

    {

        if ($this->is_completed) {

            return 'completed';

        }


        return 'pending';

    }


    public function getStatusNameAttribute()

    {

        return $this->is_completed ? 'ارسال شده' : 'در انتظار';

    }

}
