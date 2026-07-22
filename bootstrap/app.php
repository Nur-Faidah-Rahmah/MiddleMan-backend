<?php

use App\Exceptions\ApiException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // BARIS UNTUK MENDAFTARKAN ALIAS 'role'
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // Daftarkan ke global middleware
        $middleware->append(\App\Http\Middleware\SanitizeInput::class);

    })
    ->withExceptions(function (Exceptions $exceptions) {

    $exceptions->render(function (
        Throwable $e,
        Request $request
    ) {

        if (! $request->is('api/*')) {
            return null;
        }

        if ($e instanceof ApiException) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => null
            ], $e->getCode());

        }

        if ($e instanceof ValidationException) {

            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);

        }

        if ($e instanceof AuthenticationException) {

            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'errors' => null
            ],401);

        }

        if ($e instanceof AuthorizationException) {

            return response()->json([
                'success' => false,
                'message' => 'Forbidden.',
                'errors' => null
            ],403);

        }

        if ($e instanceof ModelNotFoundException) {

            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.',
                'errors' => null
            ],404);

        }

        if ($e instanceof NotFoundHttpException) {

            return response()->json([
                'success' => false,
                'message' => 'Endpoint tidak ditemukan.',
                'errors' => null
            ],404);

        }

        if ($e instanceof MethodNotAllowedHttpException) {

            return response()->json([
                'success' => false,
                'message' => 'Method tidak diizinkan.',
                'errors' => null
            ],405);

        }

        return response()->json([
            'success' => false,
            'message' => config('app.debug')
                ? $e->getMessage()
                : 'Internal Server Error',
            'errors' => null
        ],500);

    });

})->create();