<?php

namespace App\Livewire\Client\Profile\Ticket;

use App\Models\Ticket;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Show extends Component
{
    use WithFileUploads, SEOTools;

    public $ticket;
    public $message = '';
    public $attachment;

    public function mount($ticket)
    {
        $user_id = Auth::id();
        $this->ticket = Ticket::with(['messages.user', 'messages.admin', 'department'])
            ->where('ticket_number', $ticket)
            ->firstOrFail();
        // بررسی مالکیت تیکت برای کاربر فعلی
        // نکته: مقایسه با == انجام می‌شود تا روی هاست‌هایی که user_id را به صورت رشته برمی‌گردانند
        // (مثلاً MySQL با PDO::ATTR_EMULATE_PREPARES) خطای 403 رخ ندهد.
        abort_if((int) $this->ticket->user_id !== (int) $user_id, 403);
        // mark admin messages as read
        $this->ticket->messages()
            ->whereNotNull('admin_id')
            ->where('is_read', false)
            ->update(['is_read' => true]);
        $this->seoConfig();
    }

    public function seoConfig ()
    {
        $this->seo()->setTitle(' مشاهده تیکت');
    }

    public function submit()
    {
        // مرحله اول: ولیدیشن
        $this->validate([
            'message' => 'required|string|min:4',
            'attachment' => 'nullable|file|max:10240|mimes:zip,rar',
        ], [
            'message.required' => 'فیلد ضروری است',
            '*.string' => 'فرمت نوشتاری شما اشتباه است ',
            '*.max' => 'حداکثر حجم فایل 10 مگابایت است',
            '*.min' => 'حداقل نوشتن : 4 کاراکتر',
            'attachment.mimes' => 'فقط فرمت zip و rar مجاز است',
        ]);
        // مرحله دوم: ساخت مسیر و انتقال فایل
        $fileName = null;
        if ($this->attachment) {
            $fileName = Str::random(40) . '.' . $this->attachment->getClientOriginalExtension();
            $targetPath = public_path("ticket/" . auth()->id() . "/file");

            if (!File::exists($targetPath)) {
                File::makeDirectory($targetPath, 0755, true);
            }

            // ذخیره موقت فایل در storage/app
            $tmpPath = $this->attachment->store('/', 'local');

            // انتقال فایل به public
            File::move(
                storage_path('app/' . $tmpPath),
                $targetPath . '/' . $fileName
            );
        }

        // مرحله سوم: ذخیره پیام در دیتابیس
        $this->ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => strip_tags($this->message),
            'attachment' => $fileName,
        ]);

        // مرحله چهارم: آپدیت وضعیت تیکت به waiting و قفل ارسال
        $this->ticket->update([
            'status' => 'waiting',
        ]);

        // مرحله پنجم: ریست فیلدها و نمایش پیام موفقیت
        $this->dispatch('success', 'پیام با موفقیت ارسال شد');
        $this->reset(['message', 'attachment']);
        $this->resetValidation();
        $this->ticket->refresh();
        $this->ticket->load(['messages.user', 'messages.admin', 'department']);
    }

    public function render()
    {
        return view('livewire.client.profile.ticket.show', [
            'ticket' => $this->ticket,
        ])->layout('layouts.client.app');
    }
}
