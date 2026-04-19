<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileHelper
{
    /**
     * آپلود امن فایل در public_html
     *
     * @param  mixed   $file         فایل آپلودی (مثلاً $this->barnameh)
     * @param  string  $directory    مسیر نسبی داخل public_html (مثلاً student/hashedId/plan)
     * @param  bool    $hashFilename آیا نام فایل هش شود یا خیر
     * @return string|null مسیر فایل ذخیره‌شده نسبت به public_html
     */
    public static function uploadToPublicHtml($file, string $directory, bool $hashFilename = true): ?string
    {
        if (!$file) {
            return null;
        }

        // اگه مسیر وجود نداره، بسازش
        $path = base_path('public_html/' . $directory);
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        // تولید نام امن برای فایل
        $extension = $file->getClientOriginalExtension();
        $filename = $hashFilename
            ? Str::random(40).'.' . $extension
            : $file->getClientOriginalName();

        // ذخیره فایل در public_html
        $file->storeAs($directory, $filename, 'public_html');
        // پاک‌سازی فایل موقت از livewire-tmp / storage
        $realPath = method_exists($file, 'getRealPath') ? $file->getRealPath() : null;
        if ($realPath && file_exists($realPath)) {
            @unlink($realPath);
        }
        return "{$directory}/{$filename}";
    }
    public static function publicUrl(string $relativePath): string
    {
        // حذف / از ابتدا (اگه اشتباهی داده شده)
        $relativePath = ltrim($relativePath, '/');

        // تولید لینک نهایی
        return url($relativePath);
    }

}
