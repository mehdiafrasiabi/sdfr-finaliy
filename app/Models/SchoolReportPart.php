<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolReportPart extends Model
{
    protected $guarded = [];

    public function report()
    {
        return $this->belongsTo(SchoolReport::class, 'school_report_id');
    }

    public function subject()
    {
        return $this->belongsTo(CcSubject::class, 'cc_subject_id');
    }

    public function chapter()
    {
        return $this->belongsTo(CcChapter::class, 'cc_chapter_id');
    }
}
