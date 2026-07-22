<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use App\Models\Application;
use App\Services\ApplicationService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\ApplyApplicationRequest;
use App\Http\Resources\Application\ApplicationCollection;
use App\Http\Resources\Application\ApplicationResource;
use Illuminate\Http\JsonResponse;
use Exception;

class ApplicationController extends Controller
{
    public function __construct(
        protected ApplicationService $applicationService
    ) {
    }

    /**
     * Worker apply quest.
     */
    public function apply(
        ApplyApplicationRequest $request,
        Job $job
    ): JsonResponse
    {
        try {

            $application = $this->applicationService->apply(
                $job,
                auth()->id()
            );

            return $this->successResponse(
                new ApplicationResource($application),
                'Berhasil melamar quest.',
                201
            );

        } catch (Exception $e) {

            return $this->errorResponse(
                $e->getMessage(),
                $e->getCode() ?: 400
            );

        }
    }

    /**
     * Semua pelamar.
     */
    public function applicants(Job $job): JsonResponse
    {
        return $this->successResponse(
            new ApplicationCollection(
                $this->applicationService->applicants($job)
            ),
            'Daftar pelamar berhasil diambil.'
        );
    }

    /**
     * Riwayat lamaran worker.
     */
    public function myApplications(): JsonResponse
    {
        return $this->successResponse(
            new ApplicationCollection(
                $this->applicationService->myApplications(auth()->id())
            ),
            'Riwayat lamaran berhasil diambil.'
        );
    }

    /**
     * Requester memilih worker.
     */
    public function accept(Application $application): JsonResponse
    {
        try {

            return $this->successResponse(
                new ApplicationResource(
                    $this->applicationService->accept($application)
                ),
                'Worker berhasil dipilih.'
            );

        } catch (Exception $e) {

            return $this->errorResponse(
                $e->getMessage(),
                $e->getCode() ?: 400
            );

        }
    }

    /**
     * Tolak lamaran.
     */
    public function reject(Application $application): JsonResponse
    {
        try {

            return $this->successResponse(

                new ApplicationResource(
                    $this->applicationService->reject($application)
                ),

                'Lamaran berhasil ditolak.'

            );

        } catch (Exception $e) {

            return $this->errorResponse(

                $e->getMessage(),

                $e->getCode() ?: 400

            );

        }
    }
}