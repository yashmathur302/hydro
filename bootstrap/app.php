<?php

declare(strict_types=1);

use App\Http\Middleware\AdminResponseHeaders;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: static function (): void {
            RateLimiter::for('admin-login', static fn (Request $request): Limit => Limit::perMinute(10)->by($request->ip()));
            RateLimiter::for('admin-password-reset', static fn (Request $request): Limit => Limit::perMinute(3)->by($request->ip()));
            RateLimiter::for('admin-password-change', static fn (Request $request): Limit => Limit::perMinute(5)->by((string) $request->user()?->getAuthIdentifier()));

            Route::middleware(['web', AdminResponseHeaders::class])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(static function (Middleware $middleware): void {
        $middleware->append(SecurityHeaders::class);

        $middleware->redirectGuestsTo(static fn (): string => route('admin.login'));
        $middleware->redirectUsersTo(static fn (): string => route('admin.dashboard'));
    })
    ->withExceptions(static function (Exceptions $exceptions): void {
        //
    })->create();
