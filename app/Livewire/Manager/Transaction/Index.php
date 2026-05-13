<?php

namespace App\Livewire\Manager\Transaction;

use App\Models\EnrollmentPayment;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, SEOTools;

    public $search = '';
    public $status = [];

    public function mount()
    {
        $this->seo()->setTitle('تراکنش ها');
    }

    public function getTransactionWithFilters($search = null, $status = null)
    {
        $query = EnrollmentPayment::query()
            ->with('enrollment.user')
            ->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('authority', 'like', '%' . $search . '%')
                    ->orWhere('ref_number', 'like', '%' . $search . '%');
            });
        }

        if ($status && $status !== 'all') {
            $query->where('status', '=', $status);
        }

        return $query;
    }

    protected function getStatusColor($status): string
    {
        return match ($status) {
            'pending' => 'primary',
            'success' => 'success',
            'failed' => 'danger',
            default => 'info',
        };
    }

    public function render()
    {
        $query = $this->getTransactionWithFilters($this->search, $_GET['status'] ?? 'all');
        $transactions = $query->paginate(15);

        $transactions->getCollection()->transform(function ($item) {
            $item->statusPaymentColor = $this->getStatusColor($item->status);
            $item->refNumber = $item->ref_number;
            $item->cardNumber = '-';
            $item->order_number = $item->authority;
            return $item;
        });

        return view('livewire.manager.transaction.index', [
            'transactions' => $transactions,
        ])->layout('layouts.manager.app');
    }
}
