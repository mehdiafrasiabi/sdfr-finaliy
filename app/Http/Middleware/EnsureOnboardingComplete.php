<?php

namespace App\Http\Middleware;

use App\Models\Enrollment;
use App\Models\TrialWeek;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return $next($request);
        }

        // اگر هفته آزمایشی فعال داره، اجازه عبور
        if (TrialWeek::where('user_id', $user->id)->exists()) {
            return $next($request);
        }

        // اگر ثبت‌نام پرداخت‌شده با پشتیبان تخصیص‌یافته داره، اجازه عبور
        $hasActiveEnrollment = Enrollment::where('user_id', $user->id)
            ->where('status', Enrollment::STATUS_PAID)
            ->whereNotNull('supporter_id')
            ->exists();

        if ($hasActiveEnrollment) {
            return $next($request);
        }

        return redirect()->route('client.welcome');
    }
}
