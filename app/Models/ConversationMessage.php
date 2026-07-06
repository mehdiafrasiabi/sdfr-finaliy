<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConversationMessage extends Model
{
    protected $guarded = [];

    protected $casts = [
        'read_at'            => 'datetime',
        'edited_at'          => 'datetime',
        'deleted_at'         => 'datetime',
        'student_deleted_at' => 'datetime',
        'advisor_deleted_at' => 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /** پیامی که این پیام در پاسخ به آن ارسال شده (ریپلای). */
    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(ConversationMessage::class, 'reply_to_id');
    }

    public function getIsDeletedAttribute(): bool
    {
        return $this->deleted_at !== null;
    }

    public function getIsEditedAttribute(): bool
    {
        return $this->edited_at !== null;
    }
}
