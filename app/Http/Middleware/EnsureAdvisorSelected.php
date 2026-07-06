<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * اگر کاربر دوره را خریده ولی هنوز مشاور انتخاب نکرده،
 * او را به صفحه انتخاب مشاور هدایت می‌کند و از دسترسی به سایر صفحات جلوگیری می‌کند.
 */
class EnsureAdvisorSelected
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $student = $user?->student;

        // اگر کاربر دانش‌آموز نیست یا نیازی به انتخاب مشاور ندارد، عبور کند
        if (! $student || ! $student->needsAdvisorSelection()) {
            return $next($request);
        }

        // اگر کاربر در صفحه انتخاب مشاور است، اجازه دسترسی بده
        if ($request->routeIs('client.profile.appointment')) {
            return $next($request);
        }

        // در غیر این صورت، به صفحه انتخاب مشاور هدایت کن
        return redirect()->route('client.profile.appointment');
    }
}
