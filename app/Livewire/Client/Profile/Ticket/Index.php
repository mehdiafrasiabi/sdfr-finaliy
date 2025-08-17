<?php

namespace App\Livewire\Client\Profile\Ticket;

use App\Models\Ticket;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use SEOTools,WithPagination;


    public string $search = '';
    public function mount()
    {
        $this->seoConfig();
    }
    public function seoConfig()
    {
        $this->seo()
            ->setTitle('پشتیبانی');
    }


    protected $updatesQueryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $tickets = Ticket::with('department')
            ->where('user_id', auth()->id())
            ->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('status', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(10);

        return view('livewire.client.profile.ticket.index', ['tickets' => $tickets])->layout('layouts.client.app');
    }
}
