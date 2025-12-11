<?php

namespace App\Livewire\Manager\Classification;


use App\Models\CcField;

use Artesaos\SEOTools\Traits\SEOTools;

use Illuminate\Support\Str;

use Livewire\Component;

use Livewire\WithPagination;


class Fields extends Component

{

    use WithPagination, SEOTools;


    public $search = '';

    public $name = '';

    public $slug = '';

    public $order = 0;

    public $is_active = true;

    public $editingId = null;


    protected $queryString = ['search'];


    public function mount()

    {

        $this->seoConfig();

    }


    public function seoConfig()

    {

        $this->seo()->setTitle('مدیریت رشته‌های تحصیلی');

    }


    public function rules()

    {

        $slugRule = 'required|string|max:50';

        if ($this->editingId) {

            $slugRule .= '|unique:cc_fields,slug,' . $this->editingId;

        } else {

            $slugRule .= '|unique:cc_fields,slug';

        }


        return [

            'name' => 'required|string|max:100',

            'slug' => $slugRule,

            'order' => 'nullable|integer|min:0',

            'is_active' => 'boolean',

        ];

    }


    public function messages()

    {

        return [

            'name.required' => 'نام رشته الزامی است.',

            'name.string' => 'نام رشته باید متن باشد.',

            'name.max' => 'نام رشته نباید بیشتر از ۱۰۰ کاراکتر باشد.',

            'slug.required' => 'کد رشته الزامی است.',

            'slug.unique' => 'این کد رشته قبلاً استفاده شده است.',

            'slug.max' => 'کد رشته نباید بیشتر از ۵۰ کاراکتر باشد.',

            'order.integer' => 'ترتیب باید عدد باشد.',

            'order.min' => 'ترتیب نمی‌تواند منفی باشد.',

        ];

    }


    public function updatedName($value)

    {

        if (!$this->editingId) {

            $this->slug = Str::slug($value);

        }

    }


    public function submit()

    {

        $this->validate();


        $data = [

            'name' => $this->name,

            'slug' => $this->slug,

            'order' => $this->order ?? 0,

            'is_active' => $this->is_active,

        ];


        if ($this->editingId) {

            $field = CcField::findOrFail($this->editingId);

            $field->update($data);

            $this->dispatch('success', 'رشته تحصیلی با موفقیت ویرایش شد.');

        } else {

            CcField::create($data);

            $this->dispatch('success', 'رشته تحصیلی با موفقیت ایجاد شد.');

        }


        $this->resetForm();

    }


    public function edit($id)

    {

        $field = CcField::findOrFail($id);

        $this->editingId = $field->id;

        $this->name = $field->name;

        $this->slug = $field->slug;

        $this->order = $field->order;

        $this->is_active = $field->is_active;

    }


    public function delete($id)

    {

        $field = CcField::findOrFail($id);


        if ($field->subjects()->exists()) {

            $this->dispatch('warning', 'این رشته دارای درس است و نمی‌توان آن را حذف کرد.');

            return;

        }


        $field->delete();

        $this->dispatch('success', 'رشته تحصیلی با موفقیت حذف شد.');

    }


    public function resetForm()

    {

        $this->reset(['name', 'slug', 'order', 'is_active', 'editingId']);

        $this->is_active = true;

    }


    public function updatingSearch()

    {

        $this->resetPage();

    }


    public function render()

    {

        $fields = CcField::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('order')
            ->paginate(10);


        return view('livewire.manager.classification.fields', [

            'fields' => $fields,

        ])->layout('layouts.manager.app');

    }

}
