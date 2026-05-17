<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'admin.permission' => \App\Http\Middleware\CheckAdminPermission::class,
            'client.active' => \App\Http\Middleware\EnsureClientHasActiveAccess::class,
            'trial.step'    => \App\Http\Middleware\EnsureTrialStepUnlocked::class,
        ]);

        $middleware->redirectGuestsTo(function () {
            $currentPath = request()->path();
            if (str_starts_with($currentPath, 'admin')) {
                return route('admin.sign-in');
            } elseif (str_starts_with($currentPath, 'manager')) {
                return route('manager.sign-in');
            }
            return route('client.auth.login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
// ✅ اینجا public_html رو معرفی کن
$app->usePublicPath($app->basePath('public_html'));
return $app;
