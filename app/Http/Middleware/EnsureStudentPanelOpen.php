<?php

namespace App\Http\Middleware;

use App\Models\GeneralSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * بستن پنل دانش‌آموز در دو سطح:
 *   ۱) سراسری: کلید `general_settings.student_panel_closed` (همهٔ کاربران).
 *   ۲) تکی: فیلد `users.panel_closed` برای یک کاربرِ خاص (از پنل مدیر → جزئیات کاربر).
 * در هر دو حالت یک صفحهٔ «بسته است» نمایش داده می‌شود (به‌جز logout).
 * پنل مدیر/ادمین (گاردِ جدا) و سایت عمومی تحت تأثیر نیستند.
 */
class EnsureStudentPanelOpen
{
    public function handle(Request $request, Closure $next): Response
    {
        // اجازهٔ خروج همیشه باز است تا کاربر بتواند logout کند.
        if ($request->route()?->getName() === 'client.logout') {
            return $next($request);
        }

        // ۱) بستنِ سراسری
        $settings = GeneralSetting::first();
        if ($settings && $settings->student_panel_closed) {
            $message = $settings->student_panel_closed_message
                ?: 'پنل به‌طور موقت بسته است. لطفاً بعداً مراجعه کنید.';

            return response()->view('client.panel-closed', ['message' => $message], 503);
        }

        // ۲) بستنِ تکیِ همین کاربر
        $user = $request->user();
        if ($user && $user->panel_closed) {
            $message = $user->panel_closed_message
                ?: 'دسترسی شما به پنل توسط مدیریت بسته شده است. برای پیگیری با پشتیبانی تماس بگیرید.';

            return response()->view('client.panel-closed', ['message' => $message], 503);
        }

        return $next($request);
    }
}
