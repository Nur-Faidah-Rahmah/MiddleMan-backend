<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Http\Requests\Job\StoreJobRequest;
use App\Services\JobService;
use Illuminate\Http\JsonResponse;
use Exception;

class JobController extends Controller
{
    protected $jobService;

    // Inject JobService lewat constructor
    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }

    // ==========================================
    // 1. FUNGSI KHUSUS CUSTOMER
    // ==========================================

    public function store(StoreJobRequest $request): JsonResponse
    {
        $job = $this->jobService->createJob($request->validated(), auth()->id());
        return $this->successResponse($job, 'Tugas berhasil dibuat dan menunggu persetujuan Admin.', 201);
    }

    public function customerJobs(): JsonResponse
    {
        $jobs = $this->jobService->getCustomerJobHistory(auth()->id());
        return $this->successResponse($jobs, 'Daftar riwayat tugas Anda berhasil diambil.', 200);
    }

    // ==========================================
    // 2. FUNGSI KHUSUS WORKER
    // ==========================================

    public function availableJobs(): JsonResponse
    {
        $jobs = $this->jobService->getAvailableJobsForWorker();
        return $this->successResponse($jobs, 'Daftar bursa kerja tersedia berhasil diambil.', 200);
    }

    public function takeJob(Job $job): JsonResponse
    {
        try {
            $job = $this->jobService->takeJob($job, auth()->id());
            return $this->successResponse($job, 'Tugas berhasil diambil. Selamat bekerja!', 200);
        } catch (Exception $e) {
            // Tangkap exception dari Service dan jadikan respons error standar
            $code = $e->getCode() !== 0 ? $e->getCode() : 400;
            return $this->errorResponse($e->getMessage(), $code);
        }
    }

    public function completeJob(Job $job): JsonResponse
    {
        try {
            $job = $this->jobService->completeJob($job, auth()->id());
            return $this->successResponse($job, 'Selamat! Tugas telah dinyatakan selesai.', 200);
        } catch (Exception $e) {
            $code = $e->getCode() !== 0 ? $e->getCode() : 400;
            return $this->errorResponse($e->getMessage(), $code);
        }
    }

    // ==========================================
    // 3. FUNGSI KHUSUS ADMIN
    // ==========================================

    public function pendingJobs(): JsonResponse
    {
        $jobs = $this->jobService->getPendingJobsForAdmin();
        return $this->successResponse($jobs, 'Daftar antrean verifikasi tugas berhasil diambil.', 200);
    }

    public function verifyJob(Job $job): JsonResponse
    {
        try {
            $job = $this->jobService->verifyJob($job);
            return $this->successResponse($job, 'Tugas berhasil disetujui dan dilempar ke bursa kerja.', 200);
        } catch (Exception $e) {
            $code = $e->getCode() !== 0 ? $e->getCode() : 400;
            return $this->errorResponse($e->getMessage(), $code);
        }
    }
}