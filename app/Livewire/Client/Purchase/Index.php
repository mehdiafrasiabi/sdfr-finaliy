<?php

namespace App\Livewire\Client\Purchase;

use App\Contracts\PaymentGateWayInterface;
use App\Models\Coupons;
use App\Models\CouponUsage;
use App\Models\City;
use App\Models\GradePrice;
use App\Models\Installment;
use App\Models\InstallmentPlan;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PersonalInformation;
use App\Models\State;
use App\Models\TrialWeek;
use App\Services\TrialWeekService;
use Artesaos\SEOTools\Traits\SEOTools;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

class Index extends Component
{
    use SEOTools;

    public string  $couponCode     = '';
    public ?string $couponError    = null;
    public ?string $couponNotice   = null;
    public int     $couponDiscount = 0;

    public int  $step          = 1;
    public bool $agreedToTerms = false;
    public bool $showTrialConfirmModal = false;

    public bool   $editingInfo     = false;
    public string $originalInfoGrade = '';
    public string $infoName         = '';
    public string $infoNameFull     = '';
    public string $infoFatherName   = '';
    public string $infoCodeMell     = '';
    public string $infoGrade        = '';
    public string $infoField        = '';
    public string $infoBirthDate    = '';
    public string $infoFatherMobile = '';
    public string $infoMotherMobile = '';
    public string $infoPlaceOfBirth = '';
    public string $infoAddress      = '';
    public string $infoStateId      = '';
    public string $infoCityId       = '';

    public const GRADE_OPTIONS = ['9' => 'نهم', '10' => 'دهم', '11' => 'یازدهم', '12' => 'دوازدهم'];
    public const FIELD_OPTIONS = ['math' => 'ریاضی و فیزیک', 'experimental' => 'علوم تجربی', 'human' => 'علوم انسانی'];

    public function mount(): void
    {
        $this->seo()->setTitle('پرداخت دوره');

        $user = Auth::user();
        if (! $user) return;

        if ($user->student && $user->student->hasActivePaidAccess()) {
            $this->redirect(route('client.profile.dashboard'), navigate: true);
            return;
        }

        $this->loadInfo();
    }

    public function nextStep(): void
    {
        if ($this->step === 1 && ! $this->gradePrice()) {
            session()->flash('error', 'قیمتی برای پایهٔ شما تعریف نشده است.');
            return;
        }

        // بررسی اجباری بودن آدرس و محل تولد در مرحله دوم
        if ($this->step === 2) {
            if ($this->editingInfo) {
                $this->dispatch('warning', 'ابتدا ویرایش اطلاعات را ذخیره یا لغو کنید.');
                return;
            }

            if ($this->purchaseInfoNeedsCompletion()) {
                $this->editingInfo = true;
                $this->resetErrorBag();
                $this->dispatch('warning', 'برای ورود به مرحله بعد، تکمیل «استان»، «شهر»، «آدرس» و «محل تولد» الزامی است. همین حالا اطلاعات را کامل کنید.');
                return;
            }
        }

        if ($this->step < 3) {
            $this->step++;
            $this->dispatchStepChanged();
        }
    }

