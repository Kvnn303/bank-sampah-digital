<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Trust proxies from Railway load balancer
        $middleware->trustProxies(at: '*');

        $middleware->statefulApi();
        $middleware->alias([
            'is.admin'        => \App\Http\Middleware\IsAdmin::class,
            'admin.auth'      => \App\Http\Middleware\AdminMiddleware::class,
            'api.security'    => \App\Http\Middleware\ApiSecurityMiddleware::class,
        ]);

        // Tambahkan security middleware ke semua API routes
        $middleware->api(append: [
            \App\Http\Middleware\ApiSecurityMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Kalau unauthenticated di API, return JSON bukan redirect
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated. Silakan login terlebih dahulu.'
                ], 401);
            }
        });

        // Rate limit exception → return JSON
        $exceptions->render(function (\Illuminate\Http\Exceptions\ThrottleRequestsException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success'     => false,
                    'message'     => 'Terlalu banyak permintaan. Coba lagi nanti.',
                    'retry_after' => $e->getHeaders()['Retry-After'] ?? 60,
                ], 429);
            }
        });
    })->create();