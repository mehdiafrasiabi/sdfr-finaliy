<?php

namespace App\Livewire\Client\Profile\Ticket;

use App\Models\Department;
use App\Models\Ticket;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use SEOTools, WithPagination, WithFileUploads;

    public string $search = '';
    public string $statusFilter = '';
    public string $priorityFilter = '';

    protected $queryString = ['search', 'statusFilter', 'priorityFilter'];

    // ==== مودال ارسال تیکت ====
    public bool $showCreateModal = false;

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
        $this->seo()->setTitle('پشتیبانی');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPriorityFilter()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetCreateForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetCreateForm();
    }

    private function resetCreateForm()
    {
        $this->reset(['title', 'department_id', 'priority', 'message', 'attachment']);
        $this->priority = 'medium';
        $this->resetErrorBag();
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

        $this->closeCreateModal();
        $this->resetPage();

        $this->dispatch('toast', message: 'تیکت شما با موفقیت ثبت شد.', type: 'success');
    }

    public function render()
    {
        $userId = auth()->id();

        $stats = [
            'total' => Ticket::where('user_id', $userId)->count(),
            'waiting' => Ticket::where('user_id', $userId)->where('status', 'waiting')->count(),
            'answered' => Ticket::where('user_id', $userId)->where('status', 'answered')->count(),
            'closed' => Ticket::where('user_id', $userId)->where('status', 'closed')->count(),
        ];

        $tickets = Ticket::with('department')
            ->where('user_id', $userId)
            ->when($this->search, function ($q) {
                $q->where(function ($q) {
                    $q->where('title', 'like', "%{$this->search}%")
                        ->orWhere('ticket_number', 'like', "%{$this->search}%");
                });
            })
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->priorityFilter, function ($q) {
                $q->where('priority', $this->priorityFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.client.profile.ticket.index', [
            'tickets' => $tickets,
            'stats' => $stats,
            'departments' => Department::all(),
        ])->layout('layouts.client.app');
    }
}
