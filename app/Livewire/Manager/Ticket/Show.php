<?php

namespace App\Livewire\Manager\Ticket;

use App\Models\Ticket;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Show extends Component
{
    use WithFileUploads,SEOTools;

    public Ticket $ticket;
    public $message = '';
    public $attachment;

    public function mount(Ticket $ticket)
    {
        $this->ticket = $ticket->load(['messages.user', 'user', 'department']);
        $this->seo()->setTitle($ticket->user->name);

    }


    public function submit()
    {
        $validator = Validator::make([
            'message' => $this->message,
            'attachment' => $this->attachment,
        ], [
            'message' => 'required|string|min:3',
            'attachment' => 'nullable|file|max:10240|mimes:zip,rar',
        ])->validate();

        $fileName = null;

        if ($this->attachment) {
            $fileName = Str::random(40) . '.' . $this->attachment->getClientOriginalExtension();

            // ذخیره فایل اصلی
            $this->attachment->storeAs("ticket/{$this->ticket->user_id}/file", $fileName, 'public');

            // حذف فایل موقتی Livewire
            $this->attachment->delete();
        }

        // ثبت پیام جدید
        $this->ticket->messages()->create([
            'admin_id' => auth()->id(),
            'message' => $this->message,
            'attachment' => $fileName,
        ]);

        // آپدیت وضعیت تیکت
        $this->ticket->update([
            'status' => 'answered',
        ]);

        // ریست فیلدهای فرم
        $this->reset(['message', 'attachment']);

        // پیام موفقیت
        $this->dispatch('success', 'پاسخ با موفقیت ارسال شد.');
    }


    public function closeTicket()
    {
        $this->ticket->update([
            'status' => 'closed',
        ]);
        $this->dispatch('success', 'تیکت بسته شد.');
    }

    public function render()
    {
        return view('livewire.manager.ticket.show')->layout('layouts.manager.app');
    }
}
