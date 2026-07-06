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
        'connected',
        'talk_duration_seconds',
        'answered_at',
        'fail_reason',
    ];

    protected $casts = [
        'contact_date' => 'date',
        'connected'    => 'boolean',
        'answered_at'  => 'datetime',
    ];

    const CONTACT_STATUS = [
        'successful'   => 'موفق',
        'unsuccessful' => 'ناموفق',
    ];

    const RESPONDENT = [
        'father'         => 'پدر',
        'mother'         => 'مادر',
        'student'        => 'دانش‌آموز',
        'student_father' => 'دانش‌آموز + پدر',
        'student_mother' => 'دانش‌آموز + مادر',
        'other'          => 'سایر',
    ];

    /** علتِ عدم‌پاسخ (هم‌راستا با PhoneCall). */
    const FAIL_LABELS = [
        'no_answer' => 'عدم پاسخ',
        'off'       => 'خاموش',
        'rejected'  => 'رد تماس',
        'wrong'     => 'شماره اشتباه',
    ];

    public function getRespondentLabelAttribute(): string
    {
        return self::RESPONDENT[$this->respondent] ?? $this->respondent;
    }

    public function getTalkDurationLabelAttribute(): string
    {
        $s = (int) $this->talk_duration_seconds;
        if ($s <= 0) {
            return '—';
        }
        return sprintf('%02d:%02d', intdiv($s, 60), $s % 60);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
