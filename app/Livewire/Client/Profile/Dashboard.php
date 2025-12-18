<?php

namespace App\Livewire\Client\Profile;

use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

use App\Models\NotificationRecipient;

class Dashboard extends Component

{

    public $student;

    public $user;


    use SEOTools;


    public function mount()

    {

        $this->seoConfig();

        $this->user = Auth::user();

        $this->student = $this->user->student ?? null;

    }


    public function seoConfig()

    {

        $this->seo()->setTitle('پیشخوان');

    }


    /**
     * تعداد پیام‌های خوانده نشده
     */

    public function getUnreadNotificationsCount(): int

    {

        return NotificationRecipient::where('user_id', $this->user->id)
            ->where('is_read', false)
            ->count();

    }


    public function render()

    {

        $supporterStudent = $this->student?->supporterStudent;

        $advisorStudent = $this->student?->advisor;

        $unreadNotificationsCount = $this->getUnreadNotificationsCount();


        return view('livewire.client.profile.dashboard', [

            'supporterStudent' => $supporterStudent,

            'advisorStudent' => $advisorStudent,

            'unreadNotificationsCount' => $unreadNotificationsCount,

        ])->layout('layouts.client.app');

    }

}

