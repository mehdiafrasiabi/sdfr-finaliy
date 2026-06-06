<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * گِیت دومرحله‌ای فاز ۳:
 *
 *  1. اگر دانش‌آموز هفته‌ی آزمایشی دارد و assessments_completed_at پر نشده،
 *     او را به journey آزمون‌ها هدایت می‌کند.
 *  2. اگر assessments تمام شده ولی profile_acknowledged_at پر نشده،
 *     او را به صفحه‌ی ProfileReview هدایت می‌کند.
 *  3. در غیر این صورت اجازه‌ی عبور.
 *
 * روت‌های مربوط به خود journey/welcome/take/review و logout از این چک معاف‌اند.
 */
class EnsureAssessmentsCompleted
{
    protected const ALLOWED_ROUTES = [
        'client.profile.assessment.journey',
        'client.profile.assessment.welcome',
        'client.profile.assessment.take',
        'client.profile.assessment.review',
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

        $name = $request->route()?->getName();
        $isAllowed = $name && in_array($name, self::ALLOWED_ROUTES, true);

        if (! $trial->assessments_completed_at) {
            if ($isAllowed) {
                return $next($request);
            }
            return redirect()
                ->route('client.profile.assessment.journey')
                ->with('info', 'برای ادامه ابتدا آزمون‌های روان‌شناختی را تکمیل کنید.');
        }

        if (! $trial->profile_acknowledged_at) {
            if ($isAllowed) {
                return $next($request);
            }
            return redirect()
                ->route('client.profile.assessment.review')
                ->with('info', 'لطفاً پروفایل خود را مرور و تأیید کنید.');
        }

        return $next($request);
    }
}
