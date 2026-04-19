<?php

namespace App\Livewire\Admin\Consultant\Reschedule;

use App\Models\AdminWorkSchedule;
use App\Models\SessionRescheduleRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public ?int $activeRequestId = null;
    public ?int $proposedDay = null;
    public ?string $proposedTime = null;
    public string $notes = '';

    public function openPropose(int $requestId): void
    {
        $this->activeRequestId = $requestId;
        $req = SessionRescheduleRequest::find($requestId);
        $this->proposedDay = $req?->consultant_proposed_day;
        $this->proposedTime = $req?->consultant_proposed_time ? substr($req->consultant_proposed_time, 0, 5) : null;
        $this->notes = $req?->consultant_notes ?? '';
    }

    public function closePropose(): void
    {
        $this->reset(['activeRequestId', 'proposedDay', 'proposedTime', 'notes']);
    }

    public function savePropose(): void
    {
        $req = SessionRescheduleRequest::find($this->activeRequestId);
        if (!$req) return;
        $admin = Auth::guard('admin')->user();
        if (!$admin || $req->advisor_id !== $admin->id) {
            return;
        }
        if ($this->proposedDay === null || !$this->proposedTime) {
            $this->addError('proposedTime', 'روز و ساعت پیشنهادی را وارد کنید.');
            return;
        }
        $req->update([
            'consultant_proposed_day'  => (int) $this->proposedDay,
            'consultant_proposed_time' => $this->proposedTime,
            'consultant_notes'         => $this->notes ?: null,
            'status'                   => SessionRescheduleRequest::STATUS_AWAITING_STUDENT_CHOICE,
        ]);
        session()->flash('message', 'پیشنهاد شما همراه با اسلات‌های مدیر آموزشی برای دانش‌آموز ارسال شد.');
        $this->closePropose();
    }

    public function declineNoCapacity(int $requestId): void
    {
        $req = SessionRescheduleRequest::find($requestId);
        $admin = Auth::guard('admin')->user();
        if (!$req || !$admin || $req->advisor_id !== $admin->id) return;
        $req->update([
            'status' => SessionRescheduleRequest::STATUS_CONSULTANT_CHANGE,
            'consultant_notes' => $this->notes ?: 'ظرفیت خالی ندارم، نیاز به تعویض مشاور.',
        ]);
        session()->flash('message', 'درخواست تعویض مشاور برای مدیر آموزشی ارسال شد.');
    }

    public function confirmStudentChoice(int $requestId): void
    {
        $req = SessionRescheduleRequest::find($requestId);
        $admin = Auth::guard('admin')->user();
        if (!$req || !$admin || $req->advisor_id !== $admin->id) return;
        if ($req->status !== SessionRescheduleRequest::STATUS_AWAITING_CONSULTANT_CONF) return;

        $req->update([
            'status' => SessionRescheduleRequest::STATUS_AWAITING_MANAGER_FINAL,
        ]);
        session()->flash('message', 'انتخاب دانش‌آموز تایید و برای مدیر آموزشی ارسال شد.');
    }

    public function render()
    {
        $admin = Auth::guard('admin')->user();
        $requests = SessionRescheduleRequest::query()
            ->where('advisor_id', $admin?->id)
            ->with(['student.user'])
            ->whereNotIn('status', [
                SessionRescheduleRequest::STATUS_APPROVED,
                SessionRescheduleRequest::STATUS_REJECTED,
            ])
            ->latest()
            ->paginate(15);

        $activeRequest = $this->activeRequestId
            ? SessionRescheduleRequest::with('student.user')->find($this->activeRequestId)
            : null;

        return view('livewire.admin.consultant.reschedule.index', [
            'requests'      => $requests,
            'days'          => AdminWorkSchedule::DAYS,
            'activeRequest' => $activeRequest,
        ])->layout('layouts.admin.app');
    }
}

