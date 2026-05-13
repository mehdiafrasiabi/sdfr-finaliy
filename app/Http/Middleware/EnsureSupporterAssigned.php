<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * تا زمانی که پشتیبان جذب برای دانش‌آموز trial انتخاب نشده،
 * هیچ صفحه‌ای از /profile/* بجز waiting-for-supporter قابل دسترسی نباشد.
 *
 * اگر کاربر خرید کامل کرده باشد (student.payment_id) یا اصلاً trial نداشته باشد،
 * این middleware رد می‌شود.
 */
class EnsureSupporterAssigned
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (!$user) {
            return $next($request);
        }

        $trial = $user->trialWeek;
        if (!$trial) {
            return $next($request);
        }

        // اگر trial منقضی شده و کاربر خرید نکرده، به checkout هدایت کن
        $hasPaidStudent = $user->student && $user->student->payment_id;
        if ($trial->isLocked() && !$hasPaidStudent) {
            if (!$request->routeIs('client.checkout') && !$request->routeIs('client.logout')) {
                return redirect()->route('client.checkout')
                    ->with('warning', 'دوره آزمایشی شما به پایان رسیده است. برای ادامه، پرداخت کنید.');
            }
            return $next($request);
        }

        // پشتیبان هنوز اختصاص داده نشده
        if (!$trial->supporter_id && !$hasPaidStudent) {
            if (!$request->routeIs('client.profile.waiting-for-supporter') && !$request->routeIs('client.logout')) {
                return redirect()->route('client.profile.waiting-for-supporter');
            }
        }

        return $next($request);
    }
}
