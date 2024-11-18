<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'encrypted' => \App\Http\Middleware\VerifyEncryptionTokenMiddleware::class,
            'password.confirm' => \App\Http\Middleware\RequirePassword::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->respond(function (Response $response) {
            // if the CSRF token expired, return to the previous page with the error
            return $response->getStatusCode() === 419
                ? back()->with((array)\App\Support\SystemAlert::warning(__('message.page_expired')))
                : $response;
        });
    })->create();
