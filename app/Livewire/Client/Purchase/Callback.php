<?php

namespace App\Livewire\Client\Purchase;

use App\Contracts\PaymentGateWayInterface;
use App\Models\Enrollment;
use App\Models\EnrollmentPayment;
use App\Models\WalletCharge;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Callback extends Component
{
    use SEOTools;

    public bool $success = false;
    public ?string $message = null;
    public ?string $refNumber = null;
    public ?string $redirectTo = null;

    public function mount(PaymentGateWayInterface $gateway)
    {
        $this->seo()->setTitle('بازگشت از درگاه');

        $intent = session('payment_intent');

        if (! $intent || ! isset($intent['kind'], $intent['id'])) {
            $this->success = false;
            $this->message = 'اطلاعات پرداخت یافت نشد.';
            return;
        }

        $result = $gateway->verify(request());
        $this->success = (bool) $result['success'];
        $this->message = $result['message'] ?? null;
        $this->refNumber = $result['ref_number'] ?? null;

        session()->forget('payment_intent');

        if ($intent['kind'] === 'enrollment') {
            $this->handleEnrollment((int) $intent['id'], $result);
        } elseif ($intent['kind'] === 'wallet_charge') {
            $this->handleWalletCharge((int) $intent['id'], $result);
        }
    }

    protected function handleEnrollment(int $paymentId, array $result): void
    {
        $payment = EnrollmentPayment::with('enrollment')->find($paymentId);
        if (! $payment) {
            $this->success = false;
            $this->message = 'سفارش یافت نشد.';
            return;
        }

        DB::transaction(function () use ($payment, $result) {
            if ($result['success']) {
                $payment->update([
                    'status'     => EnrollmentPayment::STATUS_SUCCESS,
                    'ref_number' => $result['ref_number'] ?? null,
                    'paid_at'    => now(),
                ]);
                $payment->enrollment?->update([
                    'status'  => Enrollment::STATUS_PAID,
                    'paid_at' => now(),
                ]);
            } else {
                $payment->update([
                    'status' => EnrollmentPayment::STATUS_FAILED,
                ]);
            }
        });

        if ($result['success']) {
            $this->redirectTo = route('client.profile.waiting-for-supporter');
        }
    }

    protected function handleWalletCharge(int $chargeId, array $result): void
    {
        $charge = WalletCharge::find($chargeId);
        if (! $charge) {
            $this->success = false;
            $this->message = 'تراکنش کیف پول یافت نشد.';
            return;
        }

        if ($result['success']) {
            DB::transaction(function () use ($charge, $result) {
                $charge->update([
                    'status'     => WalletCharge::STATUS_SUCCESS,
                    'ref_number' => $result['ref_number'] ?? null,
                    'paid_at'    => now(),
                ]);

                $wallet = Auth::user()->getOrCreateWallet();
                $wallet->deposit($charge->amount, 'شارژ از درگاه - ' . ($result['ref_number'] ?? ''), 'deposit');
            });
            $this->redirectTo = route('client.profile.wallet');
        } else {
            $charge->update(['status' => WalletCharge::STATUS_FAILED]);
        }
    }

    public function render()
    {
        return view('livewire.client.purchase.callback')->layout('layouts.client.app');
    }
}
