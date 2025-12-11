<?php

namespace App\Livewire\Manager\Classification;


use App\Models\CcChapter;

use App\Models\CcSubject;

use Artesaos\SEOTools\Traits\SEOTools;

use Livewire\Component;

use Livewire\WithPagination;


class Chapters extends Component

{

    use WithPagination, SEOTools;


    public CcSubject $subject;


    public $search = '';

    public $name = '';

    public $order = 0;

    public $is_active = true;

    public $editingId = null;


    protected $queryString = ['search'];


    public function mount(CcSubject $subject)

    {

        $this->subject = $subject->load(['grade.educationLevel']);

        $this->seoConfig();

    }


    public function seoConfig()

    {

        $this->seo()->setTitle('مدیریت فصل‌های ' . $this->subject->name);

    }


    public function rules()

    {

        return [

            'name' => 'required|string|max:150',

            'order' => 'nullable|integer|min:0',

            'is_active' => 'boolean',

        ];

    }


    public function messages()

    {

        return [

            'name.required' => 'نام فصل الزامی است.',

            'name.string' => 'نام فصل باید متن باشد.',

            'name.max' => 'نام فصل نباید بیشتر از ۱۵۰ کاراکتر باشد.',

            'order.integer' => 'ترتیب باید عدد باشد.',

            'order.min' => 'ترتیب نمی‌تواند منفی باشد.',

        ];

    }


    public function submit()

    {

        $this->validate();


        $data = [

            'cc_subject_id' => $this->subject->id,

            'name' => $this->name,

            'order' => $this->order ?? 0,

            'is_active' => $this->is_active,

        ];


        if ($this->editingId) {

            $chapter = CcChapter::findOrFail($this->editingId);

            $chapter->update($data);

            $this->dispatch('success', 'فصل با موفقیت ویرایش شد.');

        } else {

            CcChapter::create($data);

            $this->dispatch('success', 'فصل با موفقیت ایجاد شد.');

        }


        $this->resetForm();

    }


    public function edit($id)

    {

        $chapter = CcChapter::findOrFail($id);

        $this->editingId = $chapter->id;

        $this->name = $chapter->name;

        $this->order = $chapter->order;

        $this->is_active = $chapter->is_active;

    }


    public function delete($id)

    {

        $chapter = CcChapter::findOrFail($id);


        if ($chapter->topics()->exists()) {

            $this->dispatch('warning', 'این فصل دارای مبحث است و نمی‌توان آن را حذف کرد.');

            return;

        }


        $chapter->delete();

        $this->dispatch('success', 'فصل با موفقیت حذف شد.');

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

        $chapters = CcChapter::query()
            ->where('cc_subject_id', $this->subject->id)
            ->withCount('topics')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('order')
            ->paginate(10);


        return view('livewire.manager.classification.chapters', [

            'chapters' => $chapters,

        ])->layout('layouts.manager.app');

    }

}
