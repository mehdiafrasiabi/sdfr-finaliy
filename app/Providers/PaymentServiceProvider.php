<?php

namespace App\Providers;

use App\Contracts\PaymentGateWayInterface;
use App\Services\PaymentGateWay\Zarinpal;
use App\Services\PaymentGateWay\Zibal;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PaymentGateWayInterface::class, function () {
            $name = config('services.payment.default', 'zibal');

            return match ($name) {
                'zarinpal' => new Zarinpal(),
                'zibal'    => new Zibal(),
                default    => throw new \Exception("Unknown payment gateway: {$name}"),
            };
        });
    }
}
