<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TypedExamAnalysisUpload extends Model
{
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

        return storage_path('app/public/' . $this->file_path);

    }



    /**

     * URL فایل

     */

    public function getUrlAttribute(): string

    {

        return asset('storage/' . $this->file_path);

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
