<?php

namespace App\Livewire\Manager\Product;

use App\Models\Product;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class Content extends Component
{
    use SEOTools;
    public $productName;
    public $description;
    public $productId;

    public function mount(Product $product)
    {
        $this->productName = $product->name;
        $this->productId = $product->id;
        $this->description = $product->description;
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()
            ->setTitle('محتوای محصول '.($this->productName));
    }

    public function submit($formData , Product $product)
    {
        $formData['description'] = $this->description;

        $validator = Validator::make($formData, [
            'description' => 'required|string',
        ], [
            '*.required' => 'فیلد ضروری است.',
            '*.string' => 'فرمت اشتباه است !',
        ]);

        $validator->validate();
        $this->resetValidation();
        $product->submitProductContent($formData,$this->productId);
        $this->redirect(route('manager.product.index'));
        session()->flash('success', 'محتوای محصول با موفقیت ثبت شد');

    }
    public function render()
    {
        return view('livewire.manager.product.content')->layout('layouts.manager.app');
    }
}
