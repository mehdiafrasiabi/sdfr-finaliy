<?php

namespace App\Livewire\Admin\Ticket;

use App\Models\Ticket;
use Artesaos\SEOTools\Traits\SEOTools;
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
        $this->ticket = Ticket::with(['messages.user', 'messages.admin', 'department', 'user', 'assignedAdmin'])->findOrFail($ticket);

        // assign admin if not assigned
        if (!$this->ticket->assigned_admin_id) {
            $this->ticket->update(['assigned_admin_id' => auth('admin')->id()]);
            $this->ticket->refresh();
        }

        // mark user messages as read
        $this->ticket->messages()
            ->whereNotNull('user_id')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()->setTitle('مشاهده تیکت #' . $this->ticket->ticket_number);
    }

    public function submit()
    {
        $this->validate([
            'message' => 'required|string|min:2',
            'attachment' => 'nullable|file|max:10240|mimes:zip,rar,jpg,jpeg,png,pdf',
        ], [
            'message.required' => 'متن پاسخ الزامی است',
            '*.min' => 'حداقل 2 کاراکتر وارد کنید',
            '*.max' => 'حداکثر حجم فایل 10 مگابایت',
            'attachment.mimes' => 'فرمت مجاز: zip, rar, jpg, png, pdf',
        ]);

        $fileName = null;
        if ($this->attachment) {
            $fileName = Str::random(40) . '.' . $this->attachment->getClientOriginalExtension();
            $targetPath = public_path("ticket/" . $this->ticket->user_id . "/file");

            if (!File::exists($targetPath)) {
                File::makeDirectory($targetPath, 0755, true);
            }

            $tmpPath = $this->attachment->store('/', 'local');
            File::move(
                storage_path('app/' . $tmpPath),
                $targetPath . '/' . $fileName
            );
        }

        $this->ticket->messages()->create([
            'admin_id' => auth('admin')->id(),
            'message' => strip_tags($this->message),
            'attachment' => $fileName,
        ]);

        $this->ticket->update([
            'status' => 'answered',
        ]);

        session()->flash('success', 'پاسخ با موفقیت ارسال شد');
        $this->reset(['message', 'attachment']);
        $this->resetValidation();
        $this->ticket->refresh();
        $this->ticket->load(['messages.user', 'messages.admin', 'department', 'user', 'assignedAdmin']);
    }

    public function closeTicket()
    {
        $this->ticket->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        session()->flash('success', 'تیکت با موفقیت بسته شد');
        $this->ticket->refresh();
    }

    public function reopenTicket()
    {
        $this->ticket->update([
            'status' => 'answered',
            'closed_at' => null,
        ]);

        session()->flash('success', 'تیکت مجددا باز شد');
        $this->ticket->refresh();
    }

    public function render()
    {
        return view('livewire.admin.ticket.show', [
            'ticket' => $this->ticket,
        ])->layout('layouts.admin.app');
    }
}
