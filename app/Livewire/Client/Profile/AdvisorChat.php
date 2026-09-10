<?php

namespace App\Livewire\Client\Profile;

use App\Livewire\Concerns\ManagesConversationThread;
use App\Models\Conversation;
use App\Models\Student;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * چت زنده‌ی دانش‌آموز با مشاور تحصیلی‌اش.
 * در دوره‌ی هفته‌ی آزمایشی (بدون دسترسی کامل) صفحه قفل است.
 */
class AdvisorChat extends Component
{
    use WithFileUploads, SEOTools, ManagesConversationThread;

    public string $side = 'student';

    public ?Student $student = null;
    public ?Conversation $conversation = null;
    public bool $locked = false;
    public bool $noAdvisor = false;

    public function mount(): void
    {
        // نکته‌ی کارایی: به‌جای یک کوئری مستقیم و جدا، از رابطه‌ی User::student()
        // (که دقیقاً همان hasOne بر اساس user_id است) استفاده می‌کنیم. چون Auth::user()
        // در طول همین request کش می‌شود، این رابطه هم روی همان کالکشن در حافظه لود/کش
        // می‌شود؛ اگر بعداً همان کاربر (مثلاً در لایوت client.app که auth()->user()?->student
        // را هم می‌خواند) دوباره به این رابطه نیاز داشته باشد، کوئری تکراری زده نمی‌شود.
        $this->student = Auth::user()?->student;
        abort_if(! $this->student, 403);

        // قفلِ دوره‌ی هفته‌ی آزمایشی: تا قبل از دسترسی کامل، این بخش بسته است.
        if ($this->student->isAdvisorChatLocked()) {
            $this->locked = true;
            $this->seo()->setTitle('ارتباط با مشاور');
            return;
        }

        // هنوز مشاوری به دانش‌آموز تخصیص نیافته است.
        if (! $this->student->advisor_id) {
            $this->noAdvisor = true;
            $this->seo()->setTitle('ارتباط با مشاور');
            return;
        }

        $this->conversation = Conversation::firstOrCreate(
            ['student_id' => $this->student->id],
            ['advisor_id' => $this->student->advisor_id],
        );

        // اگر مشاور دانش‌آموز عوض شده، مکالمه را با مشاور فعلی همگام کن.
        if ((int) $this->conversation->advisor_id !== (int) $this->student->advisor_id) {
            $this->conversation->update(['advisor_id' => $this->student->advisor_id]);
        }

        // باز کردن صفحه = خواندن پیام‌های مشاور.
        $this->tick();
        $this->seo()->setTitle('ارتباط با مشاور');
    }

    public function render()
    {
        $messages = $this->conversation
            ? $this->conversation->messages()
                ->with('replyTo')
                ->whereNull('deleted_at')        // پیام‌های حذف‌شده‌ی سراسری اصلاً نمایش داده نمی‌شوند (بدون tombstone)
                ->whereNull('student_deleted_at') // پیام‌هایی که خودِ دانش‌آموز پنهان کرده
                ->get()
            : collect();

        return view('livewire.client.profile.advisor-chat', [
            'messages' => $messages,
            'advisor'  => $this->conversation?->advisor,
        ])->layout('layouts.client.app');
    }
}
