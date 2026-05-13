<?php

namespace App\Services\PaymentGateWay;

use App\Contracts\PaymentGateWayInterface;
use Illuminate\Support\Facades\Http;

class Zarinpal implements PaymentGateWayInterface
{
    protected function baseUrl(): string
    {
        return config('services.zarinpal.sandbox')
            ? 'https://sandbox.zarinpal.com/pg/v4/payment'
            : 'https://payment.zarinpal.com/pg/v4/payment';
    }

    protected function gatewayBase(): string
    {
        return config('services.zarinpal.sandbox')
            ? 'https://sandbox.zarinpal.com/pg/StartPay/'
            : 'https://payment.zarinpal.com/pg/StartPay/';
    }

    public function request(int $amount, string $callbackUrl, ?string $description = null)
    {
        $response = Http::asJson()->post($this->baseUrl() . '/request.json', [
            'merchant_id'  => config('services.zarinpal.merchant_id'),
            'amount'       => $amount * 10, // ریال
            'callback_url' => $callbackUrl,
            'description'  => $description ?? 'پرداخت',
        ]);

        $body = $response->json();

        if (! empty($body['data']['code']) && $body['data']['code'] == 100) {
            return redirect($this->gatewayBase() . $body['data']['authority']);
        }

        throw new \Exception('خطا در اتصال به درگاه زرین‌پال: ' . ($body['errors']['message'] ?? 'unknown'));
    }

    public function verify($request): array
    {
        $authority = $request->input('Authority');
        $status    = $request->input('Status');

        if ($status !== 'OK') {
            return ['success' => false, 'ref_number' => null, 'authority' => $authority, 'message' => 'پرداخت لغو شد'];
        }

        $amount = (int) $request->session()->get('zarinpal_amount', 0);
        if ($amount === 0) {
            return ['success' => false, 'ref_number' => null, 'authority' => $authority, 'message' => 'مبلغ معتبر نیست'];
        }

        $response = Http::asJson()->post($this->baseUrl() . '/verify.json', [
            'merchant_id' => config('services.zarinpal.merchant_id'),
            'amount'      => $amount * 10,
            'authority'   => $authority,
        ]);

        $body = $response->json();

        if (! empty($body['data']['code']) && in_array($body['data']['code'], [100, 101])) {
            return [
                'success'    => true,
                'ref_number' => $body['data']['ref_id'] ?? null,
                'authority'  => $authority,
                'message'    => 'پرداخت موفق',
            ];
        }

        return ['success' => false, 'ref_number' => null, 'authority' => $authority, 'message' => 'تایید ناموفق'];
    }

    public function name(): string
    {
        return 'zarinpal';
    }
}
