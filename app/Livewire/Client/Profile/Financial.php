<?php

namespace App\Livewire\Client\Profile;

use App\Models\Payment;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Financial extends Component
{
    use SEOTools, WithPagination;

    public function mount()
    {
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()
            ->setTitle('مالی و پرداخت');
    }

    public array $expandedPayments = [];

    public function togglePaymentDetails(int $paymentId): void
    {
        if (in_array($paymentId, $this->expandedPayments)) {
            $this->expandedPayments = array_values(array_diff($this->expandedPayments, [$paymentId]));
            return;
        }

        $this->expandedPayments[] = $paymentId;
    }

    /**
     * Get a descriptive title for the payment.
     *
     * @param Payment $payment
     * @return string
     */
    public function getPaymentDescription(Payment $payment): string
    {
        if ($payment->payable_type === 'App\\Models\\Installment') {
            return 'پرداخت قسط';
        }

        if ($payment->order && $payment->order->orderItems->isNotEmpty()) {
            $firstItem = $payment->order->orderItems->first();
            $orderableType = $payment->order->orderable_type;

            if ($orderableType === 'App\\Models\\InstallmentPlan' || str_contains($firstItem->name ?? '', 'پیش پرداخت')) {
                return 'پیش پرداخت طرح اقساطی';
            }

            return($firstItem->name ?? 'خدمات');
        }

        return 'تراکنش مالی';
    }


    public function render()
    {
        $payments = Auth::user()
            ->payments()
            ->with(['order.orderItems', 'user.student']) // Eager load necessary relationships
            ->latest()
            ->paginate(10);

        $plan    = Auth::user()->student?->activeInstallmentPlan();
        $current = $plan?->currentDue();

        return view('livewire.client.profile.financial', [
            'payments' => $payments,
            'plan'     => $plan,
            'current'  => $current,
        ])->layout('layouts.client.app');
    }
}
