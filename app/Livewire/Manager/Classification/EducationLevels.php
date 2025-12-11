<?php

namespace App\Livewire\Manager\Classification;

use Livewire\Component;

use App\Models\EducationLevel;

use Artesaos\SEOTools\Traits\SEOTools;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Str;

use Livewire\WithPagination;
class EducationLevels extends Component
{
    use WithPagination, SEOTools;


    public $search = '';

    public $name = '';

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

        $this->seo()->setTitle('مدیریت دوره‌های تحصیلی');

    }


    public function rules()

    {

        return [

            'name' => 'required|string|max:100',

            'order' => 'nullable|integer|min:0',

            'is_active' => 'boolean',

        ];

    }


    public function messages()

    {

        return [

            'name.required' => 'نام دوره تحصیلی الزامی است.',

            'name.string' => 'نام دوره تحصیلی باید متن باشد.',

            'name.max' => 'نام دوره تحصیلی نباید بیشتر از ۱۰۰ کاراکتر باشد.',

            'order.integer' => 'ترتیب باید عدد باشد.',

            'order.min' => 'ترتیب نمی‌تواند منفی باشد.',

        ];

    }


    public function submit()

    {

        $this->validate();


        $data = [

            'name' => $this->name,

            'slug' => Str::slug($this->name),

            'order' => $this->order ?? 0,

            'is_active' => $this->is_active,

        ];


        if ($this->editingId) {

            $level = EducationLevel::findOrFail($this->editingId);

            $level->update($data);

            $this->dispatch('success', 'دوره تحصیلی با موفقیت ویرایش شد.');

        } else {

            EducationLevel::create($data);

            $this->dispatch('success', 'دوره تحصیلی با موفقیت ایجاد شد.');

        }


        $this->resetForm();

    }


    public function edit($id)

    {

        $level = EducationLevel::findOrFail($id);

        $this->editingId = $level->id;

        $this->name = $level->name;

        $this->order = $level->order;

        $this->is_active = $level->is_active;

    }


    public function delete($id)

    {

        $level = EducationLevel::findOrFail($id);


        if ($level->grades()->exists()) {

            $this->dispatch('warning', 'این دوره تحصیلی دارای پایه است و نمی‌توان آن را حذف کرد.');

            return;

        }


        $level->delete();

        $this->dispatch('success', 'دوره تحصیلی با موفقیت حذف شد.');

    }


    public function resetForm()

    {

        $this->reset(['name', 'order', 'is_active', 'editingId']);

        $this->is_active = true;

    }


    public function updatingSearch()

    {

        $this->resetPage();

    }


    public function render()

    {

        $levels = EducationLevel::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('order')
            ->paginate(10);


        return view('livewire.manager.classification.education-levels', [

            'levels' => $levels,

        ])->layout('layouts.manager.app');

    }
}
