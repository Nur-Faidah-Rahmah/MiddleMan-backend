<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;

// Semua rute di dalam grup ini otomatis memiliki awalan URL /api/v1/...
Route::prefix('v1')->group(function () {

    // --- RUTE PUBLIK (Bisa diakses tanpa login) ---
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // --- RUTE PRIVAT (Wajib lolos Satpam 1: Login via Sanctum) ---
    Route::middleware('auth:sanctum')->group(function () {
        
        Route::post('/logout', [AuthController::class, 'logout']);

        // 1. RUTE KHUSUS CUSTOMER (Lolos Satpam 2: Role Customer)
        Route::middleware('role:customer')->group(function () {
            Route::post('/jobs', [JobController::class, 'store']);       // Create Tugas Baru
            Route::get('/my-requests', [JobController::class, 'customerJobs']); // Lihat tugas miliknya
        });

        // 2. RUTE KHUSUS WORKER (Lolos Satpam 2: Role Worker)
        Route::middleware('role:worker')->group(function () {
            Route::get('/available-jobs', [JobController::class, 'availableJobs']); // Bursa kerja
            Route::put('/jobs/{job}/take', [JobController::class, 'takeJob']);      // Ambil tugas
        });

        // 3. RUTE KHUSUS ADMIN (Lolos Satpam 2: Role Admin)
        Route::middleware('role:admin')->group(function () {
            Route::get('/admin/jobs/pending', [JobController::class, 'pendingJobs']); // Verifikasi list
            Route::put('/jobs/{job}/verify', [JobController::class, 'verifyJob']);    // Setujui tugas
        });

        // --- Akses Semua User Logged-In (Customer, Worker, Admin) ---
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/categories/{category}', [CategoryController::class, 'show']);

        // --- Akses Khusus Admin (Operasi Mutasi Data) ---
        Route::middleware('role:admin')->group(function () {
            Route::post('/categories', [CategoryController::class, 'store']);       // Create
            Route::put('/categories/{category}', [CategoryController::class, 'update']);   // Update
            Route::delete('/categories/{category}', [CategoryController::class, 'destroy']); // Delete
        });
        
    });

});