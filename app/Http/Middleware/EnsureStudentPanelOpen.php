<?php

namespace App\Http\Middleware;

use App\Models\GeneralSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * بستن موقت پنل دانش‌آموز. وقتی مدیر کلید `student_panel_closed` را روشن کند،
 * همهٔ مسیرهای پنل دانش‌آموز یک صفحهٔ «موقتاً بسته است» نشان می‌دهند (به‌جز logout).
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

        $settings = GeneralSetting::first();

        if ($settings && $settings->student_panel_closed) {
            $message = $settings->student_panel_closed_message
                ?: 'پنل به‌طور موقت بسته است. لطفاً بعداً مراجعه کنید.';

            return response()->view('client.panel-closed', [
                'message' => $message,
            ], 503);
        }

        return $next($request);
    }
}
