<?php

namespace App\Livewire\Manager\Classification;


use App\Models\ClassificationProject;

use App\Models\ProjectGradeSetting;

use Artesaos\SEOTools\Traits\SEOTools;

use Carbon\Carbon;

use Livewire\Component;

use Livewire\WithPagination;

use Morilog\Jalali\Jalalian;


class Projects extends Component

{

    use WithPagination, SEOTools;


    public $search = '';

    public $name = '';

    public $description = '';

    public $start_date = '';

    public $start_time = '';

    public $end_date = '';

    public $end_time = '';

    public $is_active = true;

    public $editingId = null;


    // Grade settings for each student grade

    public $gradeSettings = [];


    protected $queryString = ['search'];


    public function mount()

    {

        $this->seoConfig();

        $this->initGradeSettings();

    }


    public function seoConfig()

    {

        $this->seo()->setTitle('مدیریت پروژه‌های طبقه‌بندی');

    }


    public function initGradeSettings()

    {

        // Default settings based on student grade

        $this->gradeSettings = [

            12 => [

                ['target_grade' => 12, 'type' => 'progress', 'has_general' => true, 'enabled' => true],

                ['target_grade' => 11, 'type' => 'review', 'has_general' => false, 'enabled' => true],

                ['target_grade' => 10, 'type' => 'review', 'has_general' => false, 'enabled' => true],

            ],

            11 => [

                ['target_grade' => 11, 'type' => 'progress', 'has_general' => true, 'enabled' => true],

                ['target_grade' => 10, 'type' => 'review', 'has_general' => false, 'enabled' => true],

            ],

            10 => [

                ['target_grade' => 10, 'type' => 'progress', 'has_general' => true, 'enabled' => true],

            ],

        ];

    }


    public function rules()

    {

        return [

            'name' => 'required|string|max:200',

            'description' => 'nullable|string|max:2000',

            'start_date' => 'required|string',

            'start_time' => 'required|string',

            'end_date' => 'required|string',

            'end_time' => 'required|string',

            'is_active' => 'boolean',

        ];

    }


    public function messages()

    {

        return [

            'name.required' => 'نام پروژه الزامی است.',

            'name.max' => 'نام پروژه نباید بیشتر از ۲۰۰ کاراکتر باشد.',

            'description.max' => 'توضیحات نباید بیشتر از ۲۰۰۰ کاراکتر باشد.',

            'start_date.required' => 'تاریخ شروع الزامی است.',

            'start_time.required' => 'ساعت شروع الزامی است.',

            'end_date.required' => 'تاریخ پایان الزامی است.',

            'end_time.required' => 'ساعت پایان الزامی است.',

        ];

    }


    protected function convertToGregorian($jalaliDate, $time)

    {

        try {

            $parts = explode('/', $jalaliDate);

            if (count($parts) !== 3) {

                return null;

            }


            $jalalian = Jalalian::fromFormat('Y/m/d H:i', $jalaliDate . ' ' . $time);

            return $jalalian->toCarbon();

        } catch (\Exception $e) {

            return null;

        }

    }


    public function submit()

    {

        $this->validate();


        $startAt = $this->convertToGregorian($this->start_date, $this->start_time);

        $endAt = $this->convertToGregorian($this->end_date, $this->end_time);


        if (!$startAt || !$endAt) {

            $this->dispatch('error', 'فرمت تاریخ نامعتبر است.');

            return;

        }


        if ($endAt <= $startAt) {

            $this->dispatch('error', 'تاریخ پایان باید بعد از تاریخ شروع باشد.');

            return;

        }


        $data = [

            'name' => $this->name,

            'description' => $this->description,

            'start_at' => $startAt,

            'end_at' => $endAt,

            'is_active' => $this->is_active,

        ];


        if ($this->editingId) {

            $project = ClassificationProject::findOrFail($this->editingId);

            $project->update($data);


            // Update grade settings

            $project->gradeSettings()->delete();

            $this->saveGradeSettings($project);


            $this->dispatch('success', 'پروژه با موفقیت ویرایش شد.');

        } else {

            $project = ClassificationProject::create($data);

            $this->saveGradeSettings($project);

            $this->dispatch('success', 'پروژه با موفقیت ایجاد شد.');

        }


        $this->resetForm();

    }


    protected function saveGradeSettings(ClassificationProject $project)

    {

        foreach ($this->gradeSettings as $studentGrade => $settings) {

            foreach ($settings as $setting) {

                if (!empty($setting['enabled'])) {

                    ProjectGradeSetting::create([

                        'classification_project_id' => $project->id,

                        'student_grade' => $studentGrade,

                        'target_grade' => $setting['target_grade'],

                        'type' => $setting['type'],

                        'has_general' => $setting['has_general'] ?? false,

                    ]);

                }

            }

        }

    }


    public function edit($id)

    {

        $project = ClassificationProject::with('gradeSettings')->findOrFail($id);

        $this->editingId = $project->id;

        $this->name = $project->name;

        $this->description = $project->description;


        // Convert to Jalali

        $this->start_date = Jalalian::fromCarbon($project->start_at)->format('Y/m/d');

        $this->start_time = $project->start_at->format('H:i');

        $this->end_date = Jalalian::fromCarbon($project->end_at)->format('Y/m/d');

        $this->end_time = $project->end_at->format('H:i');


        $this->is_active = $project->is_active;


        // Load grade settings

        $this->initGradeSettings();

        foreach ($project->gradeSettings as $setting) {

            $studentGrade = $setting->student_grade;

            if (isset($this->gradeSettings[$studentGrade])) {

                foreach ($this->gradeSettings[$studentGrade] as $key => $gs) {

                    if ($gs['target_grade'] == $setting->target_grade) {

                        $this->gradeSettings[$studentGrade][$key]['type'] = $setting->type;

                        $this->gradeSettings[$studentGrade][$key]['has_general'] = $setting->has_general;

                        $this->gradeSettings[$studentGrade][$key]['enabled'] = true;

                    }

                }

            }

        }

    }


    public function delete($id)

    {

        $project = ClassificationProject::findOrFail($id);


        if ($project->classifications()->exists()) {

            $this->dispatch('warning', 'این پروژه دارای طبقه‌بندی دانش‌آموزان است.');

            return;

        }


        $project->delete();

        $this->dispatch('success', 'پروژه با موفقیت حذف شد.');

    }


    public function resetForm()

    {

        $this->reset(['name', 'description', 'start_date', 'start_time', 'end_date', 'end_time', 'is_active', 'editingId']);

        $this->is_active = true;

        $this->initGradeSettings();

    }


    public function updatingSearch()

    {

        $this->resetPage();

    }


    public function render()

    {

        $projects = ClassificationProject::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);


        return view('livewire.manager.classification.projects', [

            'projects' => $projects,

        ])->layout('layouts.manager.app');

    }

}


