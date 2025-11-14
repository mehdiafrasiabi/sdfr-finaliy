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

class Info extends Component
{
    use SEOTools;

    public $cities = [];
    public $provinces = [];
    public $city = '';
    public $province = '';
    public $name;
    public $address;
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

    public function mount()
    {
        $this->seoConfig();
        $this->provinces = State::all();
    }

    public function getCity($value)
    {
        $this->cities = City::query()->where('state_id', $value)->get();
    }

    public function seoConfig()
    {
        $this->seo()->setTitle('احراز هویت VIP');
    }

    function convertPersianToEnglish($string)
    {
        $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $english = ['0','1','2','3','4','5','6','7','8','9'];
        return str_replace($persian, $english, $string);
    }

    public function submit($formData, PaymentGateWayInterface $paymentGateway)
    {
        $birthDateRaw = $this->convertPersianToEnglish($formData['birth_date']);
        $birthDate = \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', $birthDateRaw)->toCarbon();

        $validator = Validator::make($formData, [
            'name'         => 'required|string|max:35',
            'address'      => 'required|string|max:200',
            'placeOfBirth' => 'required|string|max:35',
            'fName'        => 'required|string|max:35',
            'codeMell'     => 'required|numeric|digits:10',
            'birth_date'   => 'required',
            'province'     => 'required|exists:states,id',
            'city'         => 'required|exists:cities,id',
            'fMobile'      => ['required','regex:/^09\d{9}$/'],
            'mMobile'      => ['required','regex:/^09\d{9}$/'],
            'grade'        => 'required|in:10,11,12',
            'field'        => 'required|in:math,experimental,human',
            'photo'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required'         => 'وارد کردن نام الزامی است.',
            'address.required'      => 'وارد کردن آدرس الزامی است.',
            'placeOfBirth.required' => 'محل تولد الزامی است.',
            'fName.required'        => 'نام پدر الزامی است.',
            'codeMell.required'     => 'کد ملی الزامی است.',
            'codeMell.numeric'      => 'کد ملی باید عددی باشد.',
            'codeMell.digits'       => 'کد ملی باید دقیقاً ۱۰ رقم باشد.',
            'birth_date.required'   => 'تاریخ تولد الزامی است.',
            'province.required'     => 'استان الزامی است.',
            'province.exists'       => 'استان انتخابی معتبر نیست.',
            'city.required'         => 'شهر الزامی است.',
            'city.exists'           => 'شهر انتخابی معتبر نیست.',
            'fMobile.required'      => 'شماره موبایل پدر الزامی است.',
            'fMobile.regex'         => 'شماره موبایل پدر نامعتبر است.',
            'mMobile.required'      => 'شماره موبایل مادر الزامی است.',
            'mMobile.regex'         => 'شماره موبایل مادر نامعتبر است.',
            'grade.required'        => 'انتخاب پایه الزامی است.',
            'grade.in'              => 'پایه انتخابی معتبر نیست.',
            'field.required'        => 'انتخاب رشته الزامی است.',
            'field.in'              => 'رشته انتخابی معتبر نیست.',
            'photo.image'           => 'فایل باید تصویر باشد.',
            'photo.mimes'           => 'فرمت تصویر باید jpg, jpeg, png یا webp باشد.',
            'photo.max'             => 'حجم تصویر نباید بیش از ۲ مگابایت باشد.',
        ]);
        $validator->validate();

        // ذخیره اطلاعات هویتی
        $personalInfo = \App\Models\PersonalInformation::query()->create([
            'name'          => $formData['name'],
            'address'       => $formData['address'],
            'place_of_birth'=> $formData['placeOfBirth'],
            'father_name'   => $formData['fName'],
            'code_mell'     => $formData['codeMell'],
            'father_mobile' => $formData['fMobile'],
            'mother_mobile' => $formData['mMobile'],
            'birth_date'    => $birthDate,
            'grade'         => $formData['grade'], // پایه
            'field'         => $formData['field'], // رشته
            'state_id'      => $formData['province'],
            'city_id'       => $formData['city'],
            'user_id'       => Auth::id(),
        ]);

        // ادامه فرآیند سفارش مثل قبل
        $checkout = Session::get('checkout', []);
        $totalAmount = $checkout['totalAmount'] ?? 0;
        $cartItemIds = $checkout['cartItems'] ?? [];

        $user = auth()->user();
        $orderNumber = 'REF-' . \Illuminate\Support\Str::uuid()->toString();

        DB::beginTransaction();
        try {
            $order = Order::query()->create([
                'amount'           => $totalAmount,
                'order_number'     => $orderNumber,
                'user_id'          => $user->id,
                'payment_method_id'=> 1
            ]);

            $cartItems = Cart::query()
                ->with('product')
                ->whereIn('id', $cartItemIds)
                ->get();

            foreach ($cartItems as $item) {
                OrderItem::query()->create([
                    'price'      => $item->product->price,
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                ]);
            }

            Payment::query()->create([
                'order_id'               => $order->id,
                'user_id'                => $user->id,
                'amount'                 => $totalAmount,
                'order_number'           => $orderNumber,
                'personal_information_id'=> $personalInfo->id
            ]);

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

    public function render()
    {
        $checkout = Session::get('checkout', ['totalAmount','totalOriginalPrice','discountAmount']);
        return view('livewire.client.cart.info', [
            'checkout' => $checkout
        ])->layout('layouts.client.app');
    }
}
