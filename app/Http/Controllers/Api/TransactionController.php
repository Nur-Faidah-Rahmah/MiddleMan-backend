<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use App\Services\TransactionService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Transaction\TransactionResource;
use App\Http\Resources\Transaction\TransactionCollection;
use Illuminate\Http\JsonResponse;
use Exception;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionService $transactionService
    ){
    }

    /**
     * Riwayat transaksi.
     */
    public function history(): JsonResponse
    {
        try {

            return $this->successResponse(

                new TransactionCollection(
                    $this->transactionService->history(
                        auth()->id()
                    )
                ),

                'Riwayat transaksi berhasil diambil.'

            );

        } catch (Exception $e) {

            return $this->errorResponse(

                $e->getMessage(),

                $e->getCode() ?: 400

            );

        }
    }

    /**
     * Detail transaksi.
     */
    public function show(
        Transaction $transaction
    ): JsonResponse
    {
        try {

            return $this->successResponse(

                new TransactionResource(

        $this->transactionService->show(

            $transaction

        )

    ),

                'Detail transaksi.'

            );

        } catch (Exception $e) {

            return $this->errorResponse(

                $e->getMessage(),

                $e->getCode() ?: 400

            );

        }
    }
}