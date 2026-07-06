<?php

namespace App\Livewire\Admin\PhoneAcquisition\Dashboard;

use App\Livewire\Admin\PhoneAcquisition\Concerns\LogsPhoneCalls;
use App\Models\PhoneCall;
use App\Models\PhoneLead;
use App\Models\PhoneLeadAssignment;
use App\Models\RegistrationGoal;
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

        // اهداف فعال (تیمی + شخصیِ این مشاور) که مهلتشان نگذشته است + پیشرفت.
        $goals = RegistrationGoal::query()
            ->where(fn ($q) => $q->whereNull('admin_id')->orWhere('admin_id', $adminId))
            ->whereDate('goal_date', '>=', now()->toDateString())
            ->orderBy('goal_date')
            ->get()
            ->map(function (RegistrationGoal $goal) use ($adminId) {
                $q = PhoneCall::where('result', PhoneCall::RESULT_REGISTERED);
                // هدف تیمی → کل تیم؛ هدف شخصی → فقط همین مشاور.
                $goal->achieved = $goal->admin_id ? $q->where('admin_id', $goal->admin_id)->count() : $q->count();
                return $goal;
            });

        return view('livewire.admin.phone-acquisition.dashboard.index', [
            'leads'      => $leads,
            'dueCount'   => $base()->count(),
            'goals'      => $goals,
            'activeLead' => $activeLead,
            'now'        => now(),
        ])->layout('layouts.admin.app');
    }
}
