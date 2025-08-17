<?php

namespace App\Models;

use App\Models\ProductImage;
use App\Models\SeoItem;
use App\Traits\UploadFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;



class Product extends Model
{
    //
    use HasFactory, softDeletes ,UploadFile;

    protected $guarded = [];
    public function submit($formData, $productId, $photos, $coverIndex)
    {
        DB::transaction(function () use ($formData, $productId, $photos, $coverIndex) {
            $product = $this->submitToProduct($formData, $productId);
            $this->submitToSeoItem($formData, $product->id);
            $this->submitToProductImage($photos, $product->id, $coverIndex);
            $this->saveImages($photos, $product->id);
        });
    }
    public function submitToProduct($formData, $productId)
    {

        return Product::query()->updateOrCreate(

            [
                'id' => $productId
            ],
            [
                'name' => $formData['name'],
                'price' => $formData['price'],
                'title' => $formData['title'],
                'tag' => $formData['tag'],
                'course_time' => $formData['course_time'],
                'meeting_time' => $formData['meeting_time'],
                'category_id' => $formData['categoryId'],
                'has_supporter' => $formData['has_supporter'],
                'has_advisor' => $formData['has_advisor'],
                'p_code' => config('app.name') . '-' . $this->generateProductCode(),
            ]
        );
    }
    public function submitToSeoItem($formData, $productId)
    {
        SeoItem::query()->updateOrCreate(
            [
                'ref_id' => $productId
            ],
            [
                'slug' => $formData['slug'],
                'meta_title' => $formData['meta_title'],
                'meta_description' => $formData['meta_description'],
            ]
        );

    }
    public function submitToProductImage($photos, $productId, $coverIndex)
    {
        ProductImage::query()->where('product_id', $productId)->update(['is_cover' => false]);

        foreach ($photos as $index => $photo) {

            $path = pathinfo($photo->hashName(), PATHINFO_FILENAME) . '.webp';

            ProductImage::query()->create(
                [
                    'path' => $path,
                    'product_id' => $productId,
                    'is_cover' => $index == $coverIndex,
                ]
            );
        }

    }
    public function saveImages($photos, $productId)
    {
        foreach ($photos as $photo) {
            $this->uploadImageInWebpFormat($photo, $productId, 1200, 675, 'photo');
            $photo->delete();

        }
    }
    public function generateProductCode()
    {
        do {
            $randomCode = rand(1000, 100000);
            $checkCode = Product::query()->where('p_code', $randomCode)->first();

        } while ($checkCode);
        return $randomCode;
    }
    public function removeOldPhoto($productImage, $productId)
    {

        $productImage->delete();
        \Illuminate\Support\Facades\File::delete(public_path('products/' . $productId . '/photo/' . $productImage->path));
    }

    public function setCoverOldImage($photoId, $productId)
    {

        ProductImage::query()->where('product_id', $productId)->update(['is_cover' => false]);
        ProductImage::query()->where([
            'product_id' => $productId,
            'id' => $photoId,
        ])->update(['is_cover' => true]);


    }
    public function submitProductContent($formData, $productId)
    {
        Product::query()->where('id', $productId)->update([
            'description' => $formData['description'],
        ]);
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function coverImage()
    {
        return $this->belongsTo(ProductImage::class, 'id', 'product_id')->where('is_cover', '=', true);
    }

    public function image()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function seo()
    {
        return $this->belongsTo(SeoItem::class, 'id', 'ref_id');
    }
    public function features()
    {
        return $this->hasMany(ProductFeature::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
