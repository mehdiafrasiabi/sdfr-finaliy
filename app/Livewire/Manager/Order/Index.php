<?php

namespace App\Livewire\Manager\Order;

use App\Models\Order;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;


class Index extends Component
{
    use WithPagination,SEOTools;
    public $search='';

    public function mount()
    {
        $this->seoConfig();
    }
    public function seoConfig()
    {
        $this->seo()
            ->setTitle('سفارشات');
    }
    public function changeStatus($orderId, $value)
    {
        $validator = Validator::make(['status' => $value, 'id' => $orderId],
            [
                'id' => 'required|exists:orders,id',
                'status' => 'required|in:pending,processing,completed,canceled',
            ],
            [
                '*.required' => 'فیلد اجیاری است.',
                'status.in' => 'فرمت اشتباه است',
                'id.exists' => 'سفارش نامعتبر'
            ]
        );
        $validator->validate();
        $this->resetValidation();
        $this->dispatch('success', 'با موفقیت ثبت شد');

        Order::query()->updateOrCreate(
            [
                'id' => $orderId
            ],
            [
                'status' => $value
            ]);
    }

    public function getStatusColor($status)
    {
        switch ($status) {
            case 'pending':
                return 'primary';
            case 'processing':
                return 'warning';
            case 'canceled':
                return 'danger';
            case 'completed':
                return 'success';

        }
    }


    public function render()
    {
        $orderQuery = Order::query()
            ->with('user','payment')
            ->when($this->search, function ($query) {
                $query->where('order_number', 'like','%'.$this->search.'%')
                    ->orWhereHas('user', function ($query) {
                        $query->where('name', 'like','%'.$this->search.'%')
                            ->orWhere('email', 'like','%'.$this->search.'%')
                            ->orWhere('mobile', 'like','%'.$this->search.'%');
                    });
            })->latest();

        if (isset($_GET['status']) && ($_GET['status']) !='all') {
            $orderQuery->where('status', '=',$_GET['status']);
        }
        if (isset($_GET['user']) && ($_GET['user']) !='all') {
            $orderQuery->where('user_id', '=',$_GET['user']);
        }


        $orders = $orderQuery->paginate(10);
        $orders->getCollection()->transform(function ($order) {
            $parts = explode('-', $order->order_number);
            $order->order_number = $parts[2] ?? null;

            $order->statusColor= $this->getStatusColor($order->status);
            $order->statusPaymentColor= $this->getStatusColor($order->payment->status);

            return $order;
        });

        return view('livewire.manager.order.index',
            ['orders' => $orders]
        )->layout('layouts.manager.app');
    }
}
