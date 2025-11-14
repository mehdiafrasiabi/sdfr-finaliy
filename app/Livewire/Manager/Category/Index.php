<?php

namespace App\Livewire\Manager\Category;

use App\Models\Category;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination,SEOTools;
    public $search = '';
    public $name;
    public $categories = [];
    public $categoryId;
    public $parentId2;

    public function mount()
    {
        $this->categories = Category::all();
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()
            ->setTitle('دسته بندی ها');
    }

    public function submit($formData, Category $category)
    {
        $validator = Validator::make($formData, [
            'name' => 'required|string|max:55',
            'parentId' => 'nullable|exists:categories,id',
        ], [
            '*.required' => 'فیلد ضروری است',
            '*.string' => 'فرمت نوشتاری شما اشتباه است ',
            '*.max' => 'حداکثر نوشتن : 55 کارکتر',
            'parentId.exists' => 'دسته بندی نامعتبر است',
        ]);
        $validator->validate();
        $category->submit($formData, $this->categoryId);
        $this->reset();
        $this->dispatch('success', 'عملیات با موفقیت انجام شد');
    }

    public function edit($category_id)
    {
        $category = Category::query()->where('id', $category_id)->first();
        if ($category) {
            $this->name = $category->name;
            $this->categoryId = $category->id;
            $this->parentId2 = $category->category_id;

        }
    }

    public function delete($categoryId)
    {
        $category = Category::query()->where('id', $categoryId)->first();

        if ($category->children()->exists()) {
            $this->dispatch('warning', 'این دسته‌بندی دارای زیرشاخه است و نمی‌توان آن را حذف کرد!');
            return;
        }
        $category->delete();
        $this->dispatch('success', 'عملیات حذف با موفقیت انجام شد!');
    }


    public function render()
    {
        $categories = Category::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);
        return view('livewire.manager.category.index',[ 'allCategories' => $categories])->layout('layouts.manager.app');
    }
}
