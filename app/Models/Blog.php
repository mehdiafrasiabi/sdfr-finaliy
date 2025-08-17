<?php

namespace App\Models;

use App\Models\BlogSeoItem;
use App\Traits\UploadFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Blog extends Model
{
    use HasFactory, SoftDeletes, UploadFile;

    protected $guarded = [];

    public function submit($formData, $blogId, $photos, $coverIndex)
    {
        DB::transaction(function () use ($formData, $blogId, $photos, $coverIndex) {
            $blog = $this->submitToBlog($formData, $blogId);
            $this->submitToSeoItem($formData, $blog->id);
            $this->submitToBlogImage($photos, $blog->id, $coverIndex);
            $this->saveImages($photos, $blog->id);
        });
    }

    public function submitToBlog($formData, $blogId)
    {
        return Blog::query()->updateOrCreate(
            ['id' => $blogId],
            [
                'title'       => $formData['title'],
                'description' => $formData['description'],
                'study_time'  => $formData['study_time'],
                'category_id' => $formData['category_id'],
                'status'      => 'pending', // بعد از ثبت، منتظر تایید ادمین
                'blog_code'   => config('app.name') . '-' . $this->generateBlogCode(),
            ]
        );
    }

    public function submitToSeoItem($formData, $blogId)
    {
        BlogSeoItem::query()->updateOrCreate(
            ['ref_id' => $blogId],
            [
                'slug'             => $formData['slug'],
                'meta_title'       => $formData['meta_title'],
                'meta_description' => $formData['meta_description'],
            ]
        );
    }

    public function submitToBlogImage($photos, $blogId, $coverIndex)
    {
        foreach ($photos as  $photo) {
            $path = pathinfo($photo->hashName(), PATHINFO_FILENAME) . '.webp';

            BlogImage::query()->create([
                'path'     => $path,
                'blog_id'  => $blogId,
            ]);
        }
    }

    public function saveImages($photos, $blogId)
    {
        foreach ($photos as $photo) {
            // تبدیل به webp و ذخیره در public/blogs/{id}/photo
            $this->uploadImageInWebpFormatBlog($photo, $blogId, 1200, 675, 'photo');
            // پاک شدن فایل temp بعد از ذخیره
            $photo->delete();
        }
    }

    public function generateBlogCode()
    {
        do {
            $randomCode = rand(1000, 100000);
            $checkCode  = Blog::query()->where('blog_code', $randomCode)->first();
        } while ($checkCode);

        return $randomCode;
    }

    // روابط
    public function seo()
    {
        return $this->belongsTo(BlogSeoItem::class, 'id', 'ref_id');
    }

    public function images()
    {
        return $this->hasMany(BlogImage::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
