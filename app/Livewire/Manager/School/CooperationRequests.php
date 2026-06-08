<?php

namespace App\Livewire\Manager\School;

use App\Models\SchoolCooperationRequest;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use Livewire\WithPagination;

class CooperationRequests extends Component
{
    use WithPagination, SEOTools;

    public string $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->seo()->setTitle('درخواست‌های همکاری مدارس');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function toggleReviewed(int $id): void
    {
        $request = SchoolCooperationRequest::find($id);
        if (! $request) {
            return;
        }
        $request->update(['is_reviewed' => ! $request->is_reviewed]);
        $this->dispatch('success', 'وضعیت درخواست بروزرسانی شد');
    }

    public function delete(int $id): void
    {
        $request = SchoolCooperationRequest::find($id);
        if (! $request) {
            return;
        }
        $request->delete();
        $this->dispatch('success', 'درخواست با موفقیت حذف شد');
    }

    public function render()
    {
        $requests = SchoolCooperationRequest::query()
            ->with(['state:id,name', 'city:id,name'])
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('full_name', 'like', "%{$this->search}%")
                    ->orWhere('school_name', 'like', "%{$this->search}%")
                    ->orWhere('mobile', 'like', "%{$this->search}%");
            }))
            ->latest()
            ->paginate(15);

        return view('livewire.manager.school.cooperation-requests', ['requests' => $requests])
            ->layout('layouts.manager.app');
    }
}
