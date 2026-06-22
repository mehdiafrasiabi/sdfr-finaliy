<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Ticket $ticket) {
            if (empty($ticket->ticket_number)) {
                $ticket->ticket_number = self::generateTicketNumber();
            }
        });
    }

    /**
     * تولید شناسه یکتا برای تیکت با فرمت SDFR-T-XXXXXXXXXX
     */
    public static function generateTicketNumber(): string
    {
        do {
            $number = 'SDFR-T-' . random_int(1000000000, 9999999999);
        } while (self::where('ticket_number', $number)->exists());

        return $number;
    }

    /**
     * استفاده از شناسه تیکت (SDFR-T-...) به جای id در آدرس‌ها
     */
    public function getRouteKeyName(): string
    {
        return 'ticket_number';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function assignedAdmin()
    {
        return $this->belongsTo(Admin::class, 'assigned_admin_id');
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }
    public function unreadMessagesForAdmin()
    {
        return $this->messages()->where('admin_id', null)->where('is_read', false);
    }

    public function unreadMessagesForUser()
    {
        return $this->messages()->where('user_id', null)->where('is_read', false);
    }

    public function getPriorityLabelAttribute()
    {
        return match ($this->priority) {
            'low' => 'کم',
            'medium' => 'متوسط',
            'high' => 'زیاد',
            'urgent' => 'فوری',
            default => '-',
        };
    }
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'waiting' => 'در انتظار پاسخ',
            'answered' => 'پاسخ داده شده',
            'closed' => 'بسته شده',
            default => '-',
        };
    }

}
