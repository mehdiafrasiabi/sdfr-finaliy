<?php

namespace App\Livewire\Admin\EducationalManager\PhoneAcquisition\Receipts;

use App\Models\ChargeReceipt;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * مدیر آموزشی — بررسی و تایید/رد رسیدهای شارژ مشاوران جذب تلفنی.
 */
class Index extends Component
{
    use WithPagination;

    public string $statusFilter = 'pending';

    // فرم رد کردن
    public ?int $rejectingId = null;
    public string $rejectNote = '';

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function approveReceipt(int $id): void
    {
        $receipt = ChargeReceipt::find($id);
        if (! $receipt) {
            return;
        }

        $receipt->update([
            'status'      => ChargeReceipt::STATUS_APPROVED,
            'note'        => null,
            'reviewed_by' => Auth::guard('admin')->id(),
            'reviewed_at' => now(),
        ]);

        $this->dispatch('success', 'رسید تایید شد.');
    }

    public function openReject(int $id): void
    {
        $this->rejectingId = $id;
        $this->rejectNote = '';
        $this->resetErrorBag();
    }

    public function closeReject(): void
    {
        $this->rejectingId = null;
        $this->rejectNote = '';
        $this->resetErrorBag();
    }

    public function rejectReceipt(): void
    {
        $this->validate([
            'rejectNote' => ['required', 'string', 'max:500'],
        ], [
            'rejectNote.required' => 'علت رد کردن را بنویسید.',
        ]);

        $receipt = ChargeReceipt::find($this->rejectingId);
        if ($receipt) {
            $receipt->update([
                'status'      => ChargeReceipt::STATUS_REJECTED,
                'note'        => $this->rejectNote,
                'reviewed_by' => Auth::guard('admin')->id(),
                'reviewed_at' => now(),
            ]);
            $this->dispatch('success', 'رسید رد شد.');
        }

        $this->closeReject();
    }

    public function render()
    {
        $receipts = ChargeReceipt::with('admin:id,name', 'reviewer:id,name')
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(12);

        return view('livewire.admin.educational-manager.phone-acquisition.receipts.index', [
            'receipts' => $receipts,
        ])->layout('layouts.admin.app');
    }
}
