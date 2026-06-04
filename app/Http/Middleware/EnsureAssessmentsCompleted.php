<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * گِیت آزمون‌های روان‌شناختی: اگر دانش‌آموز هفته‌ی آزمایشی دارد و هنوز
 * trial_weeks.assessments_completed_at پر نشده است، فقط مسیرهای مربوط به
 * تکمیل آزمون‌ها (و logout) قابل دسترسی هستند. سایر مسیرها به لیست آزمون‌ها
 * هدایت می‌شوند.
 *
 * دانش‌آموزان پرداختی (بدون TrialWeek) و کسانی که آزمون‌ها را تکمیل کرده‌اند
 * از این middleware عبور می‌کنند.
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

        $trial = $user->trialWeek;

        if (! $trial) {
            return $next($request);
        }

        if ($trial->assessments_completed_at) {
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
