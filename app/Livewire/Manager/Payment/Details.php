<?php

namespace App\Livewire\Manager\Payment;

use App\Models\CouponUsage;
use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * D-3 — صفحهٔ «جزئیات پرداخت‌ها» با اطلاعات کامل:
 *   تاریخ/ساعت، درگاه، شناسه و نام کاربر، وضعیت، مبلغ، کد تخفیف، …
 */
class Details extends Component
{
    use WithPagination;

    public string $search       = '';
    public string $statusFilter = 'all';

    protected $queryString = ['search', 'statusFilter'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }
    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $payments = Payment::query()
            ->with(['user', 'order.paymentMethod', 'order.orderItems.product', 'personalInformation'])
            ->when($this->statusFilter !== 'all', fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->search, function ($q) {
                $q->where(function ($w) {
                    $w->where('order_number', 'like', "%{$this->search}%")
                      ->orWhere('refNumber',   'like', "%{$this->search}%")
                      ->orWhere('cardNumber',  'like', "%{$this->search}%")
                      ->orWhereHas('user', fn($u) =>
                          $u->where('name', 'like', "%{$this->search}%")
                              ->orWhere('mobile', 'like', "%{$this->search}%")
                      );
                });
            })
            ->latest()
            ->paginate(20);

        // برای هر پرداخت، نزدیک‌ترین CouponUsage کاربر تا زمان پرداخت
        $userIds   = $payments->pluck('user_id')->unique()->all();
        $userCoupons = CouponUsage::with('coupon')
            ->whereIn('user_id', $userIds)
            ->orderBy('used_at')
            ->get()
            ->groupBy('user_id');

        return view('livewire.manager.payment.details', [
            'payments'    => $payments,
            'userCoupons' => $userCoupons,
        ])->layout('layouts.manager.app');
    }

    public function statusLabel(string $status): string
    {
        return match ($status) {
            'pending'   => 'در انتظار',
            'completed' => 'موفق',
            'cancelled' => 'کنسل شده',
            'failed'    => 'ناموفق',
            default     => $status,
        };
    }
}
