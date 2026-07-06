<?php

namespace App\Livewire\Admin\AdminUser;

use App\Models\Admin;
use App\Models\AdminWorkSchedule;
use Livewire\Component;

class WorkSchedule extends Component
{
    public Admin $admin;

    /**
     * آرایه به صورت [day_of_week => ['enabled' => bool, 'start_time' => 'HH:MM', 'end_time' => 'HH:MM']]
     */
    public array $schedules = [];

    // اطلاعات پروفایلِ مشاور (برای نمایش به دانش‌آموز هنگام انتخاب مشاور)
    public ?string $education       = null; // تحصیلات
    public ?string $field_of_study  = null; // رشته
    public ?string $bio             = null; // توضیحات
    public ?int    $student_capacity = null; // ظرفیتِ اختصاصی (خالی = استفاده از مقدار سراسری)

    public function mount(Admin $admin): void
    {
        $this->admin = $admin;
        $this->education        = $admin->education;
        $this->field_of_study   = $admin->field_of_study;
        $this->bio              = $admin->bio;
        $this->student_capacity = $admin->student_capacity;
        $existing = $admin->workSchedules()->get()->keyBy('day_of_week');

        foreach (array_keys(AdminWorkSchedule::DAYS) as $day) {
            $row = $existing->get($day);
            $this->schedules[$day] = [
                'enabled'    => (bool) ($row?->is_active),
                'start_time' => $row ? substr($row->start_time, 0, 5) : '08:00',
                'end_time'   => $row ? substr($row->end_time, 0, 5)   : '14:00',
            ];
        }
    }

    public function save(): void
    {
        $rules = [];
        $messages = [];

        foreach ($this->schedules as $day => $data) {
            if (!empty($data['enabled'])) {
                $rules["schedules.$day.start_time"] = 'required|date_format:H:i';
                $rules["schedules.$day.end_time"]   = 'required|date_format:H:i|after:schedules.' . $day . '.start_time';
                $dayName = AdminWorkSchedule::DAYS[$day] ?? '';
                $messages["schedules.$day.start_time.required"]   = "ساعت شروع {$dayName} را وارد کنید.";
                $messages["schedules.$day.end_time.required"]     = "ساعت پایان {$dayName} را وارد کنید.";
                $messages["schedules.$day.start_time.date_format"] = "ساعت شروع {$dayName} معتبر نیست.";
                $messages["schedules.$day.end_time.date_format"]   = "ساعت پایان {$dayName} معتبر نیست.";
                $messages["schedules.$day.end_time.after"]         = "ساعت پایان {$dayName} باید بعد از ساعت شروع باشد.";
            }
        }

        $this->validate($rules, $messages);

        foreach ($this->schedules as $day => $data) {
            $this->admin->workSchedules()->updateOrCreate(
                ['day_of_week' => $day],
                [
                    'start_time' => $data['start_time'],
                    'end_time'   => $data['end_time'],
                    'is_active'  => (bool) ($data['enabled'] ?? false),
                ]
            );
        }

        session()->flash('message', 'برنامه کاری ادمین با موفقیت ذخیره شد.');
    }

    /**
     * ذخیره‌ی اطلاعاتِ پروفایلِ مشاور: تحصیلات، رشته، توضیحات و ظرفیتِ اختصاصی.
     */
    public function saveProfile(): void
    {
        $this->validate([
            'education'        => 'nullable|string|max:255',
            'field_of_study'   => 'nullable|string|max:255',
            'bio'              => 'nullable|string|max:2000',
            'student_capacity' => 'nullable|integer|min:1|max:1000',
        ], [
            'education.max'        => 'تحصیلات نباید بیش از ۲۵۵ کاراکتر باشد.',
            'field_of_study.max'   => 'رشته نباید بیش از ۲۵۵ کاراکتر باشد.',
            'bio.max'              => 'توضیحات نباید بیش از ۲۰۰۰ کاراکتر باشد.',
            'student_capacity.integer' => 'ظرفیت باید عدد باشد.',
            'student_capacity.min' => 'ظرفیت باید حداقل ۱ باشد.',
            'student_capacity.max' => 'ظرفیت بیش از حد مجاز است.',
        ]);

        $this->admin->update([
            'education'        => $this->education ?: null,
            'field_of_study'   => $this->field_of_study ?: null,
            'bio'              => $this->bio ?: null,
            'student_capacity' => $this->student_capacity !== null && $this->student_capacity !== ''
                ? (int) $this->student_capacity
                : null,
        ]);

        session()->flash('message', 'اطلاعات مشاور با موفقیت ذخیره شد.');
    }

    public function render()
    {
        return view('livewire.admin.admin-user.work-schedule', [
            'days' => AdminWorkSchedule::DAYS,
        ])->layout('layouts.admin.app');
    }
}
