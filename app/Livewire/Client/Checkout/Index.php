<?php

namespace App\Livewire\Client\Checkout;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Student;
use App\Services\GradePriceCalculator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    public ?array $priceInfo = null;
    public string $error = '';

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user) {
            $this->redirect(route('client.auth.login'));
            return;
        }
        $this->priceInfo = app(GradePriceCalculator::class)->priceForUser($user);
    }

    public function pay()
    {
        $user = Auth::user();
        if (!$this->priceInfo) {
            $this->error = 'قیمت برای پایه شما تعریف نشده است. لطفاً با پشتیبانی تماس بگیرید.';
            return;
        }

        // ساخت سفارش
        $order = Order::create([
            'user_id'     => $user->id,
            'amount'      => $this->priceInfo['final_price'],
            'order_number'=> 'GP-' . now()->format('YmdHis') . '-' . $user->id,
            'status'      => 'pending',
        ]);

        $payment = Payment::create([
            'order_id'     => $order->id,
            'user_id'      => $user->id,
            'amount'       => $this->priceInfo['final_price'],
            'order_number' => $order->order_number,
            'status'       => 'pending',
        ]);

        // اتصال به student (پس از callback موفق به completed تبدیل می‌شود)
        $student = Student::firstOrCreate(['user_id' => $user->id]);
        $student->update([
            'payment_id'     => $payment->id,
            'grade_price_id' => $this->priceInfo['grade_price']->id,
        ]);

        // در سیستم واقعی به درگاه redirect می‌شود؛ اینجا فعلاً به callback خام
        return redirect()->route('client.payment.callback', ['authority' => $payment->id]);
    }

    #[Layout('layouts.client.app-auth')]
    public function render()
    {
        return view('livewire.client.checkout.index');
    }
}
