<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAttemp extends Model
{
    use HasFactory;

    // مشخص کردن نام جدول به صورت دستی
    protected $table = 'exam_attempts';

    protected $guarded = [];

    protected $casts = [
        'answers' => 'json',
        // ستون started_at را به عنوان تاریخ Cast کنید
        'started_at' => 'datetime',
    ];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function answers()
    {
        return $this->hasMany(StudentAnswer::class);
    }

}
