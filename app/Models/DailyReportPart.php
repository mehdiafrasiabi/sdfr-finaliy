<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class DailyReportPart extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_read' => 'boolean',
        'is_compensatory' => 'boolean',
    ];
    public function dailyReport(): BelongsTo
    {
        return $this->belongsTo(DailyReport::class);
    }
    public function programPart(): BelongsTo
    {
        return $this->belongsTo(ProgramPart::class);
    }
}
