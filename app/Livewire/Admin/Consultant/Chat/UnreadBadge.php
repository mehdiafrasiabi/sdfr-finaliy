<?php

namespace App\Livewire\Admin\Consultant\Chat;

use App\Models\ConversationMessage;
use Livewire\Component;

/**
 * نشانگر تعداد کلِ پیام‌های نخوانده‌ی مشاور (در منوی پنل).
 */
class UnreadBadge extends Component
{
    public int $count = 0;

    public function mount(): void
    {
        $this->loadCount();
    }

    public function loadCount(): void
    {
        $advisorId = auth('admin')->id();

        $this->count = ConversationMessage::query()
            ->where('sender_type', 'student')
            ->whereNull('read_at')
            ->whereNull('deleted_at')
            ->whereHas('conversation', fn ($q) => $q->where('advisor_id', $advisorId))
            ->count();
    }

    public function render()
    {
        return view('livewire.admin.consultant.chat.unread-badge');
    }
}
