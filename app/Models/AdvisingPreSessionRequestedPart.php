<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvisingPreSessionRequestedPart extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function preSession(): BelongsTo
    {
        return $this->belongsTo(AdvisingPreSession::class, 'pre_session_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(CcSubject::class, 'cc_subject_id');
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(CcChapter::class, 'cc_chapter_id');
    }
}
