<?php

namespace App\Livewire\Admin\Layout;

use App\Models\AdminNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Navbar extends Component
{
    public function markNotificationAsRead(int $notificationId): void
    {
        AdminNotification::where('admin_id', Auth::guard('admin')->id())
            ->whereKey($notificationId)
            ->first()
            ?->markAsRead();
    }

    public function render()
    {
        $adminId = Auth::guard('admin')->id();

        return view('livewire.admin.layout.navbar', [
            'adminNotifications' => AdminNotification::where('admin_id', $adminId)
                ->latest()
                ->limit(7)
                ->get(),
            'adminUnreadNotificationsCount' => AdminNotification::where('admin_id', $adminId)
                ->unread()
                ->count(),
        ])->layout('layouts.admin.app');
    }
}
