<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '127.0.0.1');

        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureUserHasRole::class,
            'approved' => \App\Http\Middleware\EnsureUserIsApproved::class,
        ]);

        $middleware->redirectUsersTo(function ($request) {
            $user = $request->user();

            if ($user->isPending()) {
                return route('pending-approval');
            }

            return route($user->isDean() ? 'dean.dashboard' : 'student.dashboard');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
