<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class NoIndex
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // با env کنترلش کن که موقع لانچ یادت نره خاموشش کنی
        if (env('SITE_NOINDEX', false)) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow, noarchive, nosnippet');
        }

        return $response;
    }
}
