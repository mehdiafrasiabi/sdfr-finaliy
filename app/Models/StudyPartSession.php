<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class StudyPartSession extends Model

{

    protected $guarded = [];



    protected $casts = [

        'started_at' => 'datetime',

        'ended_at' => 'datetime',

        'completed_at' => 'datetime',

        'is_completed' => 'boolean',

    ];



    public function student(): BelongsTo

    {

        return $this->belongsTo(Student::class);

    }



    public function programPart(): BelongsTo

    {

        return $this->belongsTo(ProgramPart::class);

    }



    public function weeklyProgram(): BelongsTo

    {

        return $this->belongsTo(WeeklyProgram::class);

    }

}
