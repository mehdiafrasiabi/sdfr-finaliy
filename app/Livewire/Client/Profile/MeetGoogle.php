<?php

namespace App\Livewire\Client\Profile;

use App\Models\AdvisingSession;
use App\Models\MeetLinkGoogle;
use App\Models\PreAdvisingSession;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Artesaos\SEOTools\Traits\SEOTools;

class MeetGoogle extends Component
{
    use WithPagination, SEOTools;

    public $showPreAdvisingModal = false;
    public $alreadySubmitted = false;
    public $studentId = null;


    public function mount()
    {
        // اگر کاربر لاگین نکرده، ریدایرکت به لاگین (یا abort/نمایش پیام)
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        // گرفتن user و student به صورت ایمن
        $user = Auth::user();

        // اگر رابطه student وجود ندارد، تصمیم بگیر چه کنیم:
        // گزینه: redirect به صفحهٔ پروفایل یا نمایش پیام خطا
        if (! isset($user->student) || $user->student === null) {
            // مثال: ریدایرکت به صفحهٔ تکمیل پروفایل دانش‌آموز
            $this->dispatch('warning', 'پروفایل دانش‌آموز شما کامل نیست. لطفاً ابتدا اطلاعات دانش‌آموز را تکمیل کنید.');
            return redirect()->route('client.profile.edit'); // مسیر دلخواهت رو بذار
        }

        $this->studentId = $user->student->id;

        // بررسی اینکه آیا قبلاً پیش‌مشاوره ثبت شده
        $this->alreadySubmitted = PreAdvisingSession::where('student_id', $this->studentId)->exists();

        $this->seo()->setTitle('جلسات مشاوره');
    }

    public function openPreAdvisingModal()
    {
        $this->showPreAdvisingModal = true;
    }

    #[\Livewire\Attributes\On('closePreAdvisingModal')]
    public function closePreAdvisingModal()
    {
        $this->showPreAdvisingModal = false;
    }

    #[\Livewire\Attributes\On('handlePreAdvisingSaved')]
    public function handlePreAdvisingSaved()
    {
        $this->alreadySubmitted = true;
        $this->showPreAdvisingModal = false;
    }

    public function render()
    {
        // اگر studentId موجود نیست، صفحات خالی یا redirect را مدیریت کن
        if (! $this->studentId) {
            return view('livewire.client.profile.meet-google-empty')->layout('layouts.client.app');
        }

        $sessions = AdvisingSession::where('student_id', $this->studentId)
            ->orderBy('activation_date', 'desc')
            ->paginate(10);
        $googleMeet = MeetLinkGoogle::query()->where('student_id', $this->studentId)->paginate(10);
        return view('livewire.client.profile.meet-google', [
            'sessions' => $sessions, 'googleMeet' => $googleMeet
        ])->layout('layouts.client.app');
    }
}
