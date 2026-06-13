<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Trust proxies dari Railway load balancer
        $middleware->trustProxies(at: '*');
        
        $middleware->statefulApi();
        $middleware->alias([
            'is.admin'   => \App\Http\Middleware\IsAdmin::class,
            'admin.auth' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // TEMPORARY: Show error details in browser for Railway debugging
        // REMOVE THIS AFTER DEBUGGING
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->is('/')) {
                return response("<pre style='font-family:monospace;padding:20px;color:red'><b>ERROR:</b> " . get_class($e) . "<br><br><b>MESSAGE:</b> " . htmlspecialchars($e->getMessage()) . "<br><br><b>FILE:</b> " . htmlspecialchars($e->getFile()) . ":" . $e->getLine() . "<br><br><b>TRACE:</b>\n" . $e->getTraceAsString() . "</pre>", 500);
            }
            report($e);
        });

        // Kalau unauthenticated di API, return JSON bukan redirect
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Unauthenticated. Silakan login terlebih dahulu.'
                ], 401);
            }
        });
    })->create();