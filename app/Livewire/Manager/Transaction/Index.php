<?php

namespace App\Livewire\Manager\Transaction;

use App\Exports\TransActionExport;
use App\Models\Payment;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithPagination,SEOTools;
    public $search = '';
    public $status =[];

    public function mount()
    {
        $this->seoConfig();
    }
    public function seoConfig()
    {
        $this->seo()
            ->setTitle('تراکنش ها');
    }
    public function getTransactionWithFilters($search = null, $status = null)
    {
        $query = Payment::query()
            ->with('order')
            ->latest();

        if ($search) {
            $query->where('order_number', 'like', '%' . $search . '%')
                ->orWhere('refNumber', 'like', '%' . $search . '%')
                ->orWhere('cardNumber', 'like', '%' . $search . '%');
        }


        if ($status && $status != 'all') {
            $query->where('status', '=', $status);
        }

        return $query->latest();

    }

    public function transformQuery($transactions)
    {
        return $transactions->getCollection()->transform(function ($item) {

            $item->statusPaymentColor = $this->getStatusColor($item->status);

            return $item;
        });

    }

    public function getStatusColor($status)
    {
        switch ($status) {
            case 'pending':
                return 'primary';
            case 'processing':
                return 'info';
            case 'completed':
                return 'success';
            case 'cancelled':
                return 'danger';
        }
    }
    public function export()
    {
        $status = $_GET['status'] ?? 'all';
        return Excel::download(new TransActionExport($status), 'transactions.xlsx');
    }
    public function render()
    {

        $ordersQuery = $this->getTransactionWithFilters($this->search, $_GET['status'] ?? 'all');
        $transactions = $ordersQuery->paginate(10);
        $transactions->getCollection()->transform(function ($item) {
            $parts = explode('-', $item->order_number);
            $item->order_number = $parts[5] ?? null;
            return $item;
        });
        $this->transformQuery($transactions);
        return view('livewire.manager.transaction.index',['transactions'=>$transactions])->layout('layouts.manager.app');
    }
}
