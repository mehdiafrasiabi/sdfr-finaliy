<?php

namespace App\Livewire\Admin\Consultant\Leave;

use App\Models\AdvisorLeave;
use Artesaos\SEOTools\Traits\SEOTools;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;

/**
 * پنل مشاور — ثبتِ درخواستِ مرخصی برای یک روز (حداقل ۷۲ ساعت قبل).
 * درخواست پس از تاییدِ مدیر آموزشی اعمال می‌شود.
 */
class Index extends Component
{
    use WithPagination, SEOTools;

    /** تاریخِ میلادیِ روزِ مرخصی (Y-m-d) — از فهرستِ روزهای مجاز انتخاب می‌شود. */
    public string $leaveDate = '';
    public string $reason = '';

    public function mount(): void
    {
        $this->seo()->setTitle('مرخصی مشاور');
    }

    /** فهرستِ روزهای مجاز برای مرخصی: از ۷۲ ساعتِ بعد تا ۳ هفته‌ی آینده. */
    protected function allowedDates(): array
    {
        $start = Carbon::now()->addHours(AdvisorLeave::MIN_LEAD_HOURS)->startOfDay();
        $dates = [];
        for ($i = 0; $i < 21; $i++) {
            $d = $start->copy()->addDays($i);
            $dates[$d->toDateString()] = Jalalian::fromCarbon($d)->format('l Y/m/d');
        }
        return $dates;
    }

    public function submit(): void
    {
        $allowed = $this->allowedDates();

        $this->validate([
            'leaveDate' => 'required|date',
            'reason'    => 'nullable|string|max:500',
        ], [
            'leaveDate.required' => 'روزِ مرخصی را انتخاب کنید.',
        ]);

        if (! array_key_exists($this->leaveDate, $allowed)) {
            $this->dispatch('warning', 'مرخصی باید حداقل ۷۲ ساعت قبل و در بازه‌ی مجاز ثبت شود.');
            return;
        }

        $advisorId = auth('admin')->id();

        $exists = AdvisorLeave::where('advisor_id', $advisorId)
            ->whereDate('leave_date', $this->leaveDate)
            ->whereIn('status', [AdvisorLeave::STATUS_PENDING, AdvisorLeave::STATUS_APPROVED])
            ->exists();

        if ($exists) {
            $this->dispatch('warning', 'برای این روز قبلاً درخواستِ مرخصی ثبت کرده‌اید.');
            return;
        }

        AdvisorLeave::create([
            'advisor_id' => $advisorId,
            'leave_date' => $this->leaveDate,
            'reason'     => $this->reason ?: null,
            'status'     => AdvisorLeave::STATUS_PENDING,
        ]);

        $this->reset(['leaveDate', 'reason']);
        $this->dispatch('success', 'درخواستِ مرخصی ثبت شد و برای تاییدِ مدیر آموزشی ارسال گردید.');
    }

    public function render()
    {
        $leaves = AdvisorLeave::where('advisor_id', auth('admin')->id())
            ->latest()
            ->paginate(10);

        return view('livewire.admin.consultant.leave.index', [
            'leaves'       => $leaves,
            'allowedDates' => $this->allowedDates(),
        ])->layout('layouts.admin.app');
    }
}
