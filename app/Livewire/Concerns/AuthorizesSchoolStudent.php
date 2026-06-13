<?php

namespace App\Livewire\Concerns;

use App\Models\Admin;

/**
 * جلوگیری از دسترسی «مدیر مدرسه» به دانش‌آموزانِ مدرسه‌های دیگر.
 * برای سایر نقش‌ها هیچ محدودیتی اعمال نمی‌شود.
 */
trait AuthorizesSchoolStudent
{
    protected function authorizeSchoolStudent(?int $schoolId): void
    {
        /** @var Admin|null $admin */
        $admin = auth('admin')->user();

        if ($admin && $admin->isSchoolManager() && (int) $schoolId !== (int) $admin->school_id) {
            abort(403, 'شما به این دانش‌آموز دسترسی ندارید.');
        }
    }
}
