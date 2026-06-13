<?php

namespace App\Livewire\Concerns;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Builder;

/**
 * محدودسازی کوئری دانش‌آموزان بر اساس نقش ادمین لاگین‌شده:
 *  - «مدیر مدرسه» → فقط دانش‌آموزان مدرسه‌ی خودش
 *  - سایر نقش‌ها  → دانش‌آموزانی که مشاورشان (advisor_id) همین ادمین است
 */
trait ScopesStudentsForAdmin
{
    protected function scopeStudentsForCurrentAdmin(Builder $query): Builder
    {
        /** @var Admin|null $admin */
        $admin = auth('admin')->user();

        if ($admin && $admin->isSchoolManager()) {
            return $query->where('students.school_id', $admin->school_id);
        }

        return $query->where('advisor_id', $admin?->id ?? auth()->id());
    }
}
