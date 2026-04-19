<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EssayExamAnswerUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'attempt_id',
        'file_path',
        'file_size',
        'sort_order',
    ];

    protected $casts = [
        'file_size'  => 'integer',
        'sort_order' => 'integer',
    ];

    public function attempt()
    {
        return $this->belongsTo(EssayExamAttempt::class, 'attempt_id');
    }

    public function getUrlAttribute(): string
    {
        return rtrim(config('app.url'), '/') . '/' . ltrim($this->file_path, '/');
    }
}

