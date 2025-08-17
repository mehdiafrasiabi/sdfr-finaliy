<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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
    protected function uploadImageInWebpFormatExamAnalisis($photo, $studentId, $width, $height, $folder)
    {
        $path = public_path('exams/students/' . $studentId . '/' . $folder);

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $manager = new ImageManager(new Driver());

        $fileName = pathinfo($photo->hashName(), PATHINFO_FILENAME) . '.webp';

        $manager->read($photo->getRealPath())
            ->cover($width, $height)
            ->toWebp()
            ->save($path . '/' . $fileName);

        // مسیر قابل دسترسی از public
        return 'exams/students/' . $studentId . '/' . $folder . '/' . $fileName;
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
