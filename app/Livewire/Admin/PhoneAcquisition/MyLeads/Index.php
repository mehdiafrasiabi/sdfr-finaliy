<?php

namespace App\Livewire\Admin\PhoneAcquisition\MyLeads;

use App\Models\PhoneCall;
use App\Models\PhoneLead;
use App\Models\PhoneLeadAssignment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * دانش‌آموزان من — همهٔ شماره‌های تحت پوششِ مشاور جذب تلفنی (در جریان، بسته‌شده، خاکستری)
 * با وضعیت پیگیری، تعداد تماس و مشاهدهٔ جزئیات کامل هر تماس.
 */
class Index extends Component
{
    use WithPagination;

    public string $search       = '';
    public string $statusFilter = 'all'; // all | active | closed | dead

    /** شماره‌ای که جزئیاتش باز شده است. */
    public ?int $selectedLeadId = null;

    protected $queryString = ['search', 'statusFilter'];

    public function updatingSearch(): void       { $this->resetPage(); }
    public function updatingStatusFilter(): void { $this->resetPage(); }

    public function openDetail(int $leadId): void
    {
        $this->selectedLeadId = $leadId;
    }

    public function closeDetail(): void
    {
        $this->selectedLeadId = null;
    }

    protected function assignedToMe($query)
    {
        $adminId = Auth::guard('admin')->id();

        return $query->whereHas('assignments', fn ($q) => $q->where('admin_id', $adminId));
    }

    public function render()
    {
        $leads = $this->assignedToMe(PhoneLead::query())
            ->with(['state:id,name', 'city:id,name'])
            ->withCount('calls')
            ->when($this->statusFilter !== 'all', fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->search, fn ($q) =>
                $q->where(fn ($s) =>
                    $s->where('full_name', 'like', "%{$this->search}%")
                      ->orWhere('mobile', 'like', "%{$this->search}%")
                )
            )
            ->orderByRaw("FIELD(status, 'active', 'closed', 'dead')")
            ->latest()
            ->paginate(15);

        $detailLead = null;
        if ($this->selectedLeadId) {
            $detailLead = $this->assignedToMe(PhoneLead::query())
                ->with(['calls' => fn ($q) => $q->orderByDesc('called_at')->orderByDesc('id'), 'calls.admin:id,name'])
                ->find($this->selectedLeadId);
        }

        return view('livewire.admin.phone-acquisition.my-leads.index', [
            'leads'      => $leads,
            'detailLead' => $detailLead,
        ])->layout('layouts.admin.app');
    }
}
