<?php

namespace App\Livewire\Client\Profile;

use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Notification as ModelsNotification;

class Notification extends Component
{
    use SEOTools, WithPagination;

    public $student;




    public function mount()
    {
        $this->seoConfig();
        // این خط رو تغییر بدید تا مطمئن بشید student مقدار داره
        $this->student = Auth::user()->student ?? null;
    }

    public function seoConfig()
    {
        $this->seo()->setTitle('پیشخوان');
    }

    public function markAsRead($id)
    {
        // قبل از هر چیز، مطمئن شوید که student وجود دارد.
        if (!$this->student) {
            $this->dispatch('error', 'دانش‌آموز یافت نشد.');
            return;
        }

        // نوتیفیکیشن رو بر اساس student_id و id نوتیفیکیشن پیدا کنید
        $notif = ModelsNotification::where('id', $id)
            ->where('student_id', $this->student->id)
            ->first();

        // اگر نوتیفیکیشن پیدا شد، اون رو به‌روزرسانی کنید
        if ($notif) {
            $notif->update(['is_read' => true]);
            // پیام موفقیت رو ارسال کنید
            $this->dispatch('success', 'پیام با موفقیت خوانده شد.');
        } else {
            $this->dispatch('error', 'نوتیفیکیشن پیدا نشد.');
        }
    }

    public function render()
    {
        $studentId = Auth::user()->student->id ?? null;
        $notifications = ModelsNotification::where('student_id', $studentId)->latest()->paginate(10);

        return view('livewire.client.profile.notification',[
            'notifications' => $notifications,
        ])->layout('layouts.client.app');
    }
}
