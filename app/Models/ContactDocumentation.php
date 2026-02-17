<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactDocumentation extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'student_id',
        'title',
        'description',
        'contact_status',
        'contact_date',
        'respondent',
    ];

    protected $casts = [
        'contact_date' => 'date',
    ];

    const CONTACT_STATUS = [
        'successful'   => 'موفق',
        'unsuccessful' => 'ناموفق',
    ];

    const RESPONDENT = [
        'father'  => 'پدر',
        'mother'  => 'مادر',
        'student' => 'دانش‌آموز',
        'other'   => 'سایر',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
