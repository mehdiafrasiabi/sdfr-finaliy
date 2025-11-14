<?php

namespace App\Livewire\Manager\Product;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads, SEOTools;
    public $has_supporter = false;
    public $has_advisor = false;
    public $photos = [];
    public $categories = [];
    public $coverIndex = 0;

    public $name;
    public $slug;
    public $productId;
    public $product;

    // فیلدهای جدید
    public $type; // نوع دوره
    public $duration_days; // مدت دوره به روز
    public $p_code; // کد محصول

    public function mount()
    {
        if (request()->has('product_id')) {
            $this->productId = request('product_id');
            $this->product = Product::query()
                ->with('seo','image')
                ->findOrFail($this->productId);

            $this->name = $this->product->name;
            $this->slug = $this->product->seo->slug;
            $this->type = $this->product->type;
            $this->duration_days = $this->product->duration_days;
            $this->p_code = $this->product->p_code;
        }

        $this->categories = Category::all();
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()->setTitle('افزودن محصول');
    }

    public function updatedName()
    {
        $this->slug = Str::slug($this->name, '-', null);
    }

    public function submit($formData, Product $product)
    {
        // فیلدهای جدید رو به داده‌های فرم اضافه کن
        $formData['photos'] = $this->photos;
        $formData['coverIndex'] = $this->coverIndex;
        $formData['type'] = $this->type;
        $formData['duration_days'] = $this->duration_days;
        $formData['p_code'] = $this->p_code;

        if (isset($formData['has_supporter'])) {
            $formData['has_supporter'] = true;
        } else {
            $formData['has_supporter'] = false;
        }

        if (isset($formData['has_advisor'])) {
            $formData['has_advisor'] = true;
        } else {
            $formData['has_advisor'] = false;
        }
        $validator = Validator::make($formData, [
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp',
            'name' => 'required|string',
            'title' => 'required|string|max:45',
            'course_time' => 'required|string|max:45',
            'meeting_time' => 'required|string|max:45',
            'tag' => 'required|string',
            'slug' => 'required|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'price' => 'required|integer',
            'categoryId' => 'required|exists:categories,id',
            'coverIndex' => 'required',
            'has_supporter' => 'boolean',
            'has_advisor' => 'boolean',

            // ولیدیشن جدید
            'type' => 'required|in:weekly,monthly,yearly_online,yearly_offline',
            'duration_days' => 'required|integer|min:1',
            'p_code' => 'nullable|string|max:255|unique:products,p_code,' . $this->productId
        ], [
            'coverIndex.required' => 'یکی از تصاویر را به عنوان کاور محصول انتخاب کنید.',
            '*.required' => 'فیلد ضروری است.',
            '*.string' => 'فرمت اشتباه است!',
            '*.integer' => 'این فیلد باید از نوع عددی باشد!',
            '*.min' => 'حداقل تعداد کاراکترها : 50',
            '*.max' => 'حداکثر تعداد کاراکترها : 45',
            'categoryId.exists' => 'دسته بندی نامعتبر است.',
            'photos.*.image' => 'فرمت نامعتبر است.',
            'type.required' => 'نوع دوره الزامی است.',
            'duration_days.required' => 'مدت دوره الزامی است.',
            'p_code.unique' => 'کد محصول تکراری است.',
            'has_supporter.boolean' => 'مقدار پشتیبان باید درست باشد.',
            'has_advisor.boolean' => 'مقدار مشاور باید درست باشد.',

        ]);

        $validator->validate();
        $this->resetValidation();

        // ذخیره با متد submit مدل Product (مثل قبل)
        $product->submit($formData, $this->productId, $this->photos, $this->coverIndex);

        $this->redirect(route('manager.product.index'));
        session()->flash('success', 'عملیات با موفقیت انجام شد.');
    }

    public function setCoverImage($index)
    {
        $this->coverIndex = $index;
    }

    public function removePhoto($index)
    {
        if ($index === $this->coverIndex) {
            $this->coverIndex = null;
        }
        array_splice($this->photos, $index, 1);
    }

    public function removeOldPhoto(ProductImage $productImage, $productId)
    {
        $productImage->delete();
        \Illuminate\Support\Facades\File::delete(public_path('products/' . $productId . '/photo/' . $productImage->path));
    }

    public function setOldCoverImage($photoId)
    {
        ProductImage::query()->where([
            'product_id' => $this->productId,
            'id' => $photoId
        ])->update(['is_cover' => true]);
        $this->dispatch('success', 'تصویر کاور با موفقیت تغییر کرد.');
    }

    public function render()
    {
        return view('livewire.manager.product.create')->layout('layouts.manager.app');
    }
}
