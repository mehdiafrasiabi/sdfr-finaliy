<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockProfileDuringActiveStudy
{
    private const ALLOWED_ROUTES = [
        'client.profile.studySession',
        'client.logout',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $currentRoute = $request->route()?->getName();
        if ($currentRoute && in_array($currentRoute, self::ALLOWED_ROUTES, true)) {
            return $next($request);
        }

        if ($this->hasActiveTimer()) {
            $studyRoute = route('client.profile.studySession');

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'message'  => 'یک جلسه مطالعه فعال دارید. ابتدا آن را ثبت یا لغو کنید.',
                    'redirect' => $studyRoute,
                ], 423);
            }

            return redirect($studyRoute)
                ->with('warning', 'یک جلسه مطالعه فعال دارید. ابتدا آن را ثبت یا لغو کنید.');
        }

        return $next($request);
    }

    private function hasActiveTimer(): bool
    {
        $timer = session('active_timer_state');
        if (is_array($timer) && !empty($timer['currentPartId'])) {
            return true;
        }

        $makeup = session('active_makeup_timer_state');
        if (is_array($makeup) && !empty($makeup['topicId'])) {
            return true;
        }

        return false;
    }
}
