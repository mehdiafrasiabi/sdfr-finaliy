<?php

namespace App\Livewire\Client\Purchase;

use App\Models\Coupons;
use App\Models\CouponUsage;
use App\Models\GradePrice;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PersonalInformation;
use App\Models\Student;
use App\Models\TrialWeek;
use App\Services\TrialWeekService;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

/**
 * B-2 — صفحهٔ پرداخت اختصاصی برای کاربری که در انتهای ثبت‌نام بین
 * «خرید دوره» و «۱ هفته آزمایشی» انتخاب می‌کند.
 *
 *   - نمایش قیمت پلکانی این ماه + ماه بعد
 *   - وارد کردن کد تخفیف
 *   - دکمهٔ «شروع آزمایشی» در بالای صفحه
 *   - دکمهٔ «پرداخت»  → ثبت Order/Payment در وضعیت pending و انتقال
 *     به مرحلهٔ callback درگاه (در همین پروژه شبیه‌سازی شده)
 */
class Index extends Component
{
    use SEOTools;

    public string $couponCode    = '';
    public ?string $couponError  = null;
    public ?string $couponNotice = null;
    public int $couponDiscount   = 0; // درصد تخفیف کوپن

    public function mount(): void
    {
        $this->seo()->setTitle('پرداخت دوره');

        // اگر کاربر قبلاً پرداخت موفق دارد یا برنامهٔ آزمایشی او ساخته شده،
        // به داشبورد منتقل می‌شود.
        $user = Auth::user();
        if ($user) {
            $hasPaid = $user->payments()->where('status', 'completed')->exists();
            if ($hasPaid) {
                $this->redirect(route('client.profile.dashboard'), navigate: true);
                return;
            }
        }
    }

    protected function gradePrice(): ?GradePrice
    {
        $user = Auth::user();
        if (! $user) {
            return null;
        }

        $pi = PersonalInformation::where('user_id', $user->id)->first();
        if (! $pi || ! $pi->grade) {
            return null;
        }

        return GradePrice::activeFor((int) $pi->grade, $pi->field ?? null);
    }

    public function applyCoupon(): void
    {
        $this->couponError  = null;
        $this->couponNotice = null;
        $this->couponDiscount = 0;

        $code = trim($this->couponCode);
        if ($code === '') {
            $this->couponError = 'کد تخفیف را وارد کنید.';
            return;
        }

        $coupon = Coupons::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (! $coupon) {
            $this->couponError = 'کد تخفیف نامعتبر است.';
            return;
        }

        // اگر کاربر قبلاً همین کوپن را استفاده کرده، اجازه استفاده مجدد نده.
        $userId = Auth::id();
        $alreadyUsed = CouponUsage::where('user_id', $userId)
            ->where('coupon_id', $coupon->id)
            ->exists();
        if ($alreadyUsed) {
            $this->couponError = 'این کد تخفیف قبلاً استفاده شده است.';
            return;
        }

        // مقدار درصد تخفیف کوپن — ستون متداول: discount_percentage یا percentage
        $percent = (int) ($coupon->discount_percentage ?? $coupon->percentage ?? 0);
        $this->couponDiscount = max(0, min(100, $percent));
        $this->couponNotice   = "تخفیف {$this->couponDiscount} درصدی روی این ماه اعمال شد.";
    }

    public function removeCoupon(): void
    {
        $this->couponCode     = '';
        $this->couponDiscount = 0;
        $this->couponError    = null;
        $this->couponNotice   = null;
    }

