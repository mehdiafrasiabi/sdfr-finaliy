<?php


namespace App\Livewire\Manager\GiftCode;


use App\Models\GiftCode;

use App\Models\User;

use Artesaos\SEOTools\Traits\SEOTools;

use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\Validator;

use Livewire\Component;

use Livewire\WithPagination;


class Index extends Component

{

    use WithPagination, SEOTools;


    public $giftcode_id, $code, $type = 'for_all', $user_id = null;

    public $usage_limit = 1, $amount, $expires_at;

    public $is_active = true, $search = '', $userSearch = '';


    public function mount()

    {

        $this->seoConfig();

    }


    public function seoConfig()

    {

        $this->seo()->setTitle('کد هدیه');

    }


    public function submit($formData)

    {

        $formData['user_id'] = !empty($formData['user_id']) ? (int)$formData['user_id'] : null;


        $rules = [

            'code' => 'required|string|max:50|unique:gift_codes,code,' . $this->giftcode_id,

            'type' => 'required|in:for_all,for_one',

            'amount' => 'required|integer|min:1000',

            'expires_at' => 'required|date|after:today',

            'is_active' => 'required|boolean',

        ];


        if ($formData['type'] === 'for_one') {

            $rules['user_id'] = 'required|exists:users,id';

        } else {

            $rules['usage_limit'] = 'required|integer|min:1';

        }


        $validator = Validator::make($formData, $rules, [

            'code.required' => 'وارد کردن کد هدیه الزامی است.',

            'code.max' => 'حداکثر کارکتر: 50',

            'code.string' => 'کد هدیه باید متن باشد.',

            'code.unique' => 'این کد قبلا ثبت شده است.',

            'type.required' => 'نوع هدیه الزامی است.',

            'type.in' => 'نوع هدیه باید برای همه یا یک نفر باشد.',

            'amount.required' => 'میزان هدیه الزامی است.',

            'amount.integer' => 'میزان هدیه باید عدد باشد.',

            'amount.min' => 'حداقل میزان هدیه ۱,۰۰۰ تومان است.',

            'usage_limit.required' => 'تعداد استفاده الزامی است.',

            'usage_limit.integer' => 'تعداد استفاده باید عدد باشد.',

            'usage_limit.min' => 'تعداد استفاده حداقل ۱ است.',

            'expires_at.required' => 'تاریخ انقضا الزامی است.',

            'expires_at.date' => 'فرمت تاریخ معتبر نیست.',

            'expires_at.after' => 'تاریخ انقضا باید بعد از امروز باشد.',

            'is_active.required' => 'وضعیت فعال بودن الزامی است.',

            'user_id.required' => 'انتخاب کاربر الزامی است.',

            'user_id.exists' => 'کاربر انتخابی معتبر نیست.',

        ]);


        $validator->validate();


        $data = [

            'code' => $formData['code'],

            'type' => $formData['type'],

            'amount' => $formData['amount'],

            'expires_at' => Carbon::parse($formData['expires_at']),

            'is_active' => $formData['is_active'],

        ];


        if ($formData['type'] === 'for_one') {

            $data['user_id'] = $formData['user_id'];

            $data['usage_limit'] = 1;

        } else {

            $data['user_id'] = null;

            $data['usage_limit'] = $formData['usage_limit'] ?? 1;

        }


        GiftCode::updateOrCreate(

            ['id' => $this->giftcode_id],

            $data

        );


        $this->dispatch('success', 'کد هدیه با موفقیت ذخیره شد.');

        $this->reset(['giftcode_id', 'code', 'type', 'user_id', 'usage_limit', 'amount', 'expires_at', 'is_active', 'userSearch']);

        $this->type = 'for_all';

        $this->is_active = true;

        $this->usage_limit = 1;

        $this->resetPage();

    }


    public function edit($id)

    {

        $giftCode = GiftCode::findOrFail($id);

        $this->giftcode_id = $giftCode->id;

        $this->code = $giftCode->code;

        $this->type = $giftCode->type;

        $this->user_id = $giftCode->user_id;

        $this->usage_limit = $giftCode->usage_limit;

        $this->amount = $giftCode->amount;

        $this->expires_at = $giftCode->expires_at->format('Y-m-d');

        $this->is_active = $giftCode->is_active;

    }


    public function delete($id)

    {

        GiftCode::findOrFail($id)->delete();

        $this->dispatch('success', 'کد هدیه حذف شد.');

        $this->reset(['giftcode_id', 'code', 'type', 'user_id', 'usage_limit', 'amount', 'expires_at', 'is_active']);

    }


    public function toggleStatus($id)

    {

        $giftCode = GiftCode::findOrFail($id);

        $giftCode->is_active = !$giftCode->is_active;

        $giftCode->save();

        $this->dispatch('success', 'وضعیت کد هدیه تغییر کرد.');

    }


    public function render()

    {

        $giftCodes = GiftCode::query()
            ->with('user')
            ->when($this->search, fn($q) => $q->where('code', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);


        $users = User::query()
            ->when($this->userSearch, fn($q) => $q->where('name', 'like', "%{$this->userSearch}%")
                ->orWhere('mobile', 'like', "%{$this->userSearch}%"))
            ->select('id', 'name', 'mobile')
            ->limit(20)
            ->get();


        return view('livewire.manager.gift-code.index', [

            'users' => $users,

            'giftCodes' => $giftCodes,

        ])->layout('layouts.manager.app');

    }

}
