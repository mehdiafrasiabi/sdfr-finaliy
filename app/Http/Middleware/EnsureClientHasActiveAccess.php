<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * فقط کاربری که دوره خریده (پرداخت موفق) یا در هفته آزمایشی فعال است
 * می‌تواند به route های `/profile/*` دسترسی داشته باشد.
 */
class EnsureClientHasActiveAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('client.auth.login');
        }

        // پرداخت موفق ← دانش‌آموز رسمی
        $hasPaidAccess = $user->payments()
            ->where('status', 'completed')
            ->exists();

        // هفته آزمایشی فعال (منقضی نشده)
        $trialWeek = $user->trialWeek;
        $hasActiveTrial = $trialWeek
            && (! $trialWeek->expires_at || $trialWeek->expires_at->isFuture());

        if ($hasPaidAccess || $hasActiveTrial) {
            return $next($request);
        }

        return redirect()
            ->route('client.onboarding')
            ->with('error', 'برای دسترسی به این بخش باید دوره را تهیه کنید یا هفته آزمایشی فعال داشته باشید.');
    }
}
