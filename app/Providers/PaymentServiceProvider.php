<?php

namespace App\Providers;

use App\Contracts\PaymentGateWayInterface;
use App\Models\PaymentMethod;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(PaymentGateWayInterface::class, function () {
            $activePayment = PaymentMethod::query()->where('active', true)->first();

            if (! $activePayment) {
                throw new \Exception("هیچ درگاهی وجود ندارد");
            }

            $gateWayClass = 'App\\Services\\PaymentGateWay\\' . $activePayment->name;

            if (! class_exists($gateWayClass)) {
                throw new \Exception("درگاه پرداخت تنظیم‌شده معتبر نیست");
            }

            return new $gateWayClass;
        });
    }

}
