<?php


namespace App\Livewire\Manager\Setting;


use App\Models\GeneralSetting;

use Artesaos\SEOTools\Traits\SEOTools;

use Illuminate\Support\Facades\Validator;

use Livewire\Component;


class General extends Component

{

    use SEOTools;


    public $activeTab = 'main';


    // اطلاعات اصلی فروشگاه

    public $site_title;

    public $site_short_title;

    public $h1_tag;

    public $site_description;

    public $support_hours;

    public $support_phone;


    // تگ‌ها و اسکریپت‌های هدر و فوتر

    public $head_scripts;

    public $footer_scripts;


    // کد اسکریپت مجوزها

    public $enamad_script;

    public $samandehi_script;

    public $etehaddiye_script;


    // لینک شبکه‌های اجتماعی

    public $instagram;

    public $telegram;

    public $youtube;

    public $aparat;


    // دکمه شناور پشتیبانی

    public $floating_support_enabled;

    public $floating_mobile;

    public $floating_phone;

    public $floating_whatsapp;

    public $floating_telegram;


    // بستن موقت پنل دانش‌آموز

    public $student_panel_closed = false;

    public $student_panel_closed_message;


    // ظرفیتِ سراسریِ پیش‌فرضِ هر مشاور تحصیلی

    public $advisor_default_capacity = 50;


    public function mount()

    {

        $this->seoConfig();

        $this->loadSettings();

    }


    public function seoConfig()

    {

        $this->seo()
            ->setTitle('تنظیمات کلی');

    }


    public function loadSettings()

    {

        $settings = GeneralSetting::first();


        if ($settings) {

            $this->site_title = $settings->site_title;

            $this->site_short_title = $settings->site_short_title;

            $this->h1_tag = $settings->h1_tag;

            $this->site_description = $settings->site_description;

            $this->support_hours = $settings->support_hours;

            $this->support_phone = $settings->support_phone;


            $this->head_scripts = $settings->head_scripts;

            $this->footer_scripts = $settings->footer_scripts;


            $this->enamad_script = $settings->enamad_script;

            $this->samandehi_script = $settings->samandehi_script;

            $this->etehaddiye_script = $settings->etehaddiye_script;


            $this->instagram = $settings->instagram;

            $this->telegram = $settings->telegram;

            $this->youtube = $settings->youtube;

            $this->aparat = $settings->aparat;


            $this->floating_support_enabled = $settings->floating_support_enabled;

            $this->floating_mobile = $settings->floating_mobile;

            $this->floating_phone = $settings->floating_phone;

            $this->floating_whatsapp = $settings->floating_whatsapp;

            $this->floating_telegram = $settings->floating_telegram;


            $this->student_panel_closed = (bool) ($settings->student_panel_closed ?? false);

            $this->student_panel_closed_message = $settings->student_panel_closed_message;

            $this->advisor_default_capacity = (int) ($settings->advisor_default_capacity ?? 50);

        }

    }


    public function saveAdvisorCapacity()

    {

        $validator = Validator::make([

            'advisor_default_capacity' => $this->advisor_default_capacity,

        ], [

            'advisor_default_capacity' => 'required|integer|min:1|max:1000',

        ], [

            'advisor_default_capacity.required' => 'تعیین ظرفیت پیش‌فرض الزامی است.',

            'advisor_default_capacity.integer'  => 'ظرفیت باید عدد باشد.',

            'advisor_default_capacity.min'      => 'ظرفیت باید حداقل ۱ باشد.',

            'advisor_default_capacity.max'      => 'ظرفیت بیش از حد مجاز است.',

        ]);


        $validator->validate();


        $this->updateOrCreateSettings([

            'advisor_default_capacity' => (int) $this->advisor_default_capacity,

        ]);


        $this->dispatch('success', 'ظرفیت پیش‌فرض مشاوران با موفقیت ذخیره شد.');

    }


    public function savePanelStatus()

    {

        $this->updateOrCreateSettings([

            'student_panel_closed' => $this->student_panel_closed ? true : false,

            'student_panel_closed_message' => $this->student_panel_closed_message,

        ]);


        $this->dispatch('success', $this->student_panel_closed ? 'پنل دانش‌آموز بسته شد.' : 'پنل دانش‌آموز باز شد.');

    }


    public function setTab($tab)

    {

        $this->activeTab = $tab;

    }


    public function saveMainInfo()

