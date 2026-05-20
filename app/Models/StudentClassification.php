<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentClassification extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Rating mapping: 1=D (weakest) … 4=A (strongest)
     */
    public const RATINGS = [
        1 => 'D',
        2 => 'C',
        3 => 'B',
        4 => 'A',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(ClassificationProject::class, 'classification_project_id');
    }

    public function ratable()
    {
        return $this->morphTo();
    }

    public function getRatingLabelAttribute()
    {
        return self::RATINGS[$this->rating] ?? 'نامشخص';
    }

    public function getRatingColorAttribute()
    {
        return match ((int) $this->rating) {
            4 => 'success',
            3 => 'info',
            2 => 'warning',
            1 => 'danger',
            default => 'secondary',
        };
    }

    public static function getRatingOptions(): array
    {
        $out = [];
        foreach (self::RATINGS as $value => $label) {
            $out[] = ['value' => $value, 'label' => $label];
        }
        return $out;
    }
}
