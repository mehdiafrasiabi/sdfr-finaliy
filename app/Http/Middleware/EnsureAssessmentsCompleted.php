<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * گِیت آزمون‌های روان‌شناختی برای «همهٔ» کاربران احرازشده:
 * تا وقتی دانش‌آموز همهٔ آزمون‌های روان‌شناختیِ فعال را تکمیل نکرده باشد، فقط
 * مسیرهای مربوط به تکمیل آزمون‌ها (و logout) قابل دسترسی هستند و بقیه به لیست
 * آزمون‌ها هدایت می‌شوند. این تضمین می‌کند هیچ‌کس بدون پر کردن تست به صفحهٔ
 * خرید/آزمایشی نرسد؛ پس از اتمام تست، صفحهٔ انتخاب مسیر نمایش داده می‌شود.
 */
class EnsureAssessmentsCompleted
{
    protected const ALLOWED_ROUTES = [
        'client.profile.assessment.list',
        'client.profile.assessment.take',
        'client.logout',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (! $user) {
            return $next($request);
        }

        // اگر همهٔ آزمون‌ها تکمیل شده‌اند (یا آزمونی تعریف نشده) عبور بده.
        if ($user->hasCompletedAllAssessments()) {
            return $next($request);
        }

        $name = $request->route()?->getName();
        if ($name && in_array($name, self::ALLOWED_ROUTES, true)) {
            return $next($request);
        }

        return redirect()
            ->route('client.profile.assessment.list')
            ->with('info', 'برای ادامه ابتدا آزمون‌های روان‌شناختی را تکمیل کنید.');
    }
}
