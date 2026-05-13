<?php


namespace App\Http\Middleware;


use Closure;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Symfony\Component\HttpFoundation\Response;


class CheckAdminPermission

{

    /**
     * چک کردن دسترسی ادمین به بخش‌های مختلف
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @param string $permission نام دسترسی مورد نیاز
     */

    public function handle(Request $request, Closure $next, string $permission): Response

    {

        $admin = Auth::guard('admin')->user();


        if (!$admin) {

            return redirect()->route('admin.sign-in');

        }


        // اگر super admin یا مشاور تحصیلی باشه، همه دسترسی‌ها رو داره

        if ($admin->hasRole('super admin')) {

            return $next($request);

        }


        // چک کردن دسترسی مستقیم

        if ($admin->hasPermissionTo($permission)) {

            return $next($request);

        }


        // اگر دسترسی نداشت، ارور 403

        abort(403, 'شما دسترسی به این بخش را ندارید.');

    }

}
