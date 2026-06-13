<?php

namespace App\Livewire\Manager\School;

use App\Models\Admin;
use App\Models\School;
use App\Models\SchoolStaff;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, SEOTools;

    public $search = '';

    public $schoolId;
    public $name;
    public $code;
    public $address;
    public $public_phone;
    public $type;
    public array $advisor_ids = [];

    public $manager_name;
    public $manager_phone;
    public $deputy_name;
    public $deputy_phone;

    // متغیر ذخیره اطلاعات ادمین جدید برای نمایش در بلید
    public $newAccountDetails = null;

    /** نوع‌های مجاز مدرسه */
    public const SCHOOL_TYPES = [
        'دولتی', 'غیرانتفاعی', 'هیئت امنایی', 'تیزهوشان', 'نمونه دولتی', 'دانشگاه آزاد',
    ];

    public function mount(): void
    {
        $this->seo()->setTitle('مدیریت مدارس');
    }

    public function submit(array $formData): void
    {
        // مقادیر آرایه‌ای/چندانتخابی از طریق FormData منتقل نمی‌شوند؛ از prop خوانده می‌شوند.
        $formData['advisor_ids'] = $this->advisor_ids;
        $formData['type']        = $this->type;

        $rules = [
            'name'          => 'required|string|max:255',
            'code'          => 'required|string|max:50|unique:schools,code,' . ($this->schoolId ?? 'NULL') . ',id,deleted_at,NULL',
            'address'       => 'required|string|max:1000',
            'public_phone'  => 'required|regex:/^0\d{10}$/',
            'type'          => 'required|in:' . implode(',', self::SCHOOL_TYPES),
            'advisor_ids'   => 'required|array|min:1',
            'advisor_ids.*' => 'exists:admins,id',
            'manager_name'  => 'required|string|max:255',
            'manager_phone' => 'required|regex:/^0\d{10}$/',
            'deputy_name'   => 'required|string|max:255',
            'deputy_phone'  => 'required|regex:/^0\d{10}$/',
        ];

        $messages = [
            '*.required'           => 'فیلد ضروری است',
            'code.unique'          => 'کد مدرسه تکراری است',
            'public_phone.regex'   => 'فرمت تلفن مدرسه معتبر نیست (مثلا 02112345678)',
            'type.in'              => 'نوع مدرسه نامعتبر است',
            'advisor_ids.required' => 'حداقل یک مشاور باید انتخاب شود',
            'manager_phone.regex'  => 'فرمت تلفن مدیر معتبر نیست',
            'deputy_phone.regex'   => 'فرمت تلفن معاون معتبر نیست',
        ];

        $validator = Validator::make($formData, $rules, $messages);
        $validator->validate();

        // فقط ادمین‌هایی که نقش «مشاور تحصیلی» دارند مجاز به تخصیص هستند.
        $advisorIds = Admin::role('مشاور تحصیلی')
            ->whereIn('id', $formData['advisor_ids'])
            ->pluck('id')
            ->all();

        $plainPassword = null;

        DB::transaction(function () use ($formData, $advisorIds, &$plainPassword) {
            $school = School::updateOrCreate(
                ['id' => $this->schoolId],
                [
                    'name'         => $formData['name'],
                    'code'         => $formData['code'],
                    'address'      => $formData['address'],
                    'public_phone' => $formData['public_phone'],
                    'type'         => $formData['type'],
                ]
            );

            SchoolStaff::updateOrCreate(
                ['school_id' => $school->id, 'role' => 'manager'],
                ['name' => $formData['manager_name'], 'phone' => $formData['manager_phone']]
            );

            SchoolStaff::updateOrCreate(
                ['school_id' => $school->id, 'role' => 'deputy'],
                ['name' => $formData['deputy_name'], 'phone' => $formData['deputy_phone']]
            );

            // مشاوران فعال‌شدهٔ مدرسه
            $school->advisors()->sync($advisorIds);

            // اکانت ورود «مدیر مدرسه» به‌صورت یک کاربر Admin با نقش school-manager.
            $managerAdmin = Admin::where('school_id', $school->id)->first();
            if (!$managerAdmin) {
                $plainPassword = $this->generatePassword();
                $managerAdmin = Admin::create([
                    'name'      => $formData['manager_name'],
                    'mobile'    => $formData['manager_phone'],
                    'email'     => 'school-' . $school->code . '@school.local',
                    'password'  => Hash::make($plainPassword),
                    'school_id' => $school->id,
                ]);
            } else {
                $managerAdmin->update([
                    'name'   => $formData['manager_name'],
                    'mobile' => $formData['manager_phone'],
                ]);
            }
            $managerAdmin->syncRoles(['school-manager']);
        });

        // ثبت اطلاعات در سشن یا پراپرتی برای نمایش در نمای کامپوننت
        if ($plainPassword) {
            $loginEmail = 'school-' . $formData['code'] . '@school.local';
            $this->newAccountDetails = [
                'email'    => $loginEmail,
                'mobile'   => $formData['manager_phone'],
                'password' => $plainPassword
            ];
        } else {
            // اگر ویرایش بود، باکس پیام قبلی پاک شود
            $this->newAccountDetails = null;
        }

        // فرم را ریست کن اما اطلاعات اکانت جدید کماکان باقی بماند
        $this->resetForm(false);

        $this->dispatch('success', 'عملیات با موفقیت انجام شد');
    }

    /**
     * تولید رمز تصادفی امن برای اکانت مدیر مدرسه.
     */
    public function generatePassword(int $length = 12): string
    {
        $numbers    = '0123456789';
        $lowercases = 'abcdefghijklmnopqrstuvwxyz';
        $uppercases = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $symbol     = '!@#$%^&*';

        $password = [
            $numbers[random_int(0, strlen($numbers) - 1)],
            $lowercases[random_int(0, strlen($lowercases) - 1)],
            $uppercases[random_int(0, strlen($uppercases) - 1)],
            $symbol[random_int(0, strlen($symbol) - 1)],
        ];

        $allCharset = $numbers . $lowercases . $uppercases . $symbol;
        while (count($password) < $length) {
            $password[] = $allCharset[random_int(0, strlen($allCharset) - 1)];
        }

        shuffle($password);
        return implode('', $password);
    }

    public function edit(int $schoolId): void
    {
        $school = School::with(['manager', 'deputy', 'advisors'])->find($schoolId);
        if (!$school) {
            return;
        }

        $this->schoolId      = $school->id;
        $this->name          = $school->name;
        $this->code          = $school->code;
        $this->address       = $school->address;
        $this->public_phone  = $school->public_phone;
        $this->type          = $school->type;
        $this->advisor_ids   = $school->advisors->pluck('id')->map(fn($id) => (string) $id)->all();
        $this->manager_name  = $school->manager?->name;
        $this->manager_phone = $school->manager?->phone;
        $this->deputy_name   = $school->deputy?->name;
        $this->deputy_phone  = $school->deputy?->phone;
    }

    public function delete(int $schoolId): void
    {
        $school = School::find($schoolId);
        if (!$school) {
            return;
        }
        $school->delete();
        $this->dispatch('success', 'مدرسه با موفقیت حذف شد');
    }

    public function resetForm($resetDetails = true): void
    {
        $this->reset([
            'schoolId', 'name', 'code', 'address', 'public_phone', 'type', 'advisor_ids',
            'manager_name', 'manager_phone', 'deputy_name', 'deputy_phone',
        ]);

        if ($resetDetails) {
            $this->newAccountDetails = null;
        }
    }

    public function render()
    {
        $schools = School::query()
            ->with(['manager', 'deputy', 'advisors'])
            ->withCount(['advisors', 'students'])
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('code', 'like', "%{$this->search}%");
            }))
            ->latest()
            ->paginate(10);

        $advisorOptions = Admin::role('مشاور تحصیلی')->orderBy('name')->get(['id', 'name']);

        return view('livewire.manager.school.index', [
            'schools'        => $schools,
            'advisorOptions' => $advisorOptions,
            'schoolTypes'    => self::SCHOOL_TYPES,
        ])->layout('layouts.manager.app');
    }
}
