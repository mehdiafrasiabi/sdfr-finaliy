<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionRescheduleRequest extends Model
{
    public const TYPE_EXCEPTION = 'exception';
    public const TYPE_PERMANENT = 'permanent';

    public const STATUS_PENDING_MANAGER           = 'pending_manager_review';
    public const STATUS_AWAITING_CONSULTANT_PROP  = 'awaiting_consultant_proposal';
    public const STATUS_AWAITING_STUDENT_CHOICE   = 'awaiting_student_choice';
    public const STATUS_AWAITING_CONSULTANT_CONF  = 'awaiting_consultant_confirm';
    public const STATUS_AWAITING_MANAGER_FINAL    = 'awaiting_manager_final';
    public const STATUS_APPROVED                  = 'approved';
    public const STATUS_REJECTED                  = 'rejected';
    public const STATUS_CONSULTANT_CHANGE         = 'consultant_change_requested';

    public const STATUS_LABELS = [
        self::STATUS_PENDING_MANAGER           => 'در انتظار بررسی مدیر آموزشی',
        self::STATUS_AWAITING_CONSULTANT_PROP  => 'در انتظار پیشنهاد مشاور',
        self::STATUS_AWAITING_STUDENT_CHOICE   => 'در انتظار انتخاب دانش‌آموز',
        self::STATUS_AWAITING_CONSULTANT_CONF  => 'در انتظار تایید مشاور',
        self::STATUS_AWAITING_MANAGER_FINAL    => 'در انتظار تایید نهایی مدیر آموزشی',
        self::STATUS_APPROVED                  => 'تایید شده',
        self::STATUS_REJECTED                  => 'رد شده',
        self::STATUS_CONSULTANT_CHANGE         => 'درخواست تعویض مشاور',
    ];

    use HasFactory;

    protected $fillable = [
        'student_id',
        'advisor_id',
        'type',
        'original_session_id',
        'original_day',
        'original_time',
        'student_proposed_day',
        'student_proposed_time',
        'student_description',
        'manager_available_slots',
        'consultant_proposed_day',
        'consultant_proposed_time',
        'consultant_notes',
        'student_selected_day',
        'student_selected_time',
        'status',
        'rejection_reason',
        'approved_at',
    ];

    protected $casts = [
        'manager_available_slots' => 'array',
        'approved_at'             => 'datetime',
        'original_day'            => 'integer',
        'student_proposed_day'    => 'integer',
        'consultant_proposed_day' => 'integer',
        'student_selected_day'    => 'integer',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function advisor(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'advisor_id');
    }

    public function originalSession(): BelongsTo
    {
        return $this->belongsTo(AdvisingSession::class, 'original_session_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }
}
