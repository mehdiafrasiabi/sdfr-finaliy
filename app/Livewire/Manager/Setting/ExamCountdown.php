<?php

namespace App\Livewire\Manager\Setting;

use App\Models\ExamCountdownSetting;
use App\Models\ExamCountdownEvent;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Verta;

class ExamCountdown extends Component
{
    use SEOTools;

    public $title = '';
    public $subtitle = '';
    public $card_title = '';
    public $start_date = '';
    public $end_date = '';
    public $description = '';
    public $events = [];

    public function mount()
    {
        $this->seo()->setTitle('روز شمار کنکور');

        $setting = ExamCountdownSetting::with('events')->first();

        if ($setting) {
            $this->title       = $setting->title;
            $this->subtitle    = $setting->subtitle;
            $this->card_title  = $setting->card_title;
            $this->start_date  = $setting->start_date ? verta($setting->start_date)->format('Y/n/j') : '';
            $this->end_date    = $setting->end_date   ? verta($setting->end_date)->format('Y/n/j')   : '';
            $this->description = $setting->description ?? '';

            foreach ($setting->events as $i => $event) {
                $this->events[] = [
                    'event_title' => $event->event_title,
                    'event_date'  => $event->event_date ? verta($event->event_date)->format('Y/n/j') : '',
                ];
            }
        }
    }

    public function addEvent()
    {
        $this->events[] = ['event_title' => '', 'event_date' => ''];
    }

    public function removeEvent($index)
    {
        array_splice($this->events, $index, 1);
        $this->events = array_values($this->events);
    }

    public function save()
    {
        $validator = Validator::make(
            [
                'title'      => $this->title,
                'subtitle'   => $this->subtitle,
                'card_title' => $this->card_title,
                'start_date' => $this->start_date,
                'end_date'   => $this->end_date,
                'events'     => $this->events,
            ],
            [
                'title'                  => 'required|string|max:200',
                'subtitle'               => 'required|string|max:500',
                'card_title'             => 'required|string|max:200',
                'start_date'             => 'required|string',
                'end_date'               => 'required|string',
                'events'                 => 'array',
                'events.*.event_title'   => 'required|string|max:200',
                'events.*.event_date'    => 'required|string',
            ],
            [
                'title.required'                => 'تیتر الزامی است.',
                'subtitle.required'             => 'زیر تیتر الزامی است.',
                'card_title.required'           => 'عنوان کارت الزامی است.',
                'start_date.required'           => 'تاریخ شروع شمارش الزامی است.',
                'end_date.required'             => 'تاریخ پایان شمارش الزامی است.',
                'events.*.event_title.required' => 'عنوان رویداد الزامی است.',
                'events.*.event_date.required'  => 'تاریخ رویداد الزامی است.',
            ]
        );

        $validator->validate();

        $startGregorian = $this->jalaliToGregorian($this->start_date);
        $endGregorian   = $this->jalaliToGregorian($this->end_date);

        $setting = ExamCountdownSetting::updateOrCreate(
            ['id' => ExamCountdownSetting::value('id') ?? 0],
            [
                'title'       => $this->title,
                'subtitle'    => $this->subtitle,
                'card_title'  => $this->card_title,
                'start_date'  => $startGregorian,
                'end_date'    => $endGregorian,
                'description' => $this->description,
            ]
        );

        $setting->events()->delete();

        foreach ($this->events as $i => $event) {
            ExamCountdownEvent::create([
                'exam_countdown_setting_id' => $setting->id,
                'event_title'               => $event['event_title'],
                'event_date'                => $this->jalaliToGregorian($event['event_date']),
                'sort_order'                => $i,
            ]);
        }

        $this->dispatch('success', 'روز شمار کنکور با موفقیت ذخیره شد.');
    }

    private function jalaliToGregorian(string $jalaliDate): string
    {
        try {
            return \Hekmatinasser\Verta\Facades\Verta::parse($jalaliDate)->toCarbon()->toDateString();
        } catch (\Exception $e) {
            return $jalaliDate;
        }
    }

    public function render()
    {
        return view('livewire.manager.setting.exam-countdown')
            ->layout('layouts.manager.app');
    }
}
