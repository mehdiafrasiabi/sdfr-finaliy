<?php

namespace App\Livewire\Client\Payment;

use App\Contracts\PaymentGateWayInterface;
use App\Models\Payment;
use App\Services\PurchaseFinalizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Callback extends Component
{
    public $paymentData;
    public bool $isWalletPurchase = false;
    public bool $isWalletCharge = false;
    public bool $isSuccessful = false;
    public string $paymentPurpose = '';
    public string $statusSubtitle = '';
    public string $infoMessage = '';
    public string $primaryActionLabel = '';
    public string $primaryActionUrl = '';
    public bool $primaryActionIsRetry = false;
    public string $secondaryActionLabel = '';
    public string $secondaryActionUrl = '';

    public function mount(Request $request, PaymentGateWayInterface $paymentGateWay, PurchaseFinalizer $finalizer): void
    {
        if (session('paymentSuccess')) {
            $sessionData = session('paymentData');
            $this->isWalletPurchase = true;
            $this->paymentData = [
                'order_number' => $sessionData['orderNumber'] ?? '',
                'amount' => $sessionData['amount'] ?? 0,
                'created_at' => $sessionData['date'] ?? now(),
                'updated_at' => $sessionData['date'] ?? now(),
                'paymentMethod' => $sessionData['paymentMethod'] ?? 'کیف پول',
                'status' => 'completed',
                'purpose' => $sessionData['purpose'] ?? Payment::PURPOSE_COURSE_FULL,
            ];

            session()->forget(['paymentSuccess', 'paymentData']);
            $this->configurePresentation();
            return;
        }

        $paymentGateWay->verify($request);

        $isWalletCharge = session('wallet_charge');
        $walletChargeAmount = session('wallet_charge_amount');

        if ($request->has('orderId')) {
            $payment = Payment::query()
                ->where('order_number', $request->orderId)
                ->with('order')
                ->first();

            if ($payment) {
                if ($isWalletCharge && $payment->status === 'completed') {
                    $this->processWalletCharge($walletChargeAmount);
                    $this->isWalletCharge = true;
                }

                $pendingWalletDeduction = session('pending_wallet_deduction');
                $pendingOrderNumber = session('pending_order_number');

                if ($pendingWalletDeduction && $pendingOrderNumber === $payment->order_number && $payment->status === 'completed') {
                    $this->processPartialWalletPayment($pendingWalletDeduction, $payment);
                }

                if (! $isWalletCharge && $payment->status === 'completed') {
                    $finalizer->finalize($payment);
                }

                $this->paymentData = [
                    'order_number' => $payment->order_number,
                    'amount' => $payment->amount,
                    'created_at' => $payment->created_at,
                    'updated_at' => $payment->updated_at,
                    'order_id' => $payment->order_id,
                    'payment_id' => $payment->id,
                    'refNumber' => $payment->refNumber,
                    'status' => $payment->status,
                    'purpose' => $payment->purpose ?: Payment::PURPOSE_COURSE_FULL,
                    'paymentMethod' => $this->isWalletCharge ? 'شارژ کیف پول' : ($payment->order?->payment_method_text ?? 'درگاه پرداخت'),
                ];
            }
        }

        session()->forget(['wallet_charge', 'wallet_charge_amount', 'pending_wallet_deduction', 'pending_order_number']);
        $this->configurePresentation();
    }

    private function processWalletCharge($amount): void
    {
        $user = Auth::user();
        if ($user && $amount > 0) {
            $wallet = $user->getOrCreateWallet();
            $wallet->deposit($amount, 'شارژ کیف پول از درگاه پرداخت', 'deposit');
        }
    }

    private function processPartialWalletPayment($walletDeduction, $payment): void
    {
        $user = Auth::user();
        if ($user && $walletDeduction > 0) {
            $wallet = $user->getOrCreateWallet();
            $wallet->withdraw($walletDeduction, 'خرید سفارش: ' . $payment->order_number, 'purchase');

            if ($payment->order) {
                $payment->order->update(['wallet_amount' => $walletDeduction]);
            }
        }
    }

    public function retryPayment($orderId, PaymentGateWayInterface $paymentGateWay)
    {
        $payment = Payment::query()
            ->where('id', $orderId)
            ->where('status', 'pending')
            ->firstOrFail();

        return $paymentGateWay->request($payment->amount, $payment->order_number);
    }

    protected function configurePresentation(): void
    {
        $this->isSuccessful = ($this->paymentData['status'] ?? null) === 'completed';
        $this->paymentPurpose = (string) ($this->paymentData['purpose'] ?? Payment::PURPOSE_COURSE_FULL);

        if (! $this->paymentData && ! session('paymentError')) {
            return;
        }

        if ($this->isSuccessful) {
            $this->configureSuccessPresentation();
            return;
        }

        $this->configureErrorPresentation();
    }

    protected function configureSuccessPresentation(): void
    {
        if ($this->isWalletCharge) {
            $this->statusSubtitle = 'شارژ کیف پول شما با موفقیت ثبت شد';
            $this->infoMessage = 'موجودی کیف پول به‌روزرسانی شد و جزئیات آن را از بخش امور مالی می‌توانید ببینید.';
            $this->setLinkActions('ورود به امور مالی', route('client.profile.financial'), 'ورود به داشبورد', route('client.profile.dashboard'));
            return;
        }

        if (in_array($this->paymentPurpose, [Payment::PURPOSE_INSTALLMENT, Payment::PURPOSE_INSTALLMENT_BULK], true)) {
            $this->statusSubtitle = $this->paymentPurpose === Payment::PURPOSE_INSTALLMENT_BULK
                ? 'اقساط انتخابی شما با موفقیت پرداخت شد'
                : 'قسط شما با موفقیت پرداخت شد';
            $this->infoMessage = 'وضعیت اقساط و سوابق پرداخت از بخش امور مالی در دسترس است.';
            $this->setLinkActions('ورود به امور مالی', route('client.profile.financial'), 'ورود به داشبورد', route('client.profile.dashboard'));
            return;
        }

        $this->statusSubtitle = 'خرید شما با موفقیت ثبت شد';
        $this->infoMessage = 'اکنون می‌توانید مشاور خود را انتخاب کنید یا به صفحه اصلی برگردید.';
        $this->setLinkActions('انتخاب مشاور', route('client.profile.appointment'), 'ورود به صفحه اصلی', route('client.home'));
    }

    protected function configureErrorPresentation(): void
    {
        $this->statusSubtitle = 'متاسفانه پرداخت شما تکمیل نشد';
        $this->infoMessage = 'می‌توانید دوباره پرداخت را انجام دهید یا برای بررسی وضعیت تراکنش وارد بخش امور مالی شوید.';
        $this->primaryActionLabel = 'پرداخت مجدد';
        $this->primaryActionIsRetry = isset($this->paymentData['payment_id']);
        $this->primaryActionUrl = $this->primaryActionIsRetry ? '' : route('client.purchase');
        $this->secondaryActionLabel = 'ورود به امور مالی';
        $this->secondaryActionUrl = route('client.profile.financial');
    }

    protected function setLinkActions(string $primaryLabel, string $primaryUrl, string $secondaryLabel, string $secondaryUrl): void
    {
        $this->primaryActionLabel = $primaryLabel;
        $this->primaryActionUrl = $primaryUrl;
        $this->primaryActionIsRetry = false;
        $this->secondaryActionLabel = $secondaryLabel;
        $this->secondaryActionUrl = $secondaryUrl;
    }

    public function render()
    {
        return view('livewire.client.payment.callback');
    }
}
