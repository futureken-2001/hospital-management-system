<?php

use App\Http\Middleware\EnsureUserHasRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register the custom "role" middleware alias used throughout
        // routes/web.php to restrict access per Spatie role.
        $middleware->alias([
            'role' => EnsureUserHasRole::class,
        ]);

        // routes/api.php is consumed by same-origin fetch() calls from
        // authenticated Blade pages (queue/lab polling, notification
        // badge), not by an external API client. Sharing the web
        // session/cookie stack here lets it reuse the normal 'auth'
        // guard instead of requiring a separate token system.
        $middleware->group('api', [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
