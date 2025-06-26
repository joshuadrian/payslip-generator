<?php

use App\Exceptions\MyException;
use App\Http\Middleware\JsonOnlyMiddleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            JsonOnlyMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized'
                ], 401);
            }
        });

        $exceptions->render(function (HttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], $e->getStatusCode());
            }
        });

        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => '',
                    'message' => 'Unprocessable entity.',
                    'errors' => $e->errors()
                ], 422);
            }
        });

        $exceptions->dontReport(MyException::class);
        $exceptions->render(function (MyException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], $e->getCode());
            }
        });

        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Internal server error.'
                ], 500);
            }
        });
    })->create();
