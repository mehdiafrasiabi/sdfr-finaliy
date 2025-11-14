<?php

namespace App\Livewire\Manager\Product;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SeoItem;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination,SEOTools;
    public $search = '';


    public function mount()
    {
        $this->seoConfig();
    }
    public function seoConfig()
    {
        $this->seo()
            ->setTitle('محصولات');
    }
    public function delete(Product $product)
    {
        DB::transaction(function () use ($product) {
            $product->delete();
            ProductImage::query()->where('product_id', $product->id)->delete();
            SeoItem::query()->where('ref_id', $product->id)->delete();
            File::deleteDirectory('products/' . $product->id);
            $this->dispatch('success','با موفقیت حذف شد');
        });
    }
    public function render()
    {
        $products = Product::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('p_code', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->with('category', 'coverImage')
            ->paginate(10);

        return view('livewire.manager.product.index', [
            'products' => $products
        ])->layout('layouts.manager.app');
    }
}