    /**
     * شروع هفته آزمایشی با همان اطلاعاتی که کاربر در ثبت‌نام داده.
     */
    public function startTrial(TrialWeekService $service): void
    {
        $user = Auth::user();
        if (! $user) {
            $this->redirect(route('client.auth.login'), navigate: true);
            return;
        }

        // اگر trial فعال قبلی دارد، به صفحهٔ انتظار برمی‌گردد.
        if ($user->trialWeek) {
            $this->redirect(route('client.profile.waiting-for-supporter'), navigate: true);
            return;
        }

        $pi = PersonalInformation::where('user_id', $user->id)->first();
        if (! $pi || ! $pi->grade) {
            session()->flash('error', 'ابتدا اطلاعات شخصی خود را تکمیل کنید.');
            return;
        }

        $service->start(
            $user,
            (int) $pi->grade,
            $pi->field ?: null,
            $pi->father_mobile ?? '',
            $pi->mother_mobile ?? '',
        );

        $this->redirect(route('client.profile.waiting-for-supporter'), navigate: true);
    }

    /**
     * شروع پرداخت — Order + Payment در وضعیت pending ایجاد می‌شود و کاربر
     * به مسیر callback (بخش موجود سیستم) منتقل می‌شود تا با درگاه واقعی
     * تکمیل شود.
     */
    public function pay(): void
    {
        $user = Auth::user();
        if (! $user) {
            $this->redirect(route('client.auth.login'), navigate: true);
            return;
        }

        $price = $this->gradePrice();
        if (! $price) {
            session()->flash('error', 'قیمت برای پایهٔ شما تعریف نشده است.');
            return;
        }

        $pi = PersonalInformation::where('user_id', $user->id)->first();
        if (! $pi) {
            session()->flash('error', 'ابتدا اطلاعات شخصی خود را تکمیل کنید.');
            return;
        }

        $finalPrice = $this->finalPriceForCurrentMonth($price);

        DB::transaction(function () use ($user, $pi, $finalPrice) {
            $order = Order::create([
                'user_id'     => $user->id,
                'total_price' => $finalPrice,
                'status'      => 'pending',
            ]);

            $payment = Payment::create([
                'order_id'                => $order->id,
                'user_id'                 => $user->id,
                'amount'                  => $finalPrice,
                'order_number'            => (string) $order->id,
                'personal_information_id' => $pi->id,
                'status'                  => 'pending',
            ]);

            // اگر کوپن اعمال شده، CouponUsage ثبت می‌شود.
            if ($this->couponDiscount > 0 && $this->couponCode) {
                $coupon = Coupons::where('code', trim($this->couponCode))->first();
                if ($coupon) {
                    CouponUsage::create([
                        'coupon_id' => $coupon->id,
                        'user_id'   => $user->id,
                        'used_at'   => now(),
                    ]);
                }
            }
        });

        $this->redirect(route('client.payment.callback'), navigate: true);
    }

    protected function finalPriceForCurrentMonth(GradePrice $price): int
    {
        $base = $price->effectivePrice(); // پلکانی + درصد ثابت + تخفیف روز خاص
        if ($this->couponDiscount > 0) {
            $base = (int) round($base * (1 - $this->couponDiscount / 100));
        }
        return max(0, $base);
    }

    public function render()
    {
        $price = $this->gradePrice();

        $currentSteppedPrice = $price?->current_stepped_price ?? null;
        $nextMonthPrice      = $price?->next_month_stepped_price ?? null;

        $effectiveThisMonth  = $price ? $this->finalPriceForCurrentMonth($price) : null;
        $activeDailyDiscount = $price?->activeDailyDiscount();

        // اگر کاربر هفته آزمایشی pending دارد، نوار «شما در حال انتظار هستید»
        $pendingTrial = TrialWeek::where('user_id', Auth::id())
            ->where('status', TrialWeek::STATUS_PENDING)
            ->latest()
            ->first();

        return view('livewire.client.purchase.index', [
            'price'               => $price,
            'currentSteppedPrice' => $currentSteppedPrice,
            'nextMonthPrice'      => $nextMonthPrice,
            'effectiveThisMonth'  => $effectiveThisMonth,
            'activeDailyDiscount' => $activeDailyDiscount,
            'pendingTrial'        => $pendingTrial,
        ])->layout('layouts.client.app');
    }
}
