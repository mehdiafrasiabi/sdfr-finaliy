<?php

namespace App\Http\Middleware;

use App\Models\InstallmentPlan;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * اگر دانش‌آموز طرح اقساطیِ فعال با قسطِ «عقب‌افتاده» (سررسیدشده و پرداخت‌نشده)
 * داشته باشد، تا تسویهٔ آن فقط می‌تواند به صفحهٔ اقساط (و logout/callback) برود.
 */
class EnsureInstallmentsCurrent
{
    protected const ALLOWED_ROUTES = [
        'client.profile.installment',
        'client.profile.installmentDetail',
        'client.logout',
        'client.payment.callback',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (! $user) {
            return $next($request);
        }

        $student = $user->student;
        if (! $student) {
            return $next($request);
        }

        $plan = $student->activeInstallmentPlan();
        if (! $plan || $plan->status !== InstallmentPlan::STATUS_ACTIVE || ! $plan->hasOverdue()) {
            return $next($request);
        }

        $name = $request->route()?->getName();
        if ($name && in_array($name, self::ALLOWED_ROUTES, true)) {
            return $next($request);
        }

        return redirect()
            ->route('client.profile.installment')
            ->with('error', 'قسطِ سررسیدشدهٔ پرداخت‌نشده دارید. برای ادامهٔ دسترسی، قسط جاری را پرداخت کنید.');
    }
}
