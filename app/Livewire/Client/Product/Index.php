<?php

namespace App\Livewire\Client\Product;

use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class Index extends Component
{
    use SEOTools;

    public $product;
    public $price;
    public $productId;
    public $inCart = false;
    public $hasPurchased = false;
    public $search = '';

    public function mount($p_code)
    {
        $this->product = Product::query()
            ->with('image', 'coverImage', 'seo')
            ->select('id', 'name', 'title', 'tag', 'price', 'course_time', 'meeting_time', 'description', 'category_id', 'p_code')
            ->where('p_code', $p_code)
            ->firstOrFail();

        $this->seoConfig($this->product->seo);

        $this->productId = $this->product->id;

        $this->inCart = Cart::query()
            ->where('product_id', $this->productId)
            ->where('user_id', auth()->id())
            ->exists();

        // چک با پرداخت موفق
        $this->hasPurchased = Payment::query()
            ->where('user_id', auth()->id())
            ->where('status', 'completed')
            ->whereHas('order.orderItems', function ($query) {
                $query->where('product_id', $this->productId);
            })->exists();
    }


    public function addToCart()
    {
        Cart::query()->create([
            'product_id' => $this->productId, // مقدار را از `productId` بگیر
            'user_id' => auth()->id(),
        ]);

        $this->inCart = true;

        $this->dispatch('add-to-cart', productId: $this->productId);
        return $this->redirect(route('client.checkout.cart'), navigate: true);
    }


    public function seoConfig($productSeoItem)
    {
        $this->seo()
            ->setTitle($productSeoItem->meta_title)
            ->setDescription($productSeoItem->meta_description);

    }

    public function render()
    {
        $productsQuery = Product::query()
            ->select('id', 'name', 'title', 'tag', 'price', 'course_time', 'meeting_time', 'description', 'category_id', 'p_code')
            ->with('image', 'coverImage', 'seo');

// اگر سرچ فعال بود، فیلتر کن بر اساس name و title
        if (!empty($this->search)) {
            $productsQuery->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('title', 'like', '%' . $this->search . '%');
            });
        }

        $products = $productsQuery->latest()->paginate(10); // اگه نیاز به صفحه‌بندی داری

        return view('livewire.client.product.index', [
            'products' => $products
        ])->layout('layouts.client.app');

    }
}