    public function prevStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
            $this->dispatchStepChanged();
        }
        $this->editingInfo = false;
    }

    public function openTrialConfirm(): void
    {
        $this->showTrialConfirmModal = true;
    }

    public function closeTrialConfirm(): void
    {
        $this->showTrialConfirmModal = false;
    }

    public function startTrialWeek(TrialWeekService $service)
    {
        $user = Auth::user();
        if (! $user) {
            return $this->redirect(route('client.auth.login'), navigate: true);
        }

        $existingTrial = TrialWeek::where('user_id', $user->id)->latest()->first();
        if ($existingTrial) {
            $this->showTrialConfirmModal = false;
            return $this->redirect($this->trialRedirectRouteFor($existingTrial), navigate: true);
        }

        $pi = PersonalInformation::where('user_id', $user->id)->first();
        if (! $pi) {
            $this->showTrialConfirmModal = false;
            $this->dispatch('error', 'برای شروع یک هفته آزمایشی، اطلاعات کاربری شما باید کامل باشد.');
            return;
        }

        if (
            blank($pi->grade)
            || blank($pi->father_mobile)
            || blank($pi->mother_mobile)
        ) {
            $this->showTrialConfirmModal = false;
            $this->dispatch('warning', 'برای شروع یک هفته آزمایشی، پایه و شماره موبایل پدر و مادر باید کامل باشد.');
            return;
        }

        $grade = $pi->is_graduate ? TrialWeek::GRADE_GRADUATE : (int) $pi->grade;

        $service->start(
            $user,
            $grade,
            $pi->field,
            (string) $pi->father_mobile,
            (string) $pi->mother_mobile,
            (bool) $pi->attends_school,
        );

        $this->showTrialConfirmModal = false;

        return $this->redirect(route('client.profile.waiting-for-supporter'), navigate: true);
    }

    protected function loadInfo(): void
    {
        $pi = PersonalInformation::where('user_id', Auth::id())->first();
        if (! $pi) return;

        $this->infoName         = (string) $pi->name;
        $this->infoNameFull     = (string) ($pi->name_full ?? '');
        $this->infoFatherName   = (string) $pi->father_name;
        $this->infoCodeMell     = (string) $pi->code_mell;
        $this->infoGrade        = (string) $pi->grade;
        $this->infoField        = (string) $pi->field;
        $this->infoBirthDate    = (string) $pi->birth_date;
        $this->infoFatherMobile = (string) $pi->father_mobile;
        $this->infoMotherMobile = (string) $pi->mother_mobile;
        $this->infoPlaceOfBirth = (string) $pi->place_of_birth;
        $this->infoAddress      = (string) $pi->address;
        $this->infoStateId      = (string) ($pi->state_id ?? '');
        $this->infoCityId       = (string) ($pi->city_id ?? '');
        $this->originalInfoGrade = (string) ($pi->grade ?? '');
    }

    public function updatedInfoStateId($value): void
    {
        $this->infoCityId = '';
    }

    public function updatedInfoGrade($value): void
    {
        $this->couponNotice = null;
        $this->couponError = null;

        if (
            $this->originalInfoGrade !== ''
            && (string) $value !== ''
            && (string) $value !== $this->originalInfoGrade
        ) {
            $this->dispatch('warning', 'اگر پایه‌ات را تغییر بدهی، ممکن است مبلغ نهایی خرید هم تغییر کند.');
        }

        if (! $this->gradeRequiresField($value)) {
            $this->infoField = '';
        }
    }

    public function startEditInfo(): void
    {
        $this->editingInfo = true;
        $this->resetErrorBag();
    }

    public function editInfoField(string $field): void
    {
        $allowedFields = [
            'infoName',
            'infoNameFull',
            'infoFatherName',
            'infoCodeMell',
            'infoGrade',
            'infoField',
            'infoBirthDate',
            'infoPlaceOfBirth',
            'infoFatherMobile',
            'infoMotherMobile',
            'infoStateId',
            'infoCityId',
            'infoAddress',
        ];

        if (! in_array($field, $allowedFields, true)) {
            return;
        }

        $this->editingInfo = true;
        $this->resetErrorBag();
        $this->dispatch('purchase-focus-field', field: $field);
    }

    public function cancelEditInfo(): void
    {
        $this->editingInfo = false;
        $this->loadInfo();
        $this->resetErrorBag();
    }

    public function saveInfo(): void
    {
        $this->persistInfo();
    }

    public function saveInfoAndContinue(): void
    {
        if (! $this->persistInfo(advanceToPayment: true)) {
            return;
        }

        if (! $this->gradePrice()) {
            $this->dispatch('warning', 'برای پایهٔ انتخابی شما هنوز قیمت فعالی ثبت نشده است.');
            return;
        }

        $this->step = 3;
        $this->dispatchStepChanged();
    }

    protected function gradePrice(): ?GradePrice
    {
        $user = Auth::user();
        if (! $user) return null;

        if ($this->infoGrade !== '' && in_array($this->infoGrade, array_keys(self::GRADE_OPTIONS), true)) {
            return GradePrice::activeFor((int) $this->infoGrade);
        }

        $pi = PersonalInformation::where('user_id', $user->id)->first();
        if (! $pi || ! $pi->grade) return null;

        return GradePrice::activeFor((int) $pi->grade);
    }

    protected function gradeRequiresField(string|int|null $grade): bool
    {
        return (string) $grade !== '9';
    }

    protected function gradePriceForPersonalInfo(PersonalInformation $pi): ?GradePrice
    {
        if (! $pi->grade || ! in_array((string) $pi->grade, array_keys(self::GRADE_OPTIONS), true)) {
            return null;
        }

        return GradePrice::activeFor((int) $pi->grade);
    }

    public function applyCoupon(): void
    {
        $this->couponError    = null;
        $this->couponNotice   = null;
        $this->couponDiscount = 0;

        $code = trim($this->couponCode);
        if ($code === '') {
            $this->couponError = 'کد تخفیف را وارد کنید.';
            return;
        }

        $coupon = Coupons::where('code', $code)->where('is_active', true)->first();
        if (! $coupon) {
            $this->couponError = 'کد تخفیف نامعتبر است.';
            return;
        }

        $alreadyUsed = CouponUsage::where('user_id', Auth::id())
            ->where('coupon_id', $coupon->id)
            ->exists();
        if ($alreadyUsed) {
            $this->couponError = 'این کد تخفیف قبلاً استفاده شده است.';
            return;
        }

        $percent = (int) ($coupon->discount_percentage ?? $coupon->percentage ?? 0);
        $this->couponDiscount = max(0, min(100, $percent));
        $this->couponNotice = "تخفیف {$this->couponDiscount} درصدی روی پرداخت نقدی اعمال شد.";
    }

    public function removeCoupon(): void
    {
        $this->couponCode     = '';
        $this->couponDiscount = 0;
        $this->couponError    = null;
        $this->couponNotice   = null;
    }

    public function pay(PaymentGateWayInterface $paymentGateway)
    {
        $user = Auth::user();
        if (! $user) return $this->redirect(route('client.auth.login'), navigate: true);

        if ($user->student && $user->student->hasActivePaidAccess()) {
            return $this->redirect(route('client.profile.dashboard'), navigate: true);
        }

        if (! $this->agreedToTerms) {
            $this->dispatch('error', 'برای پرداخت باید قوانین و شرایط را بپذیرید.');
            return;
        }

        $pi    = PersonalInformation::where('user_id', $user->id)->first();
        $price = $pi ? $this->gradePriceForPersonalInfo($pi) : null;
        if (! $price || ! $pi) {
            $this->dispatch('error', 'قیمتی برای پایهٔ شما تعریف نشده یا اطلاعات شخصی کامل نیست.');
            return;
        }

        if (! $this->ensureAccessEndsAtKhordad($price)) {
            return;
        }

        if (! $this->ensureProfileReadyForPurchase($pi)) {
            return;
        }

        $i      = $price->entryMonthIndex();
        $amount = $this->finalFullPrice($price, $i);
        if ($amount <= 0) {
            $this->dispatch('error', 'مبلغ نهایی نامعتبر است.');
            return;
        }

        $this->cancelStalePendingFullPayments($user->id, $pi->id, $amount);

        $existingPendingPayment = Payment::query()
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('purpose', Payment::PURPOSE_COURSE_FULL)
            ->where('personal_information_id', $pi->id)
            ->where('amount', $amount)
            ->latest('id')
            ->first();

        if ($existingPendingPayment) {
            return $this->requestGateway(
                $paymentGateway,
                $existingPendingPayment->amount,
                $existingPendingPayment->order_number,
                'پرداخت قبلی پیدا شد اما اتصال به درگاه انجام نشد. لطفاً چند لحظه دیگر دوباره تلاش کنید.'
            );
        }

        $orderNumber = 'SDFR-' . Str::uuid()->toString();

        try {
            $paymentMethodId = $paymentGateway->getPaymentMethodId() ?: 1;

            DB::transaction(function () use ($user, $pi, $amount, $orderNumber, $paymentMethodId) {
                $order = Order::query()->create([
                    'amount'            => $amount,
                    'order_number'      => $orderNumber,
                    'user_id'           => $user->id,
                    'payment_method_id' => $paymentMethodId,
                    'paid_with_wallet'  => false,
                    'wallet_amount'     => 0,
                    'status'            => 'pending',
                ]);

                OrderItem::query()->create(['price' => $amount, 'order_id' => $order->id]);

                Payment::query()->create([
                    'order_id'                => $order->id,
                    'user_id'                 => $user->id,
                    'amount'                  => $amount,
                    'order_number'            => $orderNumber,
                    'personal_information_id' => $pi->id,
                    'status'                  => 'pending',
                    'purpose'                 => Payment::PURPOSE_COURSE_FULL,
                ]);

                $this->logCouponUsage($user->id);
            });
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('error', $this->friendlyPurchaseError('خطا در ثبت سفارش.', $e));
            return;
        }

        return $this->requestGateway(
            $paymentGateway,
            $amount,
            $orderNumber,
            'سفارش ثبت شد اما اتصال به درگاه انجام نشد. دوباره روی پرداخت نقدی بزنید تا همان پرداخت ادامه پیدا کند.'
        );
    }

    public function payInstallment(PaymentGateWayInterface $paymentGateway)
    {
        $user = Auth::user();
        if (! $user) return $this->redirect(route('client.auth.login'), navigate: true);

        if ($user->student && $user->student->hasActivePaidAccess()) {
            return $this->redirect(route('client.profile.dashboard'), navigate: true);
        }

        if (! $this->agreedToTerms) {
            $this->dispatch('error', 'برای پرداخت باید قوانین و شرایط را بپذیرید.');
            return;
        }

        $pi    = PersonalInformation::where('user_id', $user->id)->first();
        $price = $pi ? $this->gradePriceForPersonalInfo($pi) : null;
        if (! $price || ! $pi) {
            $this->dispatch('error', 'قیمتی برای پایهٔ شما تعریف نشده یا اطلاعات شخصی کامل نیست.');
            return;
        }

        if (! $this->ensureAccessEndsAtKhordad($price)) {
            return;
        }

        if (! $this->ensureProfileReadyForPurchase($pi)) {
            return;
        }

        if (! GradePrice::installmentRegistrationOpen()) {
            $this->dispatch('error', 'پرداخت اقساطی از ۱ فروردین تا ۳۱ خرداد فعال نیست؛ لطفاً پرداخت نقدی را انتخاب کنید.');
            return;
        }

        $i     = $price->entryMonthIndex();
        $count = $price->installmentCount($i);
        if ($count < 1) {
            $this->dispatch('error', 'برای این ماه امکان پرداخت اقساطی وجود ندارد؛ لطفاً پرداخت نقدی را انتخاب کنید.');
            return;
        }

        $total    = $price->totalFor($i);
        $initial  = $price->initialPayment($i);
        $monthly  = $price->installmentAmount($i);

        $existingPlan = $this->existingIncompleteInstallmentPlan($user->id);
        if ($existingPlan) {
            if (in_array($existingPlan->status, [InstallmentPlan::STATUS_ACTIVE, InstallmentPlan::STATUS_DEFAULTED], true)) {
                $this->dispatch('warning', 'شما یک طرح اقساطی فعال دارید. برای ادامه یا پرداخت قسط، وارد صفحه اقساط شوید.');
                $this->redirect(route('client.profile.installment'), navigate: true);
                return;
            }

            if ($this->pendingInstallmentPlanMatches($existingPlan, $price, $pi, $i, $count, $total, $initial, $monthly)) {
                $pendingInitialPayment = Payment::query()
                    ->where('user_id', $user->id)
                    ->where('status', 'pending')
                    ->where('purpose', Payment::PURPOSE_INSTALLMENT_INITIAL)
                    ->where('installment_plan_id', $existingPlan->id)
                    ->where('amount', $initial)
                    ->latest('id')
                    ->first();

                if ($pendingInitialPayment) {
                    return $this->requestGateway(
                        $paymentGateway,
                        $pendingInitialPayment->amount,
                        $pendingInitialPayment->order_number,
                        'پیش‌پرداخت قبلی پیدا شد اما اتصال به درگاه انجام نشد. لطفاً چند لحظه دیگر دوباره تلاش کنید.'
                    );
                }
            }

            $this->cancelPendingInstallmentPlan($existingPlan);
        }

        $purchase = Carbon::now();
        $orderNumber = 'SDFR-' . Str::uuid()->toString();

        try {
            $paymentMethodId = $paymentGateway->getPaymentMethodId() ?: 1;

            DB::transaction(function () use ($user, $pi, $price, $i, $count, $total, $initial, $monthly, $purchase, $orderNumber, $paymentMethodId) {
                $plan = InstallmentPlan::query()->create([
                    'user_id'           => $user->id,
                    'student_id'        => $user->student?->id,
                    'grade_price_id'    => $price->id,
                    'grade'             => (int) $pi->grade,
                    'entry_month_index' => $i,
                    'purchase_date'     => $purchase->toDateString(),
                    'total_amount'      => $total,
                    'initial_amount'    => $initial,
                    'installment_count' => $count,
                    'monthly_amount'    => $monthly,
                    'access_ends_at'    => $price->accessEndsAt(),
                    'status'            => InstallmentPlan::STATUS_PENDING,
                ]);

                $accumulated = 0;
                for ($k = 1; $k <= $count; $k++) {
                    $amount = ($k === $count) ? ($total - $initial - $accumulated) : $monthly;
                    $accumulated += $amount;

                    Installment::create([
                        'installment_plan_id' => $plan->id,
                        'sequence'            => $k,
                        'due_date'            => $this->installmentDueDate($purchase, $i, $k)->toDateString(),
                        'amount'              => max(0, $amount),
                        'status'              => Installment::STATUS_PENDING,
                    ]);
                }

                $order = Order::query()->create([
                    'amount'            => $initial,
                    'order_number'      => $orderNumber,
                    'user_id'           => $user->id,
                    'payment_method_id' => $paymentMethodId,
                    'paid_with_wallet'  => false,
                    'wallet_amount'     => 0,
                    'status'            => 'pending',
                ]);

                OrderItem::query()->create(['price' => $initial, 'order_id' => $order->id]);

                Payment::query()->create([
                    'order_id'                => $order->id,
                    'user_id'                 => $user->id,
                    'amount'                  => $initial,
                    'order_number'            => $orderNumber,
                    'personal_information_id' => $pi->id,
                    'status'                  => 'pending',
                    'purpose'                 => Payment::PURPOSE_INSTALLMENT_INITIAL,
                    'installment_plan_id'     => $plan->id,
                ]);
            });
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('error', $this->friendlyPurchaseError('خطا در ثبت طرح اقساطی.', $e));
            return;
        }

        return $this->requestGateway(
            $paymentGateway,
            $initial,
            $orderNumber,
            'طرح اقساطی ثبت شد اما اتصال به درگاه انجام نشد. دوباره روی پرداخت اقساطی بزنید تا همان پیش‌پرداخت ادامه پیدا کند.'
        );
    }

    protected function existingIncompleteInstallmentPlan(int $userId): ?InstallmentPlan
    {
        return InstallmentPlan::query()
            ->where('user_id', $userId)
            ->whereIn('status', [InstallmentPlan::STATUS_PENDING, InstallmentPlan::STATUS_ACTIVE, InstallmentPlan::STATUS_DEFAULTED])
            ->latest('id')
            ->first();
    }

    protected function pendingInstallmentPlanMatches(
        InstallmentPlan $plan,
        GradePrice $price,
        PersonalInformation $pi,
        int $entryMonthIndex,
        int $count,
        int $total,
        int $initial,
        int $monthly
    ): bool {
        return $plan->status === InstallmentPlan::STATUS_PENDING
            && (int) $plan->grade_price_id === (int) $price->id
            && (int) $plan->grade === (int) $pi->grade
            && (int) $plan->entry_month_index === $entryMonthIndex
            && (int) $plan->total_amount === $total
            && (int) $plan->initial_amount === $initial
            && (int) $plan->installment_count === $count
            && (int) $plan->monthly_amount === $monthly;
    }

    protected function cancelStalePendingFullPayments(int $userId, int $personalInformationId, int $currentAmount): void
    {
        Payment::query()
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->where('purpose', Payment::PURPOSE_COURSE_FULL)
            ->where(function ($query) use ($personalInformationId, $currentAmount) {
                $query->where('personal_information_id', '!=', $personalInformationId)
                    ->orWhere('amount', '!=', $currentAmount);
            })
            ->with('order')
            ->get()
            ->each(function (Payment $payment) {
                $payment->update(['status' => 'cancelled']);
                $payment->order?->update(['status' => 'canceled']);
            });
    }

    protected function cancelPendingInstallmentPlan(InstallmentPlan $plan): void
    {
        if ($plan->status !== InstallmentPlan::STATUS_PENDING) {
            return;
        }

        DB::transaction(function () use ($plan) {
            $plan->installments()->where('status', Installment::STATUS_PENDING)->delete();
            $plan->update(['status' => InstallmentPlan::STATUS_CANCELLED]);

            Payment::query()
                ->where('installment_plan_id', $plan->id)
                ->where('status', 'pending')
                ->where('purpose', Payment::PURPOSE_INSTALLMENT_INITIAL)
                ->with('order')
                ->get()
                ->each(function (Payment $payment) {
                    $payment->update(['status' => 'cancelled']);
                    $payment->order?->update(['status' => 'canceled']);
                });
        });
    }

    protected function requestGateway(PaymentGateWayInterface $paymentGateway, int $amount, string $orderNumber, string $fallback): mixed
    {
        try {
            return $paymentGateway->request($amount, $orderNumber);
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('error', $this->friendlyPurchaseError($fallback, $e));
            return null;
        }
    }

    protected function installmentDueDate(Carbon $purchase, int $entryMonthIndex, int $sequence): Carbon
    {
        $purchaseJalali = Jalalian::fromCarbon($purchase);
        $dueMonth = GradePrice::persianMonthForIndex($entryMonthIndex + $sequence);

        return Jalalian::fromFormat(
            'Y/m/d',
            sprintf('%04d/%02d/20', (int) $purchaseJalali->getYear(), $dueMonth)
        )->toCarbon()->startOfDay();
    }

    protected function finalFullPrice(GradePrice $price, int $i): int
    {
        $base = $price->totalFor($i);
        if ($this->couponDiscount > 0) {
            $base = (int) round($base * (1 - $this->couponDiscount / 100));
        }
        return max(0, $base);
    }

    protected function logCouponUsage(int $userId): void
    {
        if ($this->couponDiscount > 0 && $this->couponCode) {
            $coupon = Coupons::where('code', trim($this->couponCode))->first();
            if ($coupon) {
                CouponUsage::create([
                    'coupon_id' => $coupon->id,
                    'user_id'   => $userId,
                    'used_at'   => now(),
                ]);
            }
        }
    }

    protected function ensureProfileReadyForPurchase(?PersonalInformation $pi): bool
    {
        if (! $pi) {
            $this->dispatch('error', 'اطلاعات شخصی شما یافت نشد.');
            return false;
        }

        if (
            blank(trim((string) $pi->address))
            || blank(trim((string) $pi->place_of_birth))
            || blank($pi->state_id)
            || blank($pi->city_id)
        ) {
            $this->dispatch('warning', 'برای ادامه خرید، تکمیل «استان»، «شهر»، «آدرس» و «محل تولد» الزامی است.');
            $this->step = 2;
            return false;
        }

        return true;
    }

    protected function purchaseInfoNeedsCompletion(): bool
    {
        return blank(trim($this->infoAddress))
            || blank(trim($this->infoPlaceOfBirth))
            || blank($this->infoStateId)
            || blank($this->infoCityId);
    }

    protected function ensureAccessEndsAtKhordad(GradePrice $price): bool
    {
        if ($this->priceAccessEndsAtKhordad($price)) {
            return true;
        }

        $accessEnd = $price->accessEndsAt();
        $label = $accessEnd
            ? Jalalian::fromCarbon($accessEnd)->format('Y/m/d')
            : 'نامشخص';

        $this->dispatch('error', "تاریخ پایان دسترسی این پایه درست ثبت نشده است. تاریخ فعلی: {$label}. قبل از پرداخت باید تا پایان خرداد تنظیم شود.");

        return false;
    }

    protected function priceAccessEndsAtKhordad(GradePrice $price): bool
    {
        $accessEnd = $price->accessEndsAt();
        if (! $accessEnd) {
            return false;
        }

        $jalali = Jalalian::fromCarbon($accessEnd);

        return (int) $jalali->getMonth() === 3 && (int) $jalali->getDay() === 31;
    }

    protected function dispatchStepChanged(): void
    {
        $this->dispatch('purchase-step-changed');
    }

    protected function trialRedirectRouteFor(TrialWeek $trialWeek): string
    {
        return $trialWeek->status === TrialWeek::STATUS_PENDING
            ? route('client.profile.waiting-for-supporter')
            : route('client.profile.trial.guide');
    }

    protected function persistInfo(bool $advanceToPayment = false): bool
    {
        $pi = PersonalInformation::where('user_id', Auth::id())->first();
        if (! $pi) {
            session()->flash('error', 'اطلاعات شخصی یافت نشد.');
            return false;
        }

        $fieldRules = $this->gradeRequiresField($this->infoGrade)
            ? ['required', Rule::in(array_keys(self::FIELD_OPTIONS))]
            : ['nullable'];

        $this->validate([
            'infoName'         => ['required', 'string', 'max:255'],
            'infoNameFull'     => ['nullable', 'string', 'max:255'],
            'infoFatherName'   => ['required', 'string', 'max:255'],
            'infoCodeMell'     => ['required', 'string', 'max:20', Rule::unique('personal_information', 'code_mell')->ignore($pi->id)],
            'infoGrade'        => ['required', Rule::in(array_keys(self::GRADE_OPTIONS))],
            'infoField'        => $fieldRules,
            'infoBirthDate'    => ['nullable', 'string', 'max:30'],
            'infoFatherMobile' => ['required', 'string', 'max:20'],
            'infoMotherMobile' => ['required', 'string', 'max:20'],
            'infoPlaceOfBirth' => ['required', 'string', 'max:255'],
            'infoAddress'      => ['required', 'string', 'max:500'],
            'infoStateId'      => ['required', 'integer', 'exists:states,id'],
            'infoCityId'       => ['required', 'integer', Rule::exists('cities', 'id')->where(fn ($query) => $query->where('state_id', $this->infoStateId))],
        ], [], [
            'infoName'         => 'نام',
            'infoFatherName'   => 'نام پدر',
            'infoCodeMell'     => 'کد ملی',
            'infoGrade'        => 'پایه',
            'infoField'        => 'رشته',
            'infoBirthDate'    => 'تاریخ تولد',
            'infoFatherMobile' => 'موبایل پدر',
            'infoMotherMobile' => 'موبایل مادر',
            'infoPlaceOfBirth' => 'محل تولد',
            'infoAddress'      => 'آدرس',
            'infoStateId'      => 'استان',
            'infoCityId'       => 'شهر',
        ]);

        $pi->update([
            'name'           => $this->infoName,
            'name_full'      => $this->infoNameFull ?: null,
            'father_name'    => $this->infoFatherName,
            'code_mell'      => $this->infoCodeMell,
            'grade'          => $this->infoGrade,
            'field'          => $this->gradeRequiresField($this->infoGrade) ? $this->infoField : null,
            'birth_date'     => $this->infoBirthDate,
            'father_mobile'  => $this->infoFatherMobile,
            'mother_mobile'  => $this->infoMotherMobile,
            'place_of_birth' => $this->infoPlaceOfBirth,
            'address'        => $this->infoAddress,
            'state_id'       => $this->infoStateId,
            'city_id'        => $this->infoCityId,
        ]);

        $this->editingInfo = false;
        $this->loadInfo();
        $this->dispatch('success', $advanceToPayment
            ? 'اطلاعات با موفقیت ذخیره شد.'
            : 'اطلاعات با موفقیت به‌روزرسانی شد.');

        return true;
    }

    protected function friendlyPurchaseError(string $fallback, \Throwable $e): string
    {
        $message = trim((string) $e->getMessage());

        if ($message === '') {
            return $fallback;
        }

        if (str_contains($message, 'هیچ درگاهی وجود ندارد')) {
            return 'درگاه پرداخت فعال نیست. لطفاً با پشتیبانی تماس بگیرید.';
        }

        if (app()->hasDebugModeEnabled()) {
            return $fallback . ' ' . $message;
        }

        return $fallback;
    }

    public function render()
    {
        $price = $this->gradePrice();
        $pi = PersonalInformation::with(['state', 'city'])->where('user_id', Auth::id())->first();
        $selectedGradeRequiresField = $this->gradeRequiresField($this->infoGrade);
        $selectedFieldValue = $selectedGradeRequiresField ? (string) $this->infoField : '';
        $storedFieldValue = $pi && $this->gradeRequiresField((string) $pi->grade) ? (string) ($pi->field ?? '') : '';
        $profileSelectionChanged = $pi && (
            (string) $this->infoGrade !== (string) ($pi->grade ?? '')
            || $selectedFieldValue !== $storedFieldValue
        );
        $requiresInfoCompletion = $this->purchaseInfoNeedsCompletion();

        $data = null;
        if ($price) {
            $i = $price->entryMonthIndex();
            $data = [
                'index'             => $i,
                'month_label'       => GradePrice::monthLabel($i),
                'discount'          => $price->discountFor($i),
                'effective_rate'    => $price->effectiveRate($i),
                'remaining_months'  => $price->remainingMonths($i),
                'original_total'    => $price->originalTotalFor($i),
                'total'             => $price->totalFor($i),
                'savings'           => max(0, $price->originalTotalFor($i) - $price->totalFor($i)),
                'full_with_coupon'  => $this->finalFullPrice($price, $i),
                'initial'           => $price->initialPayment($i),
                'installment_count' => $price->installmentCount($i),
                'installment_open'  => GradePrice::installmentRegistrationOpen(),
                'monthly'           => $price->installmentAmount($i),
                'installment_until_label' => ($price->installmentCount($i) > 0 && GradePrice::installmentRegistrationOpen())
                    ? Jalalian::fromCarbon($this->installmentDueDate(Carbon::now(), $i, $price->installmentCount($i)))->format('Y/m/d')
                    : null,
                'access_ends_label' => $price->accessEndsAt()
                    ? Jalalian::fromCarbon($price->accessEndsAt())->format('Y/m/d')
                    : '—',
                'access_ends_is_khordad' => $this->priceAccessEndsAtKhordad($price),
            ];
        }

        $states = State::query()->select('id', 'name')->orderBy('name')->get();
        $cities = $this->infoStateId
            ? City::query()->where('state_id', $this->infoStateId)->select('id', 'name')->orderBy('name')->get()
            : collect();

        return view('livewire.client.purchase.index', [
            'price'        => $price,
            'data'         => $data,
            'pi'           => $pi,
            'gradeOptions' => self::GRADE_OPTIONS,
            'fieldOptions' => self::FIELD_OPTIONS,
            'selectedGradeRequiresField' => $selectedGradeRequiresField,
            'profileSelectionChanged' => $profileSelectionChanged,
            'requiresInfoCompletion' => $requiresInfoCompletion,
            'gradeSelectOptions' => collect(self::GRADE_OPTIONS)->map(fn ($label, $id) => ['id' => $id, 'name' => $label])->values(),
            'fieldSelectOptions' => collect(self::FIELD_OPTIONS)->map(fn ($label, $id) => ['id' => $id, 'name' => $label])->values(),
            'states'       => $states,
            'cities'       => $cities,
        ])->layout('layouts.client.app');
    }
}
