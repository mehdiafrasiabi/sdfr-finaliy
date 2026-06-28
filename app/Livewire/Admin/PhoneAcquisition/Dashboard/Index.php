<?php

namespace App\Livewire\Admin\PhoneAcquisition\Dashboard;

use App\Livewire\Admin\PhoneAcquisition\Concerns\LogsPhoneCalls;
use App\Models\PhoneLead;
use App\Models\PhoneLeadAssignment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * داشبورد مشاور جذب تلفنی — شماره‌هایی که موعد تماس مجددشان رسیده است
 * (تماس‌های ناموفقِ موکول‌شده و پیگیری‌های موفقِ سررسیده). امکان ثبت تماس در همین صفحه.
 */
class Index extends Component
{
    use WithPagination, LogsPhoneCalls;

    public function render()
    {
        $adminId = Auth::guard('admin')->id();

        $base = fn () => PhoneLead::query()
            ->dueForCall()
            ->whereHas('assignments', fn ($q) =>
                $q->where('admin_id', $adminId)
                  ->where('status', PhoneLeadAssignment::STATUS_ACTIVE)
            );

        $leads = $base()
            ->with(['state:id,name', 'city:id,name'])
            ->withCount('calls')
            ->orderBy('next_call_at')
            ->paginate(15);

        $activeLead = $this->activeLeadId ? PhoneLead::find($this->activeLeadId) : null;

        return view('livewire.admin.phone-acquisition.dashboard.index', [
            'leads'      => $leads,
            'dueCount'   => $base()->count(),
            'activeLead' => $activeLead,
            'now'        => now(),
        ])->layout('layouts.admin.app');
    }
}
