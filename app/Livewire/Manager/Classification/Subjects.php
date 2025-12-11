<?php

namespace App\Livewire\Manager\Classification;


use App\Models\CcField;

use App\Models\CcGrade;

use App\Models\CcSubject;

use App\Models\EducationLevel;

use Artesaos\SEOTools\Traits\SEOTools;

use Livewire\Component;

use Livewire\WithPagination;


class Subjects extends Component

{

    use WithPagination, SEOTools;


    public EducationLevel $educationLevel;

    public CcGrade $grade;


    public $search = '';

    public $name = '';

    public $cc_field_id = '';

    public $type = 'specialized';

    public $order = 0;

    public $editingId = null;


    protected $queryString = ['search'];


    public function mount(EducationLevel $educationLevel, CcGrade $grade)

    {

        $this->educationLevel = $educationLevel;

        $this->grade = $grade;

        $this->seoConfig();

    }


    public function seoConfig()

    {

        $this->seo()->setTitle('مدیریت دروس ' . $this->grade->name);

    }


    public function rules()

    {

        return [

            'name' => 'required|string|max:150',

            'cc_field_id' => 'nullable|exists:cc_fields,id',

            'type' => 'required|in:general,specialized',

            'order' => 'nullable|integer|min:0',

        ];

    }


    public function messages()

    {

        return [

            'name.required' => 'نام درس الزامی است.',

            'name.string' => 'نام درس باید متن باشد.',

            'name.max' => 'نام درس نباید بیشتر از ۱۵۰ کاراکتر باشد.',

            'cc_field_id.exists' => 'رشته انتخاب شده معتبر نیست.',

            'type.required' => 'نوع درس الزامی است.',

            'type.in' => 'نوع درس باید عمومی یا تخصصی باشد.',

            'order.integer' => 'ترتیب باید عدد باشد.',

            'order.min' => 'ترتیب نمی‌تواند منفی باشد.',

        ];

    }


    public function submit()

    {

        $this->validate();


        $data = [

            'cc_grade_id' => $this->grade->id,

            'cc_field_id' => $this->cc_field_id ?: null,

            'name' => $this->name,

            'type' => $this->type,

            'order' => $this->order ?? 0,

        ];


        if ($this->editingId) {

            $subject = CcSubject::findOrFail($this->editingId);

            $subject->update($data);

            $this->dispatch('success', 'درس با موفقیت ویرایش شد.');

        } else {

            CcSubject::create($data);

            $this->dispatch('success', 'درس با موفقیت ایجاد شد.');

        }


        $this->resetForm();

    }


    public function edit($id)

    {

        $subject = CcSubject::findOrFail($id);

        $this->editingId = $subject->id;

        $this->name = $subject->name;

        $this->cc_field_id = $subject->cc_field_id;

        $this->type = $subject->type;

        $this->order = $subject->order;

    }


    public function delete($id)

    {

        $subject = CcSubject::findOrFail($id);


        if ($subject->chapters()->exists()) {

            $this->dispatch('warning', 'این درس دارای فصل است و نمی‌توان آن را حذف کرد.');

            return;

        }


        $subject->delete();

        $this->dispatch('success', 'درس با موفقیت حذف شد.');

    }


    public function resetForm()

    {

        $this->reset(['name', 'cc_field_id', 'type', 'order', 'editingId']);

        $this->type = 'specialized';

    }


    public function updatingSearch()

    {

        $this->resetPage();

    }


    public function render()

    {

        $subjects = CcSubject::query()
            ->where('cc_grade_id', $this->grade->id)
            ->with(['field'])
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('order')
            ->paginate(10);


        $fields = CcField::active()->ordered()->get();


        return view('livewire.manager.classification.subjects', [

            'subjects' => $subjects,

            'fields' => $fields,

        ])->layout('layouts.manager.app');

    }

}
