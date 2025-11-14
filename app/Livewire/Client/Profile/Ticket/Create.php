<?php

namespace App\Livewire\Client\Profile\Ticket;

use App\Models\Department;
use App\Models\Ticket;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads,SEOTools;

    public $title = '';
    public $department_id;
    public $priority = 'medium';
    public $message = '';
    public $attachment;

    public function mount()
    {
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()->setTitle('  تیکت جدید');
    }
    public function submit()
    {
        $this->validate([
            'title' => 'required|string|min:3|max:255',
            'department_id' => 'required|exists:departments,id',
            'priority' => 'required|in:low,medium,high',
            'message' => 'required|string|min:5',
            'attachment' => 'nullable|file|max:10240|mimes:zip,rar',
        ], [
            '*.required' => 'فیلد ضروری است',
            '*.string' => 'فرمت نوشتاری شما اشتباه است ',
            '*.max' => 'حداکثر حجم فایل 10 مگابایت است',
            '*.min' => 'حداقل نوشتن : 4 کاراکتر',
            '*.mimes' => 'فرمت مجاز: zip یا rar',
        ]);

        $fileName = null;

        if ($this->attachment) {
            // تولید نام فایل
            $fileName = Str::random(40) . '.' . $this->attachment->getClientOriginalExtension();

            // ذخیره در مسیر دائمی
            $this->attachment->storeAs("ticket/" . auth()->id() . "/file", $fileName, 'public');

            // حذف فایل موقت Livewire
            $this->attachment->delete(); // این خط فایل موقت را حذف می‌کند
        }

        $ticket = Ticket::create([
            'ticket_number' => random_int(100000, 999999999),
            'user_id' => auth()->id(),
            'title' => $this->title,
            'department_id' => $this->department_id,
            'priority' => $this->priority,
            'status' => 'waiting',
        ]);

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => strip_tags($this->message),
            'attachment' => $fileName,
        ]);

        session()->flash('success', 'تیکت شما با موفقیت افزوده شد.');
        return redirect()->route('client.profile.ticket.show', $ticket->id);
    }

    public function render()
    {
        return view('livewire.client.profile.ticket.create',[ 'departments' => Department::all(),])->layout('layouts.client.app');
    }
}
