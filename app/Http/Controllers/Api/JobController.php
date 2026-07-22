<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use App\Services\JobService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Job\StoreJobRequest;
use App\Http\Requests\Job\UpdateJobStatusRequest;
use App\Http\Resources\Job\JobCollection;
use App\Http\Resources\Job\JobResource;
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
        return $this->successResponse(
            new JobResource($job),
            'Tugas berhasil dibuat dan menunggu persetujuan Admin.',
            201);
    }

    public function customerJobs(): JsonResponse
    {
        $jobs = $this->jobService->getRequesterJobs(auth()->id());

        return $this->successResponse(
            new JobCollection($jobs),
            'Daftar quest berhasil diambil.'
        );
    }

    /**
     * Detail quest.
     */
    public function show(Job $job): JsonResponse
    {
        return $this->successResponse(
            new JobResource(
                $this->jobService->show($job)
            ),
            'Detail quest berhasil diambil.'
        );
    }

    /**
     * Update quest.
     */
    public function update(
        UpdateJobStatusRequest $request,
        Job $job
    ): JsonResponse
    {
        try {

            return $this->successResponse(
                new JobResource(
                    $this->jobService->update(
                        $job,
                        $request->validated(),
                        auth()->id()
                    )
                ),
                'Quest berhasil diperbarui.'
            );

        } catch (Exception $e) {

            return $this->errorResponse(
                $e->getMessage(),
                $e->getCode() ?: 400
            );

        }
    }

    /**
     * Hapus quest.
     */
    public function destroy(Job $job): JsonResponse
    {
        try {

            $this->jobService->destroy(
                $job,
                auth()->id()
            );

            return $this->successResponse(
                null,
                'Quest berhasil dihapus.'
            );

        } catch (Exception $e) {

            return $this->errorResponse(
                $e->getMessage(),
                $e->getCode() ?: 400
            );

        }
    }

    // ==========================================
    // 2. FUNGSI KHUSUS WORKER
    // ==========================================

    public function availableJobs(): JsonResponse
    {
        $jobs = $this->jobService->getOpenJobs();

        return $this->successResponse(
            new JobCollection($jobs),
            'Daftar quest tersedia berhasil diambil.'
        );
    }

    public function publish(Job $job): JsonResponse
    {
        $job = $this->jobService->publish($job);

        return $this->successResponse(
            new JobResource($job),
            'Quest berhasil dipublikasikan.'
        );
    }

    public function selectWorker(Job $job, int $applicationId): JsonResponse
    {
        $job = $this->jobService
            ->selectWorker($job, $applicationId);

        return $this->successResponse(
            new JobResource($job),
            'Worker berhasil dipilih.'
        );
    }

    public function approve(Job $job): JsonResponse
    {
        $job = $this->jobService->approve($job);

        return $this->successResponse(
            new JobResource($job),
            'Submission berhasil disetujui.'
        );
    }

    public function cancel(Job $job): JsonResponse
    {
        $job = $this->jobService->cancel($job);

        return $this->successResponse(
            new JobResource($job),
            'Quest berhasil dibatalkan.'
        );
    }

}