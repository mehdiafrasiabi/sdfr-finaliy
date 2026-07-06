<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * مکالمه‌ی مستقیم بین یک دانش‌آموز و مشاور تحصیلی‌اش.
 * side در سراسر کد یکی از 'student' یا 'advisor' است.
 */
class Conversation extends Model
{
    protected $guarded = [];

    protected $casts = [
        'last_message_at'      => 'datetime',
        'student_last_read_at' => 'datetime',
        'advisor_last_read_at' => 'datetime',
        'student_last_seen_at' => 'datetime',
        'advisor_last_seen_at' => 'datetime',
        'student_typing_at'    => 'datetime',
        'advisor_typing_at'    => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function advisor(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'advisor_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ConversationMessage::class)->orderBy('id');
    }

    /**
     * تعداد پیام‌های خوانده‌نشده‌ای که «طرف مقابلِ» $side فرستاده است.
     * مثال: unreadCountFor('student') = پیام‌های مشاور که دانش‌آموز هنوز نخوانده.
     */
    public function unreadCountFor(string $side): int
    {
        $otherSide = $side === 'student' ? 'advisor' : 'student';

        return $this->messages()
            ->where('sender_type', $otherSide)
            ->whereNull('read_at')
            ->whereNull('deleted_at')
            ->count();
    }

    /** آیا طرفِ $side در ۲۰ ثانیه‌ی اخیر فعال بوده است؟ */
    public function isOnline(string $side): bool
    {
        $value = $this->{"{$side}_last_seen_at"};

        return $value !== null && $value->gt(now()->subSeconds(20));
    }

    /** آیا طرفِ $side همین حالا در حال تایپ است؟ */
    public function isTyping(string $side): bool
    {
        $value = $this->{"{$side}_typing_at"};

        return $value !== null && $value->gt(now()->subSeconds(5));
    }

    /** برچسب «آخرین بازدید …» برای طرفِ $side (یا null اگر هرگز دیده نشده). */
    public function lastSeenLabel(string $side): ?string
    {
        return $this->{"{$side}_last_seen_at"}?->diffForHumans();
    }
}
