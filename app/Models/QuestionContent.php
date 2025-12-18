<?php


namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionContent extends Model
{
    protected $guarded = [];
    /**
     * سوال مرتبط
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
    /**
     * مسیر کامل عکس سوال
     */
    public function getQuestionImageUrlAttribute(): ?string
    {
        if (!$this->question_image || !$this->folder_hash) {
            return null;
        }
        return asset("questions/{$this->folder_hash}/{$this->question_image}");
    }

    /**
     * مسیر کامل عکس پاسخ تشریحی
     */

    public function getExplanationImageUrlAttribute(): ?string
    {
        if (!$this->explanation_image || !$this->folder_hash) {
            return null;
        }
        return asset("questions/{$this->folder_hash}/{$this->explanation_image}");
    }
    /**
     * بررسی وجود عکس سوال
     */

    public function hasQuestionImage(): bool
    {
        return !empty($this->question_image) && !empty($this->folder_hash);
    }

    /**
     * بررسی وجود عکس پاسخ تشریحی
     */
    public function hasExplanationImage(): bool
    {
        return !empty($this->explanation_image) && !empty($this->folder_hash);
    }
}
