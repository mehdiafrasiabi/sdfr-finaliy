<?php

namespace App\Livewire\Client\Cart;

use App\Contracts\PaymentGateWayInterface;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\City;
use App\Models\State;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Morilog\Jalali\Jalalian;
class Info extends Component
{
    use SEOTools;

    public $cities = [];
    public $provinces = [];
    public $city = '';
    public $province = '';
    public $name;
    public $address;
    public $nameFull;
    public $placeOfBirth;
    public $fName;
    public $codeMell;
    public $fMobile;
    public $mMobile;
    public $birth_date;

    public $grade; // پایه
    public $field; // رشته

    public $mobile;
    public $text;
    public $loadingCities = false;
    public function mount()
    {
        $this->seoConfig();
        $this->provinces = State::all();
    }
    public function updatedGrade($value)
    {
        // اگر پایه نهم انتخاب شد، رشته را خالی کن
        if ($value === '9') {
            $this->field = null;
        }
    }
    public function getCity($value)
    {
        $this->cities = City::query()->where('state_id', $value)->get();
        $this->city = '';
    }

    public function seoConfig()
    {
        $this->seo()->setTitle('احراز هویت VIP');
    }

    protected function convertJalaliToGregorian($date)
    {
        $normalizedDate = strtr(trim((string) $date), [
            '۰' => '0',
            '۱' => '1',
            '۲' => '2',
            '۳' => '3',
            '۴' => '4',
            '۵' => '5',
            '۶' => '6',
            '۷' => '7',
            '۸' => '8',
            '۹' => '9',
            '٠' => '0',
            '١' => '1',
            '٢' => '2',
            '٣' => '3',
            '٤' => '4',
            '٥' => '5',
            '٦' => '6',
            '٧' => '7',
            '٨' => '8',
            '٩' => '9',
            '-' => '/',
        ]);

        if (!$normalizedDate) {
            return null;
        }

        try {
            return Jalalian::fromFormat('Y/m/d', $normalizedDate)->toCarbon()->toDateString();
        } catch (\Throwable $e) {
            return null;
        }
    }
    public function submit($formData, PaymentGateWayInterface $paymentGateway)
    {
        // تعیین قوانین ولیدیشن رشته بر اساس پایه
        $fieldRule = $formData['grade'] === '9'
            ? 'nullable'
            : 'required|in:math,experimental,human';
        $validator = Validator::make($formData, [
            'name' => 'required|string|max:35',
            'nameFull' => 'required|string|max:35',
            'address' => 'required|string|max:200',
            'placeOfBirth' => 'required|string|max:35',
            'fName' => 'required|string|max:35',
            'codeMell' => 'required|numeric|digits:10',
            'birth_date' => 'required|string',
            'province' => 'required|exists:states,id',
            'city' => 'required|exists:cities,id',
            'fMobile' => ['required', 'regex:/^09\d{9}$/'],
            'mMobile' => ['required', 'regex:/^09\d{9}$/'],
            'grade' => 'required|in:9,10,11,12',
            'field' => $fieldRule,
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required' => 'وارد کردن نام الزامی است.',
            'nameFull.required' => 'وارد کردن نام خانوادگی الزامی است.',
            'address.required' => 'وارد کردن آدرس الزامی است.',
            'placeOfBirth.required' => 'محل تولد الزامی است.',
            'fName.required' => 'نام پدر الزامی است.',
            'codeMell.required' => 'کد ملی الزامی است.',
            'codeMell.numeric' => 'کد ملی باید عددی باشد.',
            'codeMell.digits' => 'کد ملی باید دقیقاً ۱۰ رقم باشد.',
            'birth_date.required' => 'تاریخ تولد الزامی است.',
            'province.required' => 'استان الزامی است.',
            'province.exists' => 'استان انتخابی معتبر نیست.',
            'city.required' => 'شهر الزامی است.',
            'city.exists' => 'شهر انتخابی معتبر نیست.',
            'fMobile.required' => 'شماره موبایل پدر الزامی است.',
            'fMobile.regex' => 'شماره موبایل پدر نامعتبر است.',
            'mMobile.required' => 'شماره موبایل مادر الزامی است.',
            'mMobile.regex' => 'شماره موبایل مادر نامعتبر است.',
            'grade.required' => 'انتخاب پایه الزامی است.',
            'grade.in' => 'پایه انتخابی معتبر نیست.',
            'field.required' => 'انتخاب رشته الزامی است.',
            'field.in' => 'رشته انتخابی معتبر نیست.',
            'photo.image' => 'فایل باید تصویر باشد.',
            'photo.mimes' => 'فرمت تصویر باید jpg, jpeg, png یا webp باشد.',
            'photo.max' => 'حجم تصویر نباید بیش از ۲ مگابایت باشد.',
        ]);
        $validator->validate();
        $birthDate = $this->convertJalaliToGregorian($formData['birth_date']);
        if (!$birthDate) {
            $this->addError('birth_date', 'فرمت تاریخ تولد معتبر نیست.');
            return;
        }
        // ذخیره اطلاعات هویتی
        $personalInfo = \App\Models\PersonalInformation::query()->create([
            'name' => $formData['name'],
            'name_full' => $formData['nameFull'],
            'address' => $formData['address'],
            'place_of_birth' => $formData['placeOfBirth'],
            'father_name' => $formData['fName'],
            'code_mell' => $formData['codeMell'],
            'father_mobile' => $formData['fMobile'],
            'mother_mobile' => $formData['mMobile'],
            'birth_date' => $birthDate,
            'grade' => $formData['grade'], // پایه
            'field' => $formData['field'], // رشته
            'state_id' => $formData['province'],
            'city_id' => $formData['city'],
            'user_id' => Auth::id(),
        ]);
        // ادامه فرآیند سفارش مثل قبل
        $checkout = Session::get('checkout', []);
        $totalAmount = $checkout['totalAmount'] ?? 0;
        $cartItemIds = $checkout['cartItems'] ?? [];
        $useWallet = $checkout['useWallet'] ?? false;
        $walletDeduction = $checkout['walletDeduction'] ?? 0;
        $user = auth()->user();
        $orderNumber = 'REF-' . \Illuminate\Support\Str::uuid()->toString();
        DB::beginTransaction();
        try {
            $paidWithWallet = ($totalAmount == 0 && $useWallet && $walletDeduction > 0);
            $order = Order::query()->create([
                'amount' => $checkout['totalOriginalPrice'] ?? 0,
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'payment_method_id' => 1,
                'paid_with_wallet' => $paidWithWallet,
                'wallet_amount' => $walletDeduction,
                'status' => $paidWithWallet ? 'completed' : 'pending',
            ]);
            $cartItems = Cart::query()
                ->with('product')
                ->whereIn('id', $cartItemIds)
                ->get();
            foreach ($cartItems as $item) {
                OrderItem::query()->create([
                    'price' => $item->product->price,
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                ]);
            }
            $payment = Payment::query()->create([
                'order_id' => $order->id,
                'user_id' => $user->id,
                'amount' => $checkout['totalOriginalPrice'] ?? 0,
                'order_number' => $orderNumber,
                'personal_information_id' => $personalInfo->id,
                'status' => $paidWithWallet ? 'completed' : 'pending',
            ]);
            // If paying fully with wallet
            if ($paidWithWallet) {
                $wallet = $user->getOrCreateWallet();
                $wallet->withdraw($walletDeduction, 'خرید سفارش: ' . $orderNumber, 'purchase');
                // Create student record if needed
                $this->createStudentRecord($user, $payment);
                DB::commit();
                Cart::query()->whereIn('id', $cartItemIds)->delete();
                session([
                    'paymentSuccess' => true,
                    'paymentData' => [
                        'orderNumber' => $orderNumber,
                        'amount' => $walletDeduction,
                        'date' => now(),
                        'paymentMethod' => 'کیف پول',
                    ]
                ]);
                return redirect()->route('client.payment.callback');
            }
            // If using wallet partially with gateway payment
            if ($useWallet && $walletDeduction > 0) {
                session(['pending_wallet_deduction' => $walletDeduction, 'pending_order_number' => $orderNumber]);
            }
            DB::commit();
            Cart::query()->whereIn('id', $cartItemIds)->delete();
            return $paymentGateway->request($totalAmount, $orderNumber);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            session()->flash('error', 'در فرآیند ثبت سفارش خطایی رخ داد.');
            return;
        }
    }
    private function createStudentRecord($user, $payment)
    {
        if (!$user->student) {
            \App\Models\Student::create([
                'user_id' => $user->id,
                'payment_id' => $payment->id,
            ]);

            // ارسال پیامک تبریک به دانش‌آموز همراه با لینک صفحه تعیین وقت مشاوره
            try {
                if (!empty($user->mobile)) {
                    $link = route('client.profile.appointment');
                    $user->notify(new \App\Notifications\SendAppointmentSchedulingSms(
                        $user->mobile,
                        $user->name ?: 'دانش‌آموز عزیز',
                        $link
                    ));
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send appointment scheduling SMS', [
                    'user_id' => $user->id,
                    'error'   => $e->getMessage(),
                ]);
            }
        }
    }

    public function render()
    {
        $checkout = Session::get('checkout', ['totalAmount', 'totalOriginalPrice', 'discountAmount']);
        return view('livewire.client.cart.info', [
            'checkout' => $checkout
        ])->layout('layouts.client.app');
    }
}
