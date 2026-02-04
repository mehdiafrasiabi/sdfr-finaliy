<?php

namespace App\Livewire\Client\Profile;

use App\Models\NotificationRecipient;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class Sidebar extends Component
{
    public $photo;
    public $unreadCount = 0;

    public function mount()
    {
        $this->loadUnreadCount();
    }
    public function loadUnreadCount()
    {
        $user = Auth::user();

        if (! $user) {
            $this->unreadCount = 0;
            return;
        }

        $this->unreadCount = NotificationRecipient::where('user_id', $user->id)
            ->where('is_read', false)
            ->where('created_at', '>=', now()->subDays(5)) // فقط پیام‌های 5 روز اخیر
            ->where('is_read', false)
            ->count();
    }

    #[On('notificationAdded')]
    public function refreshUnreadCount()
    {
        $this->loadUnreadCount();
    }
    public function getProfilePictureUrlAttribute()
    {
        $path = public_path("user/img/{$this->id}/{$this->picture}");
        if ($this->picture && file_exists($path)) {
            return asset("user/img/{$this->id}/{$this->picture}");
        }
        return asset('client/assets/images/avatars/01.jpeg');
    }

    public function render()
    {
        return view('livewire.client.profile.sidebar')->layout('layouts.client.app');
    }
}
