<?php

namespace App\Livewire\Client\Cart;

use App\Contracts\PaymentGateWayInterface;
use App\Models\Cart;
use App\Models\Coupons;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    use SEOTools;

    public $cartItems = [];
    public $invoice = [];
    public $discountCodeAmount = 0;
    public $totalAmount = 0;
    public $totalOriginalPrice = 0;
    public $showDiscountCode = false;
    public $latestProducts;
    public $cart = 0;

    public $selectedCartItemId = null;

    public function confirmDeleteItem($cartItemId)
    {
        $this->selectedCartItemId = $cartItemId;
    }

    #[On('add-to-cart')]
    public function mount()
    {
        $this->latestProducts = Product::query()
            ->select('id', 'name', 'title', 'tag', 'price', 'meeting_time', 'course_time', 'p_code')
            ->with('coverImage', 'seo')
            ->get();

        if (auth()->check()) {
            $this->cart = Cart::query()->where('user_id', auth()->id())->count();
        }

        $this->seoConfig();

        $invoice = Session::get('invoiceFromCart', []);
        $this->totalOriginalPrice = $invoice['totalOriginalPrice'] ?? 0;

        $this->totalAmountForPayment($this->totalOriginalPrice, $this->discountCodeAmount);
    }

    public function totalAmountForPayment($totalOriginalPrice, $discountCodeAmount): void
    {
        $this->totalAmount = max(0, $totalOriginalPrice - $discountCodeAmount);
    }

    public function getUserCart()
    {
        if (auth()->check()) {
            $this->cart = Cart::query()->where('user_id', auth()->id())->count();
        }
    }

    public function deleteItem()
    {
        Cart::query()->where('id', $this->selectedCartItemId)->delete();
        $this->selectedCartItemId = null;

        $this->updateCart();

        $this->dispatch('remove-from-cart', $this->cart);
        $this->dispatch('success', 'آیتم با موفقیت از سبد خرید حذف شد.');
    }
    public function seoConfig()
    {
        $this->seo()->setTitle('سبد خرید');
    }

    public function checkDiscountCode($formData): void
    {
        $validator = Validator::make($formData, [
            'code' => 'required|string|exists:coupons,code|min:4|max:15',
        ], [
            'code.required' => 'فیلد ضروری است.',
            'code.string' => 'فرمت اشتباه است !',
            'code.max' => 'حداکثر تعداد کاراکترها : 15',
            'code.min' => 'حداقل تعداد کاراکترها : 4',
            'code.exists' => 'کد تخفیف وارد شده معتبر نیست.',
        ]);
        $validator->validate();
        $this->resetValidation();

        $coupon = Coupons::query()->where('code', $formData['code'])->first();

        $this->applyDiscount($coupon);
    }

    public function applyDiscount($coupon)
    {
        $user = Auth::user();

        if (!$coupon || !$coupon->is_active || now()->gt($coupon->expires_at)) {
            session()->flash('error', 'کد تخفیف معتبر نیست یا منقضی شده.');
            return;
        }

        if ($coupon->user_id && $coupon->user_id !== $user->id) {
            session()->flash('error', 'این کد تخفیف فقط برای کاربر خاصی تعریف شده.');
            return;
        }

        $alreadyUsed = CouponUsage::where('coupon_id', $coupon->id)
            ->where('user_id', $user->id)
            ->exists();
        if ($alreadyUsed) {
            session()->flash('error', 'شما قبلاً از این کد تخفیف استفاده کرده‌اید.');
            return;
        }

        if ($this->totalOriginalPrice < $coupon->min_purchase) {
            session()->flash('error', 'حداقل خرید برای این کوپن برقرار نیست.');
            return;
        }

        // محاسبه تخفیف
        $discount = $coupon->type === 'percent'
            ? ($this->totalOriginalPrice * $coupon->value) / 100
            : $coupon->value;

        $this->discountCodeAmount = $discount;
        $this->totalAmountForPayment($this->totalOriginalPrice, $discount);
        $this->showDiscountCode = true;

        // ثبت استفاده
        CouponUsage::create([
            'coupon_id' => $coupon->id,
            'user_id' => $user->id,
        ]);

        // کم کردن limit و غیرفعالسازی
        $coupon->decrement('limit');
        if ($coupon->limit <= 0) {
            $coupon->update(['is_active' => false]);
        }

        session()->flash('success', 'کد تخفیف با موفقیت اعمال شد.');

    }

    public function updateCart()
    {
        $this->cartItems = Cart::query()
            ->with('product:id,name,title,tag,price,course_time,meeting_time,description,p_code')
            ->where('user_id', auth()->id())
            ->get();

        $this->cart = $this->cartItems->count();
        $this->totalOriginalPrice = $this->cartItems->sum(fn($item) => $item->product->price);
        $this->totalAmountForPayment($this->totalOriginalPrice, $this->discountCodeAmount);

        Session::put('invoiceFromCart', [
            'totalProductCount' => $this->cart,
            'totalOriginalPrice' => $this->totalOriginalPrice,
        ]);
    }

    public function createOrder($orderNumber, $getPaymentMethodId)
    {
        return Order::query()->create([
            'amount' => $this->totalAmount,
            'order_number' => $orderNumber,
            'user_id' => Auth::id(),
            'payment_method_id' => $getPaymentMethodId
        ]);
    }

    public function createOrderItem($cartItems, $orderId)
    {
        foreach ($cartItems as $item) {
            $product = Product::query()->where('id', $item->product_id)->first();
            OrderItem::query()->create([
                'price' => $product->price,
                'order_id' => $orderId,
                'product_id' => $item->product_id,
            ]);
        }
    }

    public function createPayment($orderId, $orderNumber)
    {
        Payment::query()->create([
            'order_id' => $orderId,
            'user_id' => Auth::id(),
            'amount' => $this->totalAmount,
            'order_number' => $orderNumber,
        ]);
    }



    public function goToOrderInfo()
    {
        session()->put('checkout', [
            'totalAmount' => $this->totalAmount,
            'totalOriginalPrice' => $this->totalOriginalPrice,
            'discountAmount' => $this->discountCodeAmount,
            'cartItems' => $this->cartItems->pluck('id')->toArray(),
        ]);

        return redirect()->route('client.checkout.cart.info');
    }

    public function render()
    {
        $this->updateCart();
        $this->cartItems = Cart::query()->with('product:id,name,title,tag,price,course_time,meeting_time,description,p_code')
            ->where('user_id', Auth::id())
            ->get()->map(function ($item) {
                $item->originalPrice = $item->product->price;
                return $item;
            });

        $this->invoice = [
            'totalProductCount' => $this->cartItems->count(),
            'totalOriginalPrice' => $this->cartItems->sum('originalPrice'),
        ];

        Session::put('invoiceFromCart', $this->invoice);

        return view('livewire.client.cart.index')->layout('layouts.client.app');
    }
}
