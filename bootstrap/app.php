<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

// Import semua jenis exception HTTP
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Auth\AuthenticationException;

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
        
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // 1. Tangkap Error 401 (Belum Login / Token Invalid)
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Sesi tidak valid atau Anda belum login.',
                    'errors'  => null
                ], 401);
            }
        });

        // 2. Tangkap Error 403 (Akses Ditolak / Salah Role)
        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Anda tidak memiliki izin untuk tindakan ini.',
                    'errors'  => null
                ], 403);
            }
        });

        // 3. Tangkap Error 404 (Data / URL Tidak Ditemukan)
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan atau rute tidak valid.',
                    'errors'  => null
                ], 404);
            }
        });

        // 4. Tangkap Error 405 (Salah Metode HTTP, misal GET padahal harusnya POST)
        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Metode HTTP tidak diizinkan. Cek kembali apakah harusnya GET, POST, PUT, atau DELETE.',
                    'errors'  => null
                ], 405);
            }
        });

        // BLOK PENGAMAN 500+ DI PALING BAWAH:
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                
                // Ambil status code HTTP (jika bukan HTTP exception, otomatis set ke 500)
                $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

                // hanya mencegat error tingkat server (500 ke atas, termasuk 505)
                if ($statusCode >= 500) {
                    return response()->json([
                        'success'     => false,
                        'message'     => 'Terjadi kegagalan sistem pada server (Internal Server Error).',
                        'problem'     => $e->getMessage(), // Hanya mengambil 1 baris inti masalahnya saja
                        'solution'    => 'Solusi: Silakan periksa log server, pastikan database menyala, atau cek apakah ada typo kode di file Service/Controller Anda.',
                        'errors'      => null
                    ], $statusCode);
                }
            }
        });

    })->create();