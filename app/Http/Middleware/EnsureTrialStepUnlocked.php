<?php

namespace App\Http\Middleware;

use App\Models\TrialWeek;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * B-1 — Gating مرحله‌به‌مرحلهٔ مسیرهای پنل کاربر برای دانش‌آموزان آزمایشی:
 *
 *   pending             → فقط صفحهٔ انتظار برای تخصیص پشتیبان جذب باز است
 *   supporter_assigned  → فقط مرحلهٔ طبقه‌بندی و راهنمای آزمایشی
 *   classification_done → فقط مرحلهٔ پیش‌جلسهٔ مشاوره و راهنما
 *   pre_session_done    → فقط راهنما (در انتظار ساخت برنامه)
 *   program_built       → دسترسی کامل به همهٔ مسیرهای profile
 *
 * کاربرانی که دانش‌آموز پرداختی هستند (is_trial = false) از این
 * middleware عبور می‌کنند.
 */
class EnsureTrialStepUnlocked
{
    /**
     * مسیرهایی که در هر مرحلهٔ هفتهٔ آزمایشی مجاز هستند.
     * کلید = status روی TrialWeek، مقدار = آرایه‌ای از route name‌های مجاز.
     */
    protected const STEP_ALLOWED_ROUTES = [
        TrialWeek::STATUS_SUPPORTER_ASSIGNED => [
            'client.profile.trial.guide',
            'client.profile.classification.projects',
            'client.profile.classification.classify',
        ],
        TrialWeek::STATUS_CLASSIFICATION_DONE => [
            'client.profile.trial.guide',
            'client.profile.consultation.sessions',
            'client.profile.consultation.pre-session',
            'client.profile.consultation.class-schedule',
        ],
        TrialWeek::STATUS_PRE_SESSION_DONE => [
            'client.profile.trial.guide',
            'client.profile.trial.session-analysis',
        ],
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (! $user) {
            return $next($request); // middleware دیگر این را مدیریت می‌کند
        }

        $trial = $user->trialWeek;

        // دانش‌آموز پرداختی (یا کاربرانی که trial ندارند) → عبور.
        if (! $trial) {
            return $next($request);
        }

        // اگر برنامه ساخته شده، دسترسی کامل.
        if ($trial->status === TrialWeek::STATUS_PROGRAM_BUILT) {
            return $next($request);
        }

        // مرحلهٔ pending: هیچ‌چیز در /profile باز نباشد.
        if ($trial->status === TrialWeek::STATUS_PENDING) {
            return redirect()->route('client.profile.waiting-for-supporter');
        }

        // در سایر مراحل، فقط route‌های مرحلهٔ فعلی مجازند.
        $current = $request->route()?->getName();
        $allowed = self::STEP_ALLOWED_ROUTES[$trial->status] ?? [];

        if ($current && in_array($current, $allowed, true)) {
            return $next($request);
        }

        return redirect()
            ->route('client.profile.trial.guide')
            ->with('error', 'برای دسترسی به این بخش باید مرحلهٔ فعلی هفته آزمایشی را تکمیل کنید.');
    }
}
