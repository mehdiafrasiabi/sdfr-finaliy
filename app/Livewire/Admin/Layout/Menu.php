<?php

namespace App\Livewire\Admin\Layout;

use App\Models\AdminNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Menu extends Component
{
    public function render()
    {
        return view('livewire.admin.layout.menu', [
            'adminUnreadNotificationsCount' => AdminNotification::where('admin_id', Auth::guard('admin')->id())
                ->unread()
                ->count(),
        ])->layout('layouts.admin.app');
    }
}
