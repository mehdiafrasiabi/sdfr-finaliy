<?php

namespace App\Livewire\Admin\PhoneAcquisition\FollowUps;

use App\Livewire\Admin\PhoneAcquisition\Concerns\LogsPhoneCalls;
use App\Models\PhoneCall;
use App\Models\PhoneLead;
use App\Models\PhoneLeadAssignment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * پیگیری‌های زمان‌بندی‌شدهٔ مشاور جذب تلفنی.
 * شماره‌هایی که نتیجهٔ آخرین تماسشان «پیگیری» بوده و موعدشان رسیده/نزدیک است.
 */
class Index extends Component
{
    use WithPagination, LogsPhoneCalls;

    /** فقط پیگیری‌های سررسیده را نشان بده. */
    public bool $dueOnly = false;

    public function render()
    {
        $adminId = Auth::guard('admin')->id();

        $leads = PhoneLead::query()
            ->with([
                'state:id,name',
                'city:id,name',
                'calls' => fn ($q) => $q->where('result', PhoneCall::RESULT_FOLLOW_UP)->latest('called_at'),
            ])
            ->where('status', PhoneLead::STATUS_ACTIVE)
            ->where('last_outcome', PhoneCall::RESULT_FOLLOW_UP)
            ->whereHas('assignments', function ($q) use ($adminId) {
                $q->where('admin_id', $adminId)
                    ->where('status', PhoneLeadAssignment::STATUS_ACTIVE);
            })
            ->whereHas('calls', function ($q) {
                $q->where('result', PhoneCall::RESULT_FOLLOW_UP);
                if ($this->dueOnly) {
                    $q->where('follow_up_at', '<=', now());
                }
            })
            ->paginate(15);

        $activeLead = $this->activeLeadId ? PhoneLead::find($this->activeLeadId) : null;

        return view('livewire.admin.phone-acquisition.follow-ups.index', [
            'leads'      => $leads,
            'activeLead' => $activeLead,
            'now'        => now(),
        ])->layout('layouts.admin.app');
    }
}
