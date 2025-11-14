<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderDetailArrayExport implements FromCollection, WithHeadings
{
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order->load([
            'orderItems.product:id,name,price,p_code',
            'payment',
            'paymentMethod',
            'personalInformation'
        ]);
    }

    public function collection()
    {
        $rows = [];

        foreach ($this->order->orderItems as $item) {
            $rows[] = [
                'شماره سفارش'             => $this->order->order_number,
                'مبلغ پرداختی'            => number_format($this->order->payment->amount ?? 0) . ' تومان',
                 'وضعیت پرداخت'     => match ($this->order->payment->status) {
                'pending'   => 'درحال پردازش',
                'completed' => 'پرداخت شده',
                'cancelled' => 'لغو شده',
                default     => '---',
            },
                'درگاه پرداخت'           => $this->order->paymentMethod->name ?? '---',

                'نام خریدار'              => $this->order->personalInformation->name ?? '---',
                'شماره موبایل'            => $this->order->user->mobile ?? '---',
                'کدملی'                   => $this->order->personalInformation->code_mell ?? '---',
                'نام پدر'                 => $this->order->personalInformation->father_name ?? '---',
                'شماره پدر'               => $this->order->personalInformation->father_mobile ?? '---',
                'شماره مادر'              => $this->order->personalInformation->mother_mobile ?? '---',
                'آدرس'                    => $this->order->personalInformation->address ?? '---',
                'استان'                   => $this->order->personalInformation->state->name ?? '---',
                'شهر'                     => $this->order->personalInformation->city->name ?? '---',

                'نام محصول'               => $item->product->name ?? '---',
                'کد محصول'               => $item->product->p_code ?? '---',
                'قیمت محصول'             => number_format($item->product->price ?? 0) . ' تومان',
                'تاریخ ثبت'             => jalali($this->order->created_at)->format('d M Y | h:i'),
            ];
        }

        return collect($rows);
    }

    public function headings(): array
    {
        return [
            'شماره سفارش',
            'مبلغ پرداختی',
            'وضعیت پرداخت',
            'درگاه پرداخت',

            'نام خریدار',
            'شماره موبایل',
            'کدملی',
            'نام پدر',
            'شماره پدر',
            'شماره مادر',
            'آدرس',
            'استان',
            'شهر',

            'نام محصول',
            'کد محصول',
            'قیمت محصول',
            'تاریخ سفارش',
        ];
    }
}
