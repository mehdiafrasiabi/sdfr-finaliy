<?php

namespace App\Livewire\Manager\Classification;


use App\Models\CcGrade;

use App\Models\EducationLevel;

use Artesaos\SEOTools\Traits\SEOTools;

use Livewire\Component;

use Livewire\WithPagination;


class Grades extends Component

{

    use WithPagination, SEOTools;


    public EducationLevel $educationLevel;

    public $search = '';

    public $name = '';

    public $grade_number = '';

    public $order = 0;

    public $is_active = true;

    public $editingId = null;


    protected $queryString = ['search'];


    public function mount(EducationLevel $educationLevel)

    {

        $this->educationLevel = $educationLevel;

        $this->seoConfig();

    }


    public function seoConfig()

    {

        $this->seo()->setTitle('مدیریت پایه‌های ' . $this->educationLevel->name);

    }


    public function rules()

    {

        return [

            'name' => 'required|string|max:100',

            'grade_number' => 'required|integer|min:1|max:12',

            'order' => 'nullable|integer|min:0',

            'is_active' => 'boolean',

        ];

    }


    public function messages()

    {

        return [

            'name.required' => 'نام پایه الزامی است.',

            'name.string' => 'نام پایه باید متن باشد.',

            'name.max' => 'نام پایه نباید بیشتر از ۱۰۰ کاراکتر باشد.',

            'grade_number.required' => 'شماره پایه الزامی است.',

            'grade_number.integer' => 'شماره پایه باید عدد باشد.',

            'grade_number.min' => 'شماره پایه باید حداقل ۱ باشد.',

            'grade_number.max' => 'شماره پایه نباید بیشتر از ۱۲ باشد.',

            'order.integer' => 'ترتیب باید عدد باشد.',

            'order.min' => 'ترتیب نمی‌تواند منفی باشد.',

        ];

    }


    public function submit()

    {

        $this->validate();


        $data = [

            'education_level_id' => $this->educationLevel->id,

            'name' => $this->name,

            'grade_number' => $this->grade_number,

            'order' => $this->order ?? 0,

            'is_active' => $this->is_active,

        ];


        if ($this->editingId) {

            $grade = CcGrade::findOrFail($this->editingId);

            $grade->update($data);

            $this->dispatch('success', 'پایه تحصیلی با موفقیت ویرایش شد.');

        } else {

            CcGrade::create($data);

            $this->dispatch('success', 'پایه تحصیلی با موفقیت ایجاد شد.');

        }


        $this->resetForm();

    }


    public function edit($id)

    {

        $grade = CcGrade::findOrFail($id);

        $this->editingId = $grade->id;

        $this->name = $grade->name;

        $this->grade_number = $grade->grade_number;

        $this->order = $grade->order;

        $this->is_active = $grade->is_active;

    }


    public function delete($id)

    {

        $grade = CcGrade::findOrFail($id);


        if ($grade->subjects()->exists()) {

            $this->dispatch('warning', 'این پایه دارای درس است و نمی‌توان آن را حذف کرد.');

            return;

        }


        $grade->delete();

        $this->dispatch('success', 'پایه تحصیلی با موفقیت حذف شد.');

    }


    public function resetForm()

    {

        $this->reset(['name', 'grade_number', 'order', 'is_active', 'editingId']);

        $this->is_active = true;

    }


    public function updatingSearch()

    {

        $this->resetPage();

    }


    public function render()

    {

        $grades = CcGrade::query()
            ->where('education_level_id', $this->educationLevel->id)
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('order')
            ->paginate(10);


        return view('livewire.manager.classification.grades', [

            'grades' => $grades,

        ])->layout('layouts.manager.app');

    }

}

