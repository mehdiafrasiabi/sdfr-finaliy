<?php

namespace App\Livewire\Manager\Transaction;

use App\Exports\TransActionExport;
use App\Models\Payment;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

/**
 * «سفارشات و تراکنش‌ها» (یکپارچه) — لیست همهٔ پرداخت‌ها با همهٔ وضعیت‌ها،
 * فیلتر وضعیت/نوع، جست‌وجو، و مودالِ جزئیاتِ سفارش (Order + اقلام + طرح اقساطی)
 * داخل همان صفحه. (به‌جای صفحهٔ جداگانهٔ جزئیات.)
 */
class Index extends Component
{
    use WithPagination, SEOTools;

    public string $search  = '';
    public string $status  = 'all';   // all|pending|completed|cancelled|failed
    public string $purpose = 'all';   // all|course_full|installment_initial|installment
    public ?int   $selectedId = null;

    public const STATUS_LABELS = [
        'pending'   => 'در انتظار',
        'completed' => 'پرداخت‌شده',
        'cancelled' => 'لغو شده',
        'failed'    => 'ناموفق',
    ];

    public const PURPOSE_LABELS = [
        Payment::PURPOSE_COURSE_FULL         => 'خرید نقدی دوره',
        Payment::PURPOSE_INSTALLMENT_INITIAL => 'پیش‌پرداخت اقساط',
        Payment::PURPOSE_INSTALLMENT         => 'قسط',
        Payment::PURPOSE_INSTALLMENT_BULK    => 'پرداخت گروهی اقساط',
    ];

    public function mount(): void
    {
        $this->seo()->setTitle('سفارشات و تراکنش‌ها');
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatedPurpose(): void { $this->resetPage(); }

    public function setStatus(string $status): void
    {
        $this->status = $status;
        $this->resetPage();
    }

    public function setPurpose(string $purpose): void
    {
        $this->purpose = $purpose;
        $this->resetPage();
    }

    public function showDetail(int $id): void { $this->selectedId = $id; }
    public function closeDetail(): void { $this->selectedId = null; }

    public function statusColor(?string $status): string
    {
        return match ($status) {
            'completed' => 'success',
            'pending'   => 'warning',
            'cancelled' => 'danger',
            'failed'    => 'dark',
            default     => 'secondary',
        };
    }

    public function purposeColor(?string $purpose): string
    {
        return match ($purpose) {
            Payment::PURPOSE_COURSE_FULL         => 'success',
            Payment::PURPOSE_INSTALLMENT_INITIAL => 'info',
            Payment::PURPOSE_INSTALLMENT         => 'primary',
            Payment::PURPOSE_INSTALLMENT_BULK    => 'purple',
            default                              => 'secondary',
        };
    }

    public function export()
    {
        return Excel::download(new TransActionExport($this->status), 'transactions.xlsx');
    }

    protected function baseQuery()
    {
        return Payment::query()
            ->with(['order.user', 'user'])
            ->when($this->search !== '', function ($q) {
                $term = '%' . $this->search . '%';
                $q->where(function ($qq) use ($term) {
                    $qq->where('order_number', 'like', $term)
                        ->orWhere('refNumber', 'like', $term)
                        ->orWhere('cardNumber', 'like', $term)
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $term)->orWhere('mobile', 'like', $term));
                });
            })
            ->when($this->status !== 'all', fn ($q) => $q->where('status', $this->status))
            ->when($this->purpose !== 'all', fn ($q) => $q->where('purpose', $this->purpose))
            ->latest();
    }

    public function render()
    {
        $transactions = $this->baseQuery()->paginate(12);

        $selected = $this->selectedId
            ? Payment::with(['order.orderItems', 'order.user', 'user', 'personalInformation', 'installmentPlan.installments'])
                ->find($this->selectedId)
            : null;

        return view('livewire.manager.transaction.index', [
            'transactions' => $transactions,
            'selected'     => $selected,
        ])->layout('layouts.manager.app');
    }
}
