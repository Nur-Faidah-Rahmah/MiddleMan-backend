<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\SubmissionController;
use App\Http\Controllers\Api\EscrowController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\VerificationDocumentController;
use App\Http\Controllers\Api\DisputeController;

// Semua rute di dalam grup ini otomatis memiliki awalan URL /api/v1/...
Route::prefix('v1')->group(function () {

    // --- RUTE PUBLIK (Bisa diakses tanpa login) ---
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // --- RUTE PRIVAT (Wajib lolos Satpam 1: Login via Sanctum) ---
    Route::middleware('auth:sanctum')->group(function () {
        
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me',[AuthController::class,'me']);

        // RUTE KHUSUS WORKER (Lolos Satpam 2: Role Worker)
        Route::middleware('role:worker')->group(function () {
            Route::get('/verification', [VerificationDocumentController::class, 'show']);
        });

        // RUTE KHUSUS ADMIN (Lolos Satpam 2: Role Admin)
        Route::middleware('role:admin')->group(function () {
            Route::get('/admin/verifications', [VerificationDocumentController::class, 'pending']);
            Route::put('/admin/verifications/{document}/approve', [VerificationDocumentController::class, 'approve']);
            Route::put('/admin/verifications/{document}/reject', [VerificationDocumentController::class, 'reject']);
        });

        // --- Akses Semua User Logged-In (Customer, Worker, Admin) ---
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/categories/{category}', [CategoryController::class, 'show']);

        // --- Akses Khusus Admin (Operasi Mutasi Data) ---
        Route::middleware('role:admin')->group(function () {
            Route::apiResource('categories', CategoryController::class)->except(['index','show']);
        });

        Route::prefix('jobs')->group(function(){
            Route::get('/',[JobController::class,'availableJobs']);

            Route::post('/',[JobController::class,'store']);

            Route::get('/mine',[JobController::class,'customerJobs']);

            Route::get('/{job}',[JobController::class,'show']);

            Route::put('/{job}',[JobController::class,'update']);

            Route::delete('/{job}',[JobController::class,'destroy']);
        });

        Route::prefix('applications')->group(function(){
            Route::get('/mine',[ApplicationController::class,'myApplications']);

            Route::patch('/{application}/accept',[ApplicationController::class,'accept']);

            Route::patch('/{application}/reject',[ApplicationController::class,'reject']);

            Route::post('/jobs/{job}/apply',[ApplicationController::class,'apply']);

            Route::get('/jobs/{job}/applications',[ApplicationController::class,'applicants']);

        });

        Route::prefix('submissions')->group(function () {

            Route::post('/jobs/{job}',[SubmissionController::class,'submit']);

            Route::get('/jobs/{job}',[SubmissionController::class,'show']);

        });

        Route::prefix('escrows')->group(function () {

            Route::post('/jobs/{job}/fund',[EscrowController::class,'fund']);

            Route::patch('/jobs/{job}/release',[EscrowController::class,'release']);

            Route::patch('/jobs/{job}/refund',[EscrowController::class,'refund']);

        });

        Route::prefix('transactions')->group(function(){

            Route::get('/',[TransactionController::class,'history']);

            Route::get('/{transaction}',[TransactionController::class,'show']);

        });

        Route::prefix('profile')->group(function(){

            Route::get('/',[UserProfileController::class,'show']);

            Route::put('/',[UserProfileController::class,'update']);

            Route::post('/topup',[UserProfileController::class,'topUp']);

        });

        // Dispute routes
        Route::prefix('disputes')->group(function () {

            Route::post('/jobs/{job}', [DisputeController::class, 'file']);

            // Admin only: resolve dispute
            Route::middleware('role:admin')->group(function () {
                Route::post('/jobs/{job}/resolve', [DisputeController::class, 'resolve']);
            });

        });

        Route::prefix('verification')->group(function(){

            Route::post('/',[VerificationDocumentController::class,'upload']);

        });

        Route::get('/users', [UserProfileController::class, 'index']);
        Route::get('/users/{user}', [UserProfileController::class, 'publicProfile']);
        
    });

});