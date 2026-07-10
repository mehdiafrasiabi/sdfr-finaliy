<?php

namespace App\Services\PaymentGateWay;

use App\Contracts\PaymentGateWayInterface;

class Zarinpal implements PaymentGateWayInterface
{
    public function request($amount, $orderNumber)
    {

    }
    public function verify($request)
    {

    }
    public function getPaymentMethodId()
    {

    }
}
