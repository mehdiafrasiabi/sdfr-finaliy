<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OnlySupporters
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->hasAnyRole(['Product Admin', 'Order Admin', 'Payment Admin', 'Map Admin', 'student Admin', 'story Admin', 'ContactUs Admin', 'user Admin','payment_method admin']) && !Auth::user()->hasRole('super admin')) {
            return $next($request);
        }

        abort(403, 'شما اجازه دسترسی به این بخش را ندارید.');
    }
}
