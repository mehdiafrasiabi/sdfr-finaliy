<?php

namespace App\Livewire\Client\Payment;

use App\Contracts\PaymentGateWayInterface;
use App\Models\Payment;
use Illuminate\Http\Request;
use Livewire\Component;

class Callback extends Component

{

    public $paymentData;


    public function mount(Request $request, PaymentGateWayInterface $paymentGateWay)
    {
        $paymentGateWay->verify($request);
        // Get payment data for both success and error display
        if ($request->has('orderId')) {
            $payment = Payment::query()
                ->where('order_number', $request->orderId)
                ->with('order')
                ->first();
            if ($payment) {
                $this->paymentData = [
                    'order_number' => $payment->order_number,
                    'amount' => $payment->amount,
                    'created_at' => $payment->created_at,
                    'updated_at' => $payment->updated_at,
                    'order_id' => $payment->order_id,
                    'payment_id' => $payment->id,
                    'refNumber' => $payment->refNumber,
                    'status' => $payment->status,
                ];

            }

        }


    }

    public function retryPayment($orderId, PaymentGateWayInterface $paymentGateWay)

    {

        $payment = Payment::query()
            ->where('id', $orderId)
            ->where('status', 'pending')
            ->firstOrFail();


        // Retry payment through gateway

        return $paymentGateWay->request($payment->amount, $payment->order_number);

    }

    public function render()
    {
        return view('livewire.client.payment.callback');
    }
}
