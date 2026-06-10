<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Trust all proxies — Required for Cloudflare Flexible SSL
        // Without this, Laravel sees HTTP instead of HTTPS and breaks sessions/redirects
        $middleware->trustProxies(at: '*');
        
        // Disable CSRF for public-facing forms to prevent 419 errors from Cloudflare caching
        $middleware->validateCsrfTokens(except: [
            'i/*/rsvp',
            'e/*/register',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
