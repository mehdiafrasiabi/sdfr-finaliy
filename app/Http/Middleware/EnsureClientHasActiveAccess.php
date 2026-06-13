<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * فقط کاربری که دوره خریده (پرداخت موفق و دسترسیِ منقضی‌نشده) یا در هفته آزمایشی
 * فعال است می‌تواند به route های `/profile/*` دسترسی داشته باشد.
 *
 * دسترسی پرداختی در پایان خرداد سالِ خدمت (students.access_ends_at) منقضی می‌شود؛
 * پس از آن کاربر به صفحهٔ خرید/تمدید هدایت می‌شود و هیچ صفحهٔ پروفایلی نمی‌بیند.
 */
class EnsureClientHasActiveAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('client.auth.login');
        }

        // دانش‌آموز مدرسه (ثبت‌شده توسط مدرسه) از قاعده‌ی خرید/آزمایشی معاف است.
        if ($user->isSchoolStudent()) {
            return $next($request);
        }

        $student = $user->student;

        // پرداخت موفق + دسترسیِ منقضی‌نشده (پایان خرداد).
        // دانش‌آموز قدیمی با access_ends_at تهی، نامحدود تلقی می‌شود (قفل نمی‌شود).
        $hasCompletedPayment = $user->payments()->where('status', 'completed')->exists();
        $accessActive = $hasCompletedPayment && ! ($student && $student->accessExpired());

        // هفته آزمایشی فعال (منقضی نشده)
        $trialWeek = $user->trialWeek;
        $hasActiveTrial = $trialWeek
            && (! $trialWeek->expires_at || $trialWeek->expires_at->isFuture());

        if ($accessActive || $hasActiveTrial) {
            return $next($request);
        }

        // پرداخت داشته ولی دسترسی منقضی شده → پیام تمدید.
        $expired = $hasCompletedPayment && $student && $student->accessExpired();
        $message = $expired
            ? 'مدت دسترسی شما به پایان رسیده است. برای ادامه، دوره را تمدید کنید.'
            : 'برای دسترسی به این بخش باید دوره را تهیه کنید یا هفته آزمایشی فعال داشته باشید.';

        return redirect()
            ->route('client.purchase')
            ->with('error', $message);
    }
}
