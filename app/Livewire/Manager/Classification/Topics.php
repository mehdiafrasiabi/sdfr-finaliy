<?php

namespace App\Livewire\Manager\Classification;


use App\Models\CcChapter;

use App\Models\CcTopic;

use Artesaos\SEOTools\Traits\SEOTools;

use Livewire\Component;

use Livewire\WithPagination;


class Topics extends Component

{

    use WithPagination, SEOTools;


    public CcChapter $chapter;


    public $search = '';

    public $name = '';

    public $order = 0;

    public $is_active = true;

    public $editingId = null;


    protected $queryString = ['search'];


    public function mount(CcChapter $chapter)

    {

        $this->chapter = $chapter->load(['subject.grade.educationLevel']);

        $this->seoConfig();

    }


    public function seoConfig()

    {

        $this->seo()->setTitle('مدیریت مباحث ' . $this->chapter->name);

    }


    public function rules()

    {

        return [

            'name' => 'required|string|max:200',

            'order' => 'nullable|integer|min:0',

            'is_active' => 'boolean',

        ];

    }


    public function messages()

    {

        return [

            'name.required' => 'نام مبحث الزامی است.',

            'name.string' => 'نام مبحث باید متن باشد.',

            'name.max' => 'نام مبحث نباید بیشتر از ۲۰۰ کاراکتر باشد.',

            'order.integer' => 'ترتیب باید عدد باشد.',

            'order.min' => 'ترتیب نمی‌تواند منفی باشد.',

        ];

    }


    public function submit()

    {

        $this->validate();


        $data = [

            'cc_chapter_id' => $this->chapter->id,

            'name' => $this->name,

            'order' => $this->order ?? 0,

            'is_active' => $this->is_active,

        ];


        if ($this->editingId) {

            $topic = CcTopic::findOrFail($this->editingId);

            $topic->update($data);

            $this->dispatch('success', 'مبحث با موفقیت ویرایش شد.');

        } else {

            CcTopic::create($data);

            $this->dispatch('success', 'مبحث با موفقیت ایجاد شد.');

        }


        $this->resetForm();

    }


    public function edit($id)

    {

        $topic = CcTopic::findOrFail($id);

        $this->editingId = $topic->id;

        $this->name = $topic->name;

        $this->order = $topic->order;

        $this->is_active = $topic->is_active;

    }


    public function delete($id)

    {

        $topic = CcTopic::findOrFail($id);


        if ($topic->classifications()->exists()) {

            $this->dispatch('warning', 'این مبحث دارای طبقه‌بندی است و نمی‌توان آن را حذف کرد.');

            return;

        }


        $topic->delete();

        $this->dispatch('success', 'مبحث با موفقیت حذف شد.');

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

        $topics = CcTopic::query()
            ->where('cc_chapter_id', $this->chapter->id)
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('order')
            ->paginate(15);


        return view('livewire.manager.classification.topics', [

            'topics' => $topics,

        ])->layout('layouts.manager.app');

    }

}
