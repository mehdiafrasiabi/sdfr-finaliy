<?php

namespace App\Livewire\Client\Layout;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class Header extends Component
{
    public ?string $profilePictureUrl = null;

    public ?string $gender = null;
    public $unreadCount = 0;

    public function mount()
    {
        $this->loadUnreadCount();
        $this->loadUserProfileData();
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

    public function loadUserProfileData(): void
    {
        $user = Auth::user();

        if (! $user) {
            $this->profilePictureUrl = null;
            $this->gender = null;
            return;
        }

        $this->gender = $user->profile?->gender;
        $this->profilePictureUrl = $this->resolveUserPictureUrl($user);
    }

    protected function resolveUserPictureUrl($user): ?string
    {
        if (! $user?->picture) {
            return null;
        }

        $picture = ltrim($user->picture, '/');
        if (file_exists(public_path($picture))) {
            return asset($picture);
        }

        $legacyPath = "user/img/{$user->id}/{$user->picture}";
        if (file_exists(public_path($legacyPath))) {
            return asset($legacyPath);
        }

        return null;
    }

    public function getDefaultAvatarTypeProperty(): string
    {
        return $this->gender === 'female' ? 'female' : 'male';
    }

    #[On('notificationAdded')]
    #[On('notificationRead')]
    public function refreshUnreadCount()
    {
        $this->loadUnreadCount();
    }
    public function render()
    {
        return view('livewire.client.layout.header')->layout('layouts.client.app');
    }
}
