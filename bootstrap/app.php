<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CustomerAuth;
use App\Http\Middleware\AdminAuth;
use App\Http\Middleware\SecurityHeaders;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'admin.auth'    => AdminAuth::class,
            'customer.auth' => CustomerAuth::class,
            'role'          => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'    => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        ]);

        // Add security headers to every response
        $middleware->append(SecurityHeaders::class);
        $middleware->validateCsrfTokens(except: [
            'webhooks/razorpay',
            'webhooks/cashfree',
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
