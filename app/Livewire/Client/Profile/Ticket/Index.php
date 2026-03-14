<?php

namespace App\Livewire\Client\Profile\Ticket;

use App\Models\Ticket;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use SEOTools, WithPagination;


    public string $search = '';
    public string $statusFilter = '';
    public string $priorityFilter = '';

    protected $queryString = ['search', 'statusFilter', 'priorityFilter'];
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
        ])->layout('layouts.client.app');    }
}
