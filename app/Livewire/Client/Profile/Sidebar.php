<?php

namespace App\Livewire\Client\Profile;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

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
        $student = $user?->student;

        if (! $student) {
            $this->unreadCount = 0;
            return;
        }
        $this->unreadCount = Notification::where('student_id', $student->id)
            ->where('is_read', false)
            ->count();
    }

    #[On('notificationAdded')]
    #[On('notificationRead')]
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
