<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * دانش‌آموزانی که توسط مدرسه ثبت‌نام شده‌اند، حق شرکت در «هفته آزمایشی» و
 * «خرید دوره» را ندارند. این کاربران فقط می‌توانند آزمون‌های روان‌شناختی
 * (تست مایندست/ذهنیت تحصیلی) را تکمیل کنند و سپس از پنل عادی استفاده کنند.
 */
class RestrictSchoolStudent
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->isSchoolStudent()) {
            return redirect()
                ->route('client.profile.dashboard')
                ->with('info', 'دانش‌آموزان مدرسه به هفته آزمایشی و خرید دوره دسترسی ندارند.');
        }

        return $next($request);
    }
}
