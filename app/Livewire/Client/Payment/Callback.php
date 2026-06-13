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
    public $isWalletPurchase = false;
    public $isWalletCharge = false;
    public function mount(Request $request, PaymentGateWayInterface $paymentGateWay, PurchaseFinalizer $finalizer)
    {
        // Check if this is a wallet-only purchase (no gateway)
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
            ];
            session()->forget(['paymentSuccess', 'paymentData']);
            return;
        }
        // Handle gateway verification
        $paymentGateWay->verify($request);
        // Check if this was a wallet charge
        $isWalletCharge = session('wallet_charge');
        $walletChargeAmount = session('wallet_charge_amount');
        // Get payment data for both success and error display
        if ($request->has('orderId')) {
            $payment = Payment::query()
                ->where('order_number', $request->orderId)
                ->with('order')
                ->first();
            if ($payment) {
                // Handle wallet charge
                if ($isWalletCharge && $payment->status === 'completed') {
                    $this->processWalletCharge($walletChargeAmount);
                    $this->isWalletCharge = true;
                }
                // Handle partial wallet payment
                $pendingWalletDeduction = session('pending_wallet_deduction');
                $pendingOrderNumber = session('pending_order_number');
                if ($pendingWalletDeduction && $pendingOrderNumber === $payment->order_number && $payment->status === 'completed') {
                    $this->processPartialWalletPayment($pendingWalletDeduction, $payment);
                }
                // نهایی‌سازی خرید دوره/اقساط (به‌جز شارژ کیف پول): فعال‌سازی دسترسی،
                // طرح اقساطی یا علامت‌زدن قسط پرداخت‌شده.
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
                    'paymentMethod' => $this->isWalletCharge ? 'شارژ کیف پول' : ($payment->order?->payment_method_text ?? 'درگاه پرداخت'),
                ];
                session()->forget(['wallet_charge', 'wallet_charge_amount', 'pending_wallet_deduction', 'pending_order_number']);
            }
        }
    }
    private function processWalletCharge($amount)
    {
        $user = Auth::user();
        if ($user && $amount > 0) {
            $wallet = $user->getOrCreateWallet();
            $wallet->deposit($amount, 'شارژ کیف پول از درگاه پرداخت', 'deposit');
        }
    }
    private function processPartialWalletPayment($walletDeduction, $payment)
    {
        $user = Auth::user();
        if ($user && $walletDeduction > 0) {
            $wallet = $user->getOrCreateWallet();
            $wallet->withdraw($walletDeduction, 'خرید سفارش: ' . $payment->order_number, 'purchase');
            // Update order wallet amount
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
        // Retry payment through gateway
        return $paymentGateWay->request($payment->amount, $payment->order_number);
    }

    public function render()
    {
        return view('livewire.client.payment.callback');
    }
}
