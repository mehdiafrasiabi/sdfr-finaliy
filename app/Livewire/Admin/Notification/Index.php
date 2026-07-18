<?php

namespace App\Livewire\Admin\Notification;

use App\Models\AdminNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $filter = 'unread';

    protected $paginationTheme = 'bootstrap';

    public function setFilter(string $filter): void
    {
        $this->filter = in_array($filter, ['unread', 'all'], true) ? $filter : 'unread';
        $this->resetPage();
    }

    public function markAsRead(int $notificationId): void
    {
        AdminNotification::where('admin_id', Auth::guard('admin')->id())
            ->whereKey($notificationId)
            ->first()
            ?->markAsRead();
    }

    public function markAllAsRead(): void
    {
        AdminNotification::where('admin_id', Auth::guard('admin')->id())
            ->unread()
            ->update(['read_at' => now()]);
    }

    public function render()
    {
        $adminId = Auth::guard('admin')->id();

        $query = AdminNotification::query()
            ->with(['student.user.personalInformation', 'phoneLead'])
            ->where('admin_id', $adminId)
            ->latest();

        if ($this->filter === 'unread') {
            $query->unread();
        }

        return view('livewire.admin.notification.index', [
            'notifications' => $query->paginate(12),
            'unreadCount' => AdminNotification::where('admin_id', $adminId)->unread()->count(),
            'totalCount' => AdminNotification::where('admin_id', $adminId)->count(),
        ])->layout('layouts.admin.app');
    }
}
