<?php


namespace App\Livewire\Client\Layout;


use App\Models\Notification;

use Illuminate\Support\Facades\Auth;

use Livewire\Component;

use Livewire\Attributes\On;

use App\Models\NotificationRecipient;
class MobileBottomNav extends Component

{

    public $unreadCount = 0;


    public function mount()

    {

        $this->loadUnreadCount();

    }


    public function loadUnreadCount()

    {

        $user = Auth::user();

        if (!$user) {

            $this->unreadCount = 0;

            return;

        }

        $this->unreadCount = NotificationRecipient::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

    }


    #[On('notificationAdded')]

    public function refreshUnreadCount()

    {

        $this->loadUnreadCount();

    }


    public function render()

    {

        return view('livewire.client.layout.mobile-bottom-nav');

    }

}
