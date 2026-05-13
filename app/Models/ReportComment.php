<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ReportComment extends Model

{

    use HasFactory;

    protected $guarded = [];


    protected $casts = [

        'commented_at' => 'datetime',

        'replied_at' => 'datetime',

    ];


    public function dailyReport(): BelongsTo

    {

        return $this->belongsTo(DailyReport::class, 'report_id');

    }

}


