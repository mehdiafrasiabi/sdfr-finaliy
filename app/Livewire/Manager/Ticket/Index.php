<?php

namespace App\Livewire\Manager\Ticket;

use App\Models\Admin;
use App\Models\Ticket;
use App\Models\User;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination,SEOTools;
    public $search = '';
    public $status = '';
    public $priority = '';
    public $department = '';
    public $assigned = '';
    public $perPage = 10;
    public $activeTab = 'all';

    public function mount()
    {
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()->setTitle('لیست تیکت و پشتیبانی');
    }
    public function updating($field)
    {
        if (in_array($field, ['search', 'priority', 'department', 'assigned', 'activeTab'])) {
            $this->resetPage();
        }
    }
    public function render()
    {
        $ticketsQuery = Ticket::query()
            ->with(['user', 'department', 'assignedTo'])
            ->when($this->search, function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhereHas('user', fn($query) => $query->where('name', 'like', "%{$this->search}%"));
            })
            ->when($this->priority, fn($q) => $q->where('priority', $this->priority))
            ->when($this->department, fn($q) => $q->where('department_id', $this->department))
            ->when($this->assigned, fn($q) => $q->where('assigned_admin_id', $this->assigned))
            ->when($this->activeTab !== 'all', function ($query) {
                $query->where('status', $this->activeTab);
            });

        $tickets = $ticketsQuery
            ->orderBy('updated_at', 'desc')
            ->paginate($this->perPage);

        // آمار کلی
        $ticketStats = Ticket::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $waitingTickets = $ticketStats['waiting'] ?? 0;
        $closedTickets = $ticketStats['closed'] ?? 0;
        $answeredTickets = $ticketStats['answered'] ?? 0;
        $totalTickets = array_sum($ticketStats->toArray());


        return view('livewire.manager.ticket.index',[
            'tickets' => $tickets,
            'departments' => \App\Models\Department::all(),
            'admins' => Admin::role('super admin')->get(),
            'totalTickets' => $totalTickets,
            'waitingTickets' => $waitingTickets,
            'closedTickets' => $closedTickets,
            'answeredTickets' => $answeredTickets,
        ])->layout('layouts.manager.app');
    }
}
