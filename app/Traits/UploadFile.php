<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

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

    protected function uploadImageInWebpFormatQuestion($photo, $id, $width, $height, $folder)
    {
        // برای questions از مسیر questions استفاده می‌کنیم
        $path = public_path('questions/' . $id . '/' . $folder);

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $manager = new ImageManager(new Driver());

        $image = $manager->read($photo->getRealPath());

        // اگر width و height تعیین شده باشد
        if ($width && $height) {
            $image->scale($width, $height);
        } else {
            // محدود کردن سایز به 1200 پیکسل
            $image->scaleDown(1200, 1200);
        }

        $image->toWebp(85)
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

        $isTemporary = $photo instanceof TemporaryUploadedFile;
        $isUploaded = $photo instanceof UploadedFile;
        $isStringPath = is_string($photo) && file_exists($photo);

        if (!$isTemporary && !$isUploaded && !$isStringPath) {
            Log::error('uploadImageInWebpFormatExamAnalisis: invalid $photo type', [
                'type' => is_object($photo) ? get_class($photo) : gettype($photo),
            ]);

            return null;
        }

        // مسیر نسبی برای public (هرجور دوست داری این را تغییر بده)
        // مثال: exam/students/{studentId}/{folder}
        $relativeDir = "exam/students/{$studentId}/{$folder}";
        $fullDir = public_path($relativeDir);

        if (!file_exists($fullDir)) {
            mkdir($fullDir, 0755, true);
        }

        // ImageManager نسخه جدید (Intervention Image v3)
        if (extension_loaded('imagick')) {
            $manager = new ImageManager(new ImagickDriver());
        } else {
            $manager = new ImageManager(new Driver());
        }

        // مسیر منبع و نام اصلی فایل
        if ($isTemporary || $isUploaded) {
            $sourcePath = $photo->getRealPath();
            $originalName = method_exists($photo, 'getClientOriginalName')
                ? $photo->getClientOriginalName()
                : $photo->getFilename();
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

        file_put_contents($fullPath, (string)$image);

        // مسیر نسبی برای ذخیره در دیتابیس
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
            $filename = sha1($photo->getClientOriginalName() . now() . uniqid());
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


    /**
     * آپلود عکس سوالات با hash کردن نام فایل و فولدر
     * عرض پیشنهادی: 1200px - ارتفاع: متناسب با تصویر
     *
     * @param mixed $photo فایل آپلود شده
     * @param string $type نوع (question یا explanation)
     * @return array ['filename' => '...', 'folder' => '...']
     */
    protected function uploadQuestionImage($photo, string $type = 'question'): array

    {
        if (!$photo || !$photo->isValid()) {
            throw new \Exception("فایل معتبر نیست یا ارسال نشده.");
        }

        $extension = strtolower($photo->getClientOriginalExtension());
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp'];
        if (!in_array($extension, $allowedExtensions)) {
            throw new \Exception("فرمت فایل مجاز نیست. فرمت‌های مجاز: " . implode(', ', $allowedExtensions));
        }
        // ایجاد hash برای فولدر
        $folderHash = substr(sha1(uniqid() . now() . rand(1000, 9999)), 0, 16);
        // مسیر ذخیر
        $basePath = 'questions/' . $folderHash;
        $fullPath = public_path($basePath);
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }
        // ایجاد نام فایل hash شده
        $filename = sha1($photo->getClientOriginalName() . now() . uniqid()) . '.webp';
        $manager = new ImageManager(new Driver());

        // خواندن تصویر
        $image = $manager->read($photo->getRealPath());

        // محدود کردن عرض به 1200 پیکسل با حفظ نسبت
        $image->scaleDown(1200, null);

        // ذخیره با کیفیت 85%
        $image->toWebp(85)->save($fullPath . '/' . $filename);

        // حذف فایل temp livewire
        if (file_exists($photo->getRealPath())) {
            @unlink($photo->getRealPath());
        }

        return [
            'filename' => $filename,
            'folder' => $folderHash,
        ];
    }


    /**
     * حذف عکس سوال
     */
    protected function deleteQuestionImage(string $folder, string $filename): bool
    {
        $path = public_path('questions/' . $folder . '/' . $filename);
        if (file_exists($path)) {
            @unlink($path);
            // اگر فولدر خالی شد، آن را هم حذف کن
            $folderPath = public_path('questions/' . $folder);
            if (is_dir($folderPath) && count(glob($folderPath . '/*')) === 0) {
                @rmdir($folderPath);
            }
            return true;
        }

        return false;
    }

    /**
     * دریافت URL کامل عکس سوال
     */
    protected function getQuestionImageUrl(?string $folder, ?string $filename): ?string
    {
        if (!$folder || !$filename) {
            return null;
        }
        return asset('questions/' . $folder . '/' . $filename);
    }
}
