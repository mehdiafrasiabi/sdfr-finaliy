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

    public function mount(Admin $admin): void
    {
        $this->admin = $admin;
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

    public function render()
    {
        return view('livewire.admin.admin-user.work-schedule', [
            'days' => AdminWorkSchedule::DAYS,
        ])->layout('layouts.admin.app');
    }
}
