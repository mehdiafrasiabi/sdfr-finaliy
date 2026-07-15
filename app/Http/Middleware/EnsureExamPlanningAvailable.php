<?php

namespace App\Http\Middleware;

use App\Services\ExamPlanningService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureExamPlanningAvailable
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user) {
            return $next($request);
        }

        $access = app(ExamPlanningService::class)->resolveExamAccess($user);
        if ($access['mode']) {
            return $next($request);
        }

        $targetRoute = $user->student?->is_trial
            ? 'client.profile.trial.guide'
            : 'client.profile.dashboard';

        return redirect()
            ->route($targetRoute)
            ->with('error', 'در حال حاضر برنامه‌ریزی امتحانات برای حساب شما فعال نیست.');
    }
}
