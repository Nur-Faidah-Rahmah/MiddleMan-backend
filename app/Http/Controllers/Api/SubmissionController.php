<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use App\Services\SubmissionService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Submission\StoreSubmissionRequest;
use App\Http\Resources\Submission\SubmissionResource;
use Illuminate\Http\JsonResponse;
use Exception;

class SubmissionController extends Controller
{
    public function __construct(
        protected SubmissionService $submissionService
    ) {
    }

    public function submit(
        StoreSubmissionRequest $request,
        Job $job
    ): JsonResponse
    {
        try {

            return $this->successResponse(

                new SubmissionResource(

                    $this->submissionService->submit(

                        $job,

                        auth()->id(),

                        $request->validated()

                    )

                ),

                'Submission berhasil dikirim.',

                201

            );

        } catch (Exception $e) {

            return $this->errorResponse(

                $e->getMessage(),

                $e->getCode() ?: 400

            );

        }
    }

    public function show(Job $job): JsonResponse
    {
        return $this->successResponse(

            new SubmissionResource(

                $this->submissionService->show($job)

            ),

            'Detail submission.'

        );
    }
}