    {

        $validator = Validator::make([

            'site_title' => $this->site_title,

            'site_short_title' => $this->site_short_title,

            'h1_tag' => $this->h1_tag,

            'site_description' => $this->site_description,

            'support_hours' => $this->support_hours,

            'support_phone' => $this->support_phone,

        ], [

            'site_title' => 'nullable|string|max:255',

            'site_short_title' => 'nullable|string|max:100',

            'h1_tag' => 'nullable|string|max:255',

            'site_description' => 'nullable|string|max:1000',

            'support_hours' => 'nullable|string|max:100',

            'support_phone' => 'nullable|string|max:50',

        ], [

            '*.string' => 'فرمت فیلد اشتباه است.',

            '*.max' => 'طول فیلد بیش از حد مجاز است.',

        ]);


        $validator->validate();


        $this->updateOrCreateSettings([

            'site_title' => $this->site_title,

            'site_short_title' => $this->site_short_title,

            'h1_tag' => $this->h1_tag,

            'site_description' => $this->site_description,

            'support_hours' => $this->support_hours,

            'support_phone' => $this->support_phone,

        ]);


        $this->dispatch('success', 'اطلاعات اصلی با موفقیت ذخیره شد.');

    }


    public function saveScripts()

    {

        $validator = Validator::make([

            'head_scripts' => $this->head_scripts,

            'footer_scripts' => $this->footer_scripts,

        ], [

            'head_scripts' => 'nullable|string',

            'footer_scripts' => 'nullable|string',

        ]);


        $validator->validate();


        $this->updateOrCreateSettings([

            'head_scripts' => $this->head_scripts,

            'footer_scripts' => $this->footer_scripts,

        ]);


        $this->dispatch('success', 'کدهای هدر و فوتر با موفقیت ذخیره شد.');

    }


    public function saveLicenses()

    {

        $validator = Validator::make([

            'enamad_script' => $this->enamad_script,

            'samandehi_script' => $this->samandehi_script,

            'etehaddiye_script' => $this->etehaddiye_script,

        ], [

            'enamad_script' => 'nullable|string',

            'samandehi_script' => 'nullable|string',

            'etehaddiye_script' => 'nullable|string',

        ]);


        $validator->validate();


        $this->updateOrCreateSettings([

            'enamad_script' => $this->enamad_script,

            'samandehi_script' => $this->samandehi_script,

            'etehaddiye_script' => $this->etehaddiye_script,

        ]);


        $this->dispatch('success', 'اسکریپت مجوزها با موفقیت ذخیره شد.');

    }


    public function saveSocialLinks()

    {

        $validator = Validator::make([

            'instagram' => $this->instagram,

            'telegram' => $this->telegram,

            'youtube' => $this->youtube,

            'aparat' => $this->aparat,

        ], [

            'instagram' => 'nullable|url|max:255',

            'telegram' => 'nullable|url|max:255',

            'youtube' => 'nullable|url|max:255',

            'aparat' => 'nullable|url|max:255',

        ], [

            '*.url' => 'آدرس وارد شده معتبر نیست.',

            '*.max' => 'طول آدرس بیش از حد مجاز است.',

        ]);


        $validator->validate();


        $this->updateOrCreateSettings([

            'instagram' => $this->instagram,

            'telegram' => $this->telegram,

            'youtube' => $this->youtube,

            'aparat' => $this->aparat,

        ]);


        $this->dispatch('success', 'لینک شبکه‌های اجتماعی با موفقیت ذخیره شد.');

    }


    public function saveFloatingSupport()

    {

        $validator = Validator::make([

            'floating_support_enabled' => $this->floating_support_enabled,

            'floating_mobile' => $this->floating_mobile,

            'floating_phone' => $this->floating_phone,

            'floating_whatsapp' => $this->floating_whatsapp,

            'floating_telegram' => $this->floating_telegram,

        ], [

            'floating_support_enabled' => 'boolean',

            'floating_mobile' => 'nullable|string|max:20',

            'floating_phone' => 'nullable|string|max:20',

            'floating_whatsapp' => 'nullable|string|max:20',

            'floating_telegram' => 'nullable|string|max:100',

        ], [

            '*.string' => 'فرمت فیلد اشتباه است.',

            '*.max' => 'طول فیلد بیش از حد مجاز است.',

        ]);


        $validator->validate();


        $this->updateOrCreateSettings([

            'floating_support_enabled' => $this->floating_support_enabled ? true : false,

            'floating_mobile' => $this->floating_mobile,

            'floating_phone' => $this->floating_phone,

            'floating_whatsapp' => $this->floating_whatsapp,

            'floating_telegram' => $this->floating_telegram,

        ]);


        $this->dispatch('success', 'تنظیمات دکمه شناور با موفقیت ذخیره شد.');

    }


    private function updateOrCreateSettings(array $data)

    {

        $settings = GeneralSetting::first();


        if ($settings) {

            $settings->update($data);

        } else {

            GeneralSetting::create($data);

        }

    }


    public function render()

    {

        return view('livewire.manager.setting.general')->layout('layouts.manager.app');

    }

}
