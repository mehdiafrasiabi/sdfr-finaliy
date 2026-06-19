<?php

namespace App\Livewire\Admin\PhoneAcquisition\Queue;

use App\Livewire\Admin\PhoneAcquisition\Concerns\LogsPhoneCalls;
use App\Models\PhoneLead;
use App\Models\PhoneLeadAssignment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * صف مشاور جذب تلفنی — شماره‌های فعالِ اختصاص‌یافته به‌صورت کادرهای رنگی زنده.
 * ثبت تماس در لحظه (موفق/ناموفق).
 */
class Index extends Component
{
    use WithPagination, LogsPhoneCalls;

    public string $search = '';

    protected $queryString = ['search'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $adminId = Auth::guard('admin')->id();

        $leads = PhoneLead::query()
            ->with(['state:id,name', 'city:id,name'])
            ->where('status', PhoneLead::STATUS_ACTIVE)
            ->whereHas('assignments', function ($q) use ($adminId) {
                $q->where('admin_id', $adminId)
                    ->where('status', PhoneLeadAssignment::STATUS_ACTIVE);
            })
            // پیگیری‌های زمان‌بندی‌شده برای آینده اینجا نمایش داده نمی‌شوند (در صفحهٔ پیگیری‌ها)
            ->where(function ($q) {
                $q->whereNull('last_outcome')
                    ->orWhere('last_outcome', '!=', \App\Models\PhoneCall::RESULT_FOLLOW_UP);
            })
            ->when($this->search, fn ($q) => $q->where(function ($s) {
                $s->where('full_name', 'like', "%{$this->search}%")
                    ->orWhere('mobile', 'like', "%{$this->search}%");
            }))
            ->withCount('calls')
            ->orderBy('attempts_count')
            ->latest()
            ->paginate(15);

        $activeLead = $this->activeLeadId ? PhoneLead::find($this->activeLeadId) : null;

        return view('livewire.admin.phone-acquisition.queue.index', [
            'leads'      => $leads,
            'activeLead' => $activeLead,
        ])->layout('layouts.admin.app');
    }
}
