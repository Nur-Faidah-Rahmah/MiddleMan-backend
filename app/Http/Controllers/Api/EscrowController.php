<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use App\Services\EscrowService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Escrow\EscrowResource;
use Illuminate\Http\JsonResponse;
use Exception;

class EscrowController extends Controller
{
    public function __construct(
        protected EscrowService $escrowService
    ) {
    }

    /**
     * Requester melakukan pendanaan escrow.
     */
    public function fund(Job $job): JsonResponse
    {
        try {

            return $this->successResponse(

                new EscrowResource(

                    $this->escrowService->fund(

                        $job,

                        auth()->id()

                    )

                ),

                'Pembayaran berhasil. Quest telah dibuka.',

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
     * Admin melepas dana ke worker.
     */
    public function release(Job $job): JsonResponse
    {
        try {

            return $this->successResponse(

                new EscrowResource(

                    $this->escrowService->release(

                        $job,

                        auth()->id()

                    )

                ),

                'Dana berhasil dilepas ke worker.'

            );

        } catch (Exception $e) {

            return $this->errorResponse(

                $e->getMessage(),

                $e->getCode() ?: 400

            );

        }
    }

    /**
     * Refund dana.
     */
    public function refund(Job $job): JsonResponse
    {
        try {

            return $this->successResponse(

                new EscrowResource(

                    $this->escrowService->refund(

                        $job,

                        auth()->id()

                    )
                ),

                'Dana berhasil direfund.',

                201

            );

        } catch (Exception $e) {

            return $this->errorResponse(

                $e->getMessage(),

                $e->getCode() ?: 400

            );

        }
    }
}