<?php

use App\Exceptions\Handler;
use Illuminate\Foundation\Application;
use App\Http\Middleware\AlwaysAcceptJson;
use App\Http\Middleware\LocaleMiddleware;
use Modules\Auth\Http\Middleware\UserMiddleware;
use Modules\Auth\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Auth\Http\Middleware\DriverMiddleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->append([
            LocaleMiddleware::class,
            AlwaysAcceptJson::class
        ]);
        $middleware->alias([
            'admin.auth' => AdminMiddleware::class,
            'user.auth' => UserMiddleware::class,
            'driver.auth' => DriverMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $httpResponse = (new class
        {
            use \App\Traits\HttpResponse;
        });

        $exceptions->render(function (ModelNotFoundException $e, $request) use($httpResponse) {
            //NotFoundHttpException
            return $httpResponse->errorResponse(message: 'Model NOT found');
        });
    })->create();
