<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->usePublicPath(base_path('public_html'));
    }



    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('fa');

        // بعد از بوت‌شدنِ همه‌ی پرووایدرها (از جمله Livewire) اجرا می‌شود
        $this->app->booted(function () {
            $this->disableLivewireMultipleRootDetector();
        });
    }

    /**
     * دتکتورِ «چند المنتِ ریشه»‌ی Livewire فقط در حالت debug اجرا می‌شود و روی
     * کامپوننت‌های بزرگ (مثل صفحه‌ی برنامه‌ی هفتگی) گاهی هنگام پارسِ HTML با
     * DOMDocument به خطای «Attempt to read property childNodes on null» کرش می‌کند.
     * این متد فقط همان یک لیسنر را از EventBusِ Livewire حذف می‌کند تا صفحه کرش نکند.
     * هیچ فایلی از vendor دستکاری نمی‌شود و در production (که debug خاموش است) بی‌اثر است.
     */
    private function disableLivewireMultipleRootDetector(): void
    {
        try {
            if (! class_exists(\Livewire\EventBus::class) || ! app()->bound(\Livewire\EventBus::class)) {
                return;
            }

            $bus = app(\Livewire\EventBus::class);
            $ref = new \ReflectionObject($bus);
            if (! $ref->hasProperty('listeners')) return;

            $prop = $ref->getProperty('listeners');
            $prop->setAccessible(true);

            $listeners = $prop->getValue($bus);
            if (empty($listeners['mount']) || ! is_array($listeners['mount'])) return;

            foreach ($listeners['mount'] as $i => $cb) {
                if (! $cb instanceof \Closure) continue;
                $scope = (new \ReflectionFunction($cb))->getClosureScopeClass();
                if ($scope && str_contains($scope->getName(), 'SupportMultipleRootElementDetection')) {
                    unset($listeners['mount'][$i]);
                }
            }

            $prop->setValue($bus, $listeners);
        } catch (\Throwable $e) {
            // اگر ساختار داخلی Livewire تغییر کرد، بی‌سروصدا نادیده بگیر (هرگز اپ را نشکن)
        }
    }
}
