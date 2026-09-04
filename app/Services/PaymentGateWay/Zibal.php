<?php

namespace App\Services\PaymentGateWay;

use App\Contracts\PaymentGateWayInterface;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Payments;
use App\Models\Student;

class Zibal implements PaymentGateWayInterface
{
    public function request($amount,$orderNumber)
    {
        $zibal = new \App\classes\zibal();
        $parameters = array(
            "merchant"=> config('services.zibal.merchant'),//required
            "callbackUrl"=> route('client.payment.callback'),//required
            "amount"=> $amount*10,//required

            "orderId"=> $orderNumber,//optional
            "mobile"=> "09120000000",//optional for mpg
        );

        $response = $zibal->postToZibal('request', $parameters);
        if ($response->result == 100)
        {
            $startGateWayUrl = "https://gateway.zibal.ir/start/".$response->trackId;
            return redirect($startGateWayUrl);
        }
        else{
            throw new \Exception('Error :'.$response->message. ' -Message :' . $response->message );
        }
    }
    public function verify($request)
    {
        session()->forget(['paymentSuccess','paymentError']);
        $payment = Payment::query()->where('order_number', $request->orderId)->firstOrFail();
        $zibal = new \App\classes\zibal();

        if($_GET['success']==1) {
            //start verfication
            $parameters = array(
                "merchant" => config('services.zibal.merchant'),//required
                "trackId" => $_GET['trackId'],//required

            );

            $response = $zibal->postToZibal('verify', $parameters);

            if ($response->result == 100) {

                $this->updatePayment($payment,$request);
                $this->updateOrder($payment);

                $refNumber = $response->refNumber ?? 'نامشخض';
                session()->flash('paymentSuccess',"$refNumber پرداخت با موفقیت انجام شد،شناسه پرداخت:");
            } else {
                session()->flash('paymentError',"تراکنش نامعتبر ");

            }
        }else{
            session()->flash('paymentError'," پرداخت با شکست مواجه شد ");
        }
    }
    public function getPaymentMethodId()
    {
        return PaymentMethod::query()->where('name','=','Zibal')->pluck('id')->first();
    }

    public function updatePayment($payment,$request)
    {
        // اگر کاربر قبلاً (مثلاً از طریق هفته آزمایشی) Student داشته، همان رکورد
        // را به دانش‌آموز پرداختی ارتقا می‌دهیم. در غیر این صورت رکورد جدید
        // ساخته می‌شود.
        $student = Student::query()->firstOrNew(['user_id' => $payment->user_id]);
        $student->payment_id = $payment->id;
        $student->is_trial   = false;
        $student->save();

        $payment->update(
            [
                'status'=>'completed',
                'refNumber'=>$request->refNumber,
                'cardNumber'=>$request->cardNumber,
            ]
        );
    }
    public function updateOrder($payment)
    {
        $payment->order()->update(
            [
                'status'=>'processing',
            ]
        );
    }
}
