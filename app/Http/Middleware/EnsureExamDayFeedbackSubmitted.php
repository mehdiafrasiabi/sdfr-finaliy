<?php

namespace App\Http\Middleware;

use App\Services\StudentExamDayFeedbackService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureExamDayFeedbackSubmitted
{
    private const ALLOWED_ROUTES = [
        'client.profile.dashboard',
        'client.logout',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user) {
            return $next($request);
        }

        $currentRoute = $request->route()?->getName();
        if ($currentRoute && in_array($currentRoute, self::ALLOWED_ROUTES, true)) {
            return $next($request);
        }

        $pending = app(StudentExamDayFeedbackService::class)->pendingFeedback($user);
        if (! $pending) {
            return $next($request);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'برای ادامه، ابتدا بازخورد آزمون امروزت را ثبت کن.',
                'redirect' => route('client.profile.dashboard'),
            ], 423);
        }

        return redirect()
            ->route('client.profile.dashboard')
            ->with('warning', 'برای ادامه، ابتدا بازخورد آزمون امروزت را ثبت کن.');
    }
}
