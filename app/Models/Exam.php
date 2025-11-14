<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $guarded = [];
    public function files()
    {
        return $this->hasMany(ExamFile::class);
    }



    public function students()
    {
        return $this->belongsToMany(Student::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function examKeys()
    {
        return $this->hasMany(ExamKey::class);
    }
    // app/Models/Exam.php

    public function keyAnswers()
    {
        return $this->hasMany(ExamKey::class);
    }
}
