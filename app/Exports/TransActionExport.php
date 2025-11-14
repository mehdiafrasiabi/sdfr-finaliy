<?php

namespace App\Exports;

use App\Models\Payment;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransActionExport implements FromCollection, WithHeadings
{
    protected $status;

    public function __construct($search = null, $status = null)
    {
        $this->status = $status;
    }

    public function collection(): Collection
    {
        $query = Payment::query()->with('order.user')->latest();

        if ($this->status && $this->status != 'all') {
            $query->where('status', $this->status);
        }

        return $query->get()->map(function ($item) {
            return [
                'شماره سفارش'      => $item->order_number,
                'نام و نام خانوادگی' => $item->order->user->name ?? '---',
                'شماره موبایل'     => $item->order->user->mobile ?? '---',
                'وضعیت پرداخت'     => match ($item->status) {
                    'pending'   => 'درحال پردازش',
                    'completed' => 'پرداخت شده',
                    'cancelled' => 'لغو شده',
                    default     => '---',
                },
                'مبلغ پرداختی'     => number_format($item->amount) . ' تومان',
                'شماره مرجع'       => $item->refNumber,
                'شماره کارت پرداخت' => $item->cardNumber,
                'تاریخ ثبت'        => verta($item->created_at)->format('Y/m/d - H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'شماره سفارش',
            'نام و نام خانوادگی',
            'شماره موبایل',
            'وضعیت پرداخت',
            'مبلغ پرداختی',
            'شماره مرجع',
            'شماره کارت پرداخت',
            'تاریخ ثبت',
        ];
    }
}
