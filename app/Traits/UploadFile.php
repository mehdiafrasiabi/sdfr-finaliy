<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;


trait UploadFile
{

    protected function uploadImageInWebpFormat($photo, $productId, $width, $height, $folder)
    {
        $path = public_path('products/' . $productId . '/' . $folder);

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $manager = new ImageManager(new Driver());

        $manager->read($photo->getRealPath())
            ->scale($width, $height)
            ->toWebp()
            ->save($path . '/' . pathinfo($photo->hashName(), PATHINFO_FILENAME) . '.webp');


    }
    protected function uploadImageInWebpFormatSdfrStudent($photo, $id, $width = null, $height = null, $folder = 'default')
    {
        $path = public_path($folder . '/' . $id);

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $manager = new ImageManager(new Driver());

        $image = $manager->read($photo->getRealPath());

        // تغییر سایز فقط اگر width/height داده شده باشه
        if ($width && $height) {
            $image->scale($width, $height);
        }

        $filename = pathinfo($photo->hashName(), PATHINFO_FILENAME) . '.webp';
        $image->toWebp(85)->save($path . '/' . $filename);

        return $filename;
    }
    protected function uploadImageInWebpFormatSdfrSchool($photo, $id, $width = null, $height = null, $folder = 'default')
    {
        $path = public_path($folder . '/' . $id);

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $manager = new ImageManager(new Driver());

        $image = $manager->read($photo->getRealPath());

        // تغییر سایز فقط اگر width/height داده شده باشه
        if ($width && $height) {
            $image->scale($width, $height);
        }

        $filename = pathinfo($photo->hashName(), PATHINFO_FILENAME) . '.webp';
        $image->toWebp(85)->save($path . '/' . $filename);

        return $filename;
    }

protected function uploadImageInWebpFormatExamAnalisis($photo, $studentId, $width, $height, $folder)
{
    // اگر آرایه پاس شده بود اولین آیتم را بگیر
    if (is_array($photo)) {
        $photo = reset($photo);
    }

    $isTemporary = $photo instanceof \Livewire\TemporaryUploadedFile;
    $isUploaded = $photo instanceof \Illuminate\Http\UploadedFile;
    $isStringPath = is_string($photo) && file_exists($photo);

    if (! $isTemporary && ! $isUploaded && ! $isStringPath) {
        \Log::error('uploadImageInWebpFormatExamAnalisis: invalid $photo type', [
            'type' => is_object($photo) ? get_class($photo) : gettype($photo)
        ]);
        return null;
    }

    // مسیر نسبی و کامل برای ذخیره در public
    $relativeDir = "exam/students/{$studentId}/{$folder}";
    $fullDir = public_path($relativeDir);

    if (! file_exists($fullDir)) {
        mkdir($fullDir, 0755, true);
    }

    // ✅ نسخه جدید ImageManager (سازگار با Intervention Image v3)
    if (extension_loaded('imagick')) {
        $manager = new ImageManager(new ImagickDriver());
    } else {
        $manager = new ImageManager(new Driver());
    }

    // مسیر منبع و نام اصلی فایل
    if ($isTemporary || $isUploaded) {
        $sourcePath = $photo->getRealPath();
        $originalName = method_exists($photo, 'getClientOriginalName') ? $photo->getClientOriginalName() : $photo->getFilename();
    } else {
        $sourcePath = $photo;
        $originalName = basename($photo);
    }

    // ساخت نام فایل امن
    $base = pathinfo($originalName, PATHINFO_FILENAME);
    $base = preg_replace('/[^A-Za-z0-9\-_]/', '-', $base);
    $fileName = uniqid() . '-' . $base . '.webp';
    $fullPath = $fullDir . '/' . $fileName;

    // پردازش تصویر و ذخیره در فرمت webp
    $image = $manager->read($sourcePath)
        ->scaleDown($width, $height) // متد جدید معادل fit()
        ->toWebp(85);

    file_put_contents($fullPath, (string) $image);

    return $relativeDir . '/' . $fileName;
}



    protected function uploadImageInWebpFormatBlog($photo, $productId, $width, $height, $folder)
    {
        $path = public_path('blogs/' . $productId . '/' . $folder);
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        $manager = new ImageManager(new Driver());
        $manager->read($photo->getRealPath())
            ->cover($width, $height)
            ->toWebp()
            ->save($path . '/' . pathinfo($photo->hashName(), PATHINFO_FILENAME) . '.webp');
    }
    protected function uploadImageInWebpFormatExamQuestion($photo, $itemId, $width, $height, $folder = 'images')
    {
        $path = public_path("blog/example-question/{$itemId}/{$folder}");
        File::ensureDirectoryExists($path);

        $manager = new ImageManager(new Driver());

        $manager->read($photo->getRealPath())
            ->cover($width, $height)
            ->toWebp(90)
            ->save("{$path}/" . pathinfo($photo->hashName(), PATHINFO_FILENAME) . '.webp');
    }
    protected function uploadImageInWebpFormatProfile($photo, $userId, $width, $height, $folder, $filename = null)
    {
        $path = public_path("user/$folder/$userId");

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        // اگر نام خاصی داده نشده، یک نام هش شده بساز
        if (!$filename) {
            $filename = sha1($photo->getClientOriginalName() . now(). uniqid());
        }

        $finalPath = $path . '/' . $filename . '.webp';

        $manager = new ImageManager(new Driver());
        $manager->read($photo->getRealPath())
            ->scale($width, $height)
            ->toWebp(80)
            ->save($finalPath);

        // حذف فایل temp livewire
        if (file_exists($photo->getRealPath())) {
            unlink($photo->getRealPath());
        }

        return $filename . '.webp'; // فقط نام فایل
    }
    protected function uploadImageInWebpFormatProfileReport($photo, $studentId, $width, $height, $folder)
    {
        if (!$photo || !$photo->isValid()) {
            throw new \Exception("فایل معتبر نیست یا ارسال نشده.");
        }

        $extension = strtolower($photo->getClientOriginalExtension());
        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
            throw new \Exception("فرمت فایل مجاز نیست.");
        }

        $path = public_path("students/$folder/$studentId");

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        // تولید نام هش شده منحصربه‌فرد
        $filename = sha1($photo->getClientOriginalName() . now() . uniqid());

        $finalPath = $path . '/' . $filename . '.webp';

        // تبدیل به WebP و ذخیره
        $manager = new ImageManager(new Driver());
        $manager->read($photo->getRealPath())
            ->scale($width, $height)
            ->toWebp(80)
            ->save($finalPath);

        // حذف فایل temp livewire
        if (file_exists($photo->getRealPath())) {
            unlink($photo->getRealPath());
        }

        return "students/$folder/$studentId/" . $filename . '.webp';
    }



}
