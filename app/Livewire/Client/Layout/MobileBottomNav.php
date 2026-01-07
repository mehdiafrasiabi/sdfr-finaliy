<?php


namespace App\Livewire\Client\Layout;


use App\Models\Notification;

use Illuminate\Support\Facades\Auth;

use Livewire\Component;

use Livewire\Attributes\On;


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

        $student = $user?->student;


        if (!$student) {

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


    public function render()

    {

        return view('livewire.client.layout.mobile-bottom-nav');

    }

}
