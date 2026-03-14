<?php

namespace App\Livewire\Admin\Ticket;

use App\Models\Department;
use App\Models\Ticket;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use SEOTools, WithPagination;

    public string $search = '';
    public string $status = '';
    public string $priority = '';
    public string $department = '';
    public string $activeTab = 'all';

    protected $queryString = ['search', 'status', 'priority', 'department', 'activeTab'];

    public function mount()
    {
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()->setTitle('مدیریت تیکت‌ها');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->status = $tab === 'all' ? '' : $tab;
        $this->resetPage();
    }

    public function render()
    {
        $stats = [
            'total' => Ticket::count(),
            'waiting' => Ticket::where('status', 'waiting')->count(),
            'answered' => Ticket::where('status', 'answered')->count(),
            'closed' => Ticket::where('status', 'closed')->count(),
        ];

        $tickets = Ticket::with(['user', 'department', 'assignedAdmin'])
            ->when($this->search, function ($q) {
                $q->where(function ($q) {
                    $q->where('title', 'like', "%{$this->search}%")
                        ->orWhere('ticket_number', 'like', "%{$this->search}%")
                        ->orWhereHas('user', function ($q) {
                            $q->where('name', 'like', "%{$this->search}%");
                        });
                });
            })
            ->when($this->status, function ($q) {
                $q->where('status', $this->status);
            })
            ->when($this->activeTab !== 'all' && !$this->status, function ($q) {
                $q->where('status', $this->activeTab);
            })
            ->when($this->priority, function ($q) {
                $q->where('priority', $this->priority);
            })
            ->when($this->department, function ($q) {
                $q->where('department_id', $this->department);
            })
            ->latest('updated_at')
            ->paginate(15);

        $departments = Department::all();

        return view('livewire.admin.ticket.index', [
            'tickets' => $tickets,
            'stats' => $stats,
            'departments' => $departments,
        ])->layout('layouts.admin.app');
    }
}
