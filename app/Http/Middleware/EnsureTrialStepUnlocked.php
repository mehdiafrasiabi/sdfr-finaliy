<?php

namespace App\Http\Middleware;

use App\Models\TrialWeek;
use App\Services\ExamPlanningService;
use App\Services\TrialWeekService;
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
            // در هفتهٔ آزمایشی، صفحهٔ لیست پروژه‌ها بسته است؛ دانش‌آموز مستقیماً از راهنما وارد طبقه‌بندی می‌شود.
            'client.profile.classification.classify',
        ],
        TrialWeek::STATUS_CLASSIFICATION_DONE => [
            'client.profile.trial.guide',
            // در هفتهٔ آزمایشی، صفحهٔ لیست جلسات بسته است؛ دانش‌آموز مستقیماً از راهنما وارد پیش‌جلسه می‌شود.
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

        $student = $user->student;
        $trial = $user->trialWeek;

        // دانش‌آموز پرداختی (یا کاربرانی که trial ندارند) → عبور.
        if (! $trial || ($student && ! $student->is_trial && $student->hasActivePaidAccess()) || $user->isSchoolStudent()) {
            return $next($request);
        }

        $builtExamSchedule = $user->examSchedules()
            ->whereNotNull('weekly_program_id')
            ->whereNotNull('program_built_at')
            ->latest('program_built_at')
            ->first();

        if ($builtExamSchedule && $trial->status !== TrialWeek::STATUS_PROGRAM_BUILT) {
            $trialProgramBuiltAt = $builtExamSchedule->program_built_at ?? $trial->program_built_at ?? now();

            $trial->update([
                'status' => TrialWeek::STATUS_PROGRAM_BUILT,
                'daily_study_hours' => $builtExamSchedule->max_daily_study_hours ?: $trial->daily_study_hours,
                'program_built_at' => $trialProgramBuiltAt,
                'expires_at' => TrialWeekService::trialAccessExpiresAt($trialProgramBuiltAt),
            ]);

            $trial->refresh();
        }

        if ($trial->status === TrialWeek::STATUS_PROGRAM_BUILT) {
            return $next($request);
        }

        $examPlanning = app(ExamPlanningService::class)->resolveExamAccess($user);
        if ($examPlanning['mode'] === ExamPlanningService::ACCESS_TRIAL) {
            $current = $request->route()?->getName();
            $allowed = [
                'client.profile.trial.guide',
                'client.profile.exam-planning',
            ];

            if ($current && in_array($current, $allowed, true)) {
                return $next($request);
            }

            return redirect()
                ->route('client.profile.trial.guide')
                ->with('error', 'در بازه امتحانات، مسیر ساخت برنامه امتحانی برای شما فعال است.');
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
