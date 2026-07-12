<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TypedExamAnalysisUpload extends Model
{
    use HasFactory;

    protected $guarded = [];



    /**

     * تلاش آزمون مرتبط

     */

    public function attempt(): BelongsTo

    {

        return $this->belongsTo(TypedExamAttempt::class, 'attempt_id');

    }



    /**

     * مسیر کامل فایل

     */

    public function getFullPathAttribute(): string

    {
        $publicPath = public_path($this->file_path);

        if (file_exists($publicPath)) {
            return $publicPath;
        }

        return storage_path('app/public/' . ltrim($this->file_path, '/'));

    }



    /**

     * URL فایل

     */

    public function getUrlAttribute(): string

    {
        $publicPath = public_path($this->file_path);

        if (file_exists($publicPath)) {
            return asset($this->file_path);
        }

        return asset('storage/' . ltrim($this->file_path, '/'));

    }



    /**

     * سایز فایل فرمت شده

     */

    public function getFormattedSizeAttribute(): string

    {

        $bytes = $this->file_size;

        $units = ['B', 'KB', 'MB', 'GB'];

        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {

            $bytes /= 1024;

            $i++;

        }

        return round($bytes, 2) . ' ' . $units[$i];

    }
}
