<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleNotMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::guard('admin')->user();

        if ($user && $user->hasRole($role)) {
            abort(403, 'شما اجازه دسترسی به این بخش را ندارید.');
        }

        return $next($request);
    }
}
