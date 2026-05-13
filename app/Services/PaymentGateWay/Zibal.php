<?php

namespace App\Services\PaymentGateWay;

use App\Contracts\PaymentGateWayInterface;
use App\classes\zibal as ZibalClient;

class Zibal implements PaymentGateWayInterface
{
    public function request(int $amount, string $callbackUrl, ?string $description = null)
    {
        $zibal = new ZibalClient();
        $params = [
            'merchant'    => config('services.zibal.merchant'),
            'callbackUrl' => $callbackUrl,
            'amount'      => $amount * 10, // ریال
            'description' => $description,
        ];

        $response = $zibal->postToZibal('request', $params);

        if (isset($response->result) && $response->result == 100) {
            return redirect('https://gateway.zibal.ir/start/' . $response->trackId);
        }

        throw new \Exception('خطا در اتصال به درگاه زیبال: ' . ($response->message ?? 'unknown'));
    }

    public function verify($request): array
    {
        $success = ($request->input('success') == 1);

        if (! $success) {
            return ['success' => false, 'ref_number' => null, 'authority' => $request->input('trackId'), 'message' => 'پرداخت لغو شد'];
        }

        $zibal = new ZibalClient();
        $response = $zibal->postToZibal('verify', [
            'merchant' => config('services.zibal.merchant'),
            'trackId'  => $request->input('trackId'),
        ]);

        if (isset($response->result) && $response->result == 100) {
            return [
                'success'    => true,
                'ref_number' => $response->refNumber ?? null,
                'authority'  => $request->input('trackId'),
                'message'    => 'پرداخت موفق',
            ];
        }

        return ['success' => false, 'ref_number' => null, 'authority' => $request->input('trackId'), 'message' => 'تایید پرداخت ناموفق'];
    }

    public function name(): string
    {
        return 'zibal';
    }
}
