<?php

namespace App\Livewire\Manager\Order;

use App\Exports\OrderDetailArrayExport;
use App\Exports\OrderDetailExport;
use App\Models\Order;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Detail extends Component
{
    use SEOTools;
    public $orderDetails;
    public $statusColor;

    public function mount(Order $order)
    {
        $orderDetails = $this->orderDetails = $order->load(
            ['orderItems.product:id,name,price,p_code',
                'orderItems.product.coverImage:id,path,product_id',
                'payment',
                'paymentMethod',
                'personalInformation'
            ]
        );
        $parts = explode('-', $orderDetails->order_number);
        $orderDetails->statusPaymentColor= $this->getStatusColor($order->payment->status);
        $this->statusColor= $this->getStatusColor($orderDetails->status);

        $this->seoConfig();
    }
    public function seoConfig()
    {
        $this->seo()
            ->setTitle('جزییات سفارشات');
    }
    public function getStatusColor($status)
    {
        switch ($status) {
            case 'pending':
                return 'primary';
            case 'processing':
                return 'warning';
            case 'cancelled':
                return 'danger';
            case 'completed':
                return 'success';

        }
    }
    public function export()
    {
        $filename = $this->orderDetails->personalInformation->name . '-سفارش.xlsx';

        return Excel::download(new OrderDetailArrayExport($this->orderDetails), $filename);
    }

        public function render()
    {
        return view('livewire.manager.order.detail')->layout('layouts.manager.app');
    }
}
