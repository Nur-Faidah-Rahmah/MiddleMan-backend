<?php

namespace App\Services;

use App\Models\Transaction;
use App\Services\Base\BaseService;

class TransactionService extends BaseService
{
    /**
     * Riwayat transaksi user login.
     */
    public function history(int $userId)
    {
        $query = Transaction::with([

            'job',

            'escrow',

            'user'

        ]);

        if (auth()->user()->role->name !== 'admin') {

            $query->where(

                'user_id',

                $userId

            );

        }

        return $query

            ->latest()

            ->paginate(15);
    }

    /**
     * Detail transaksi.
     */
    public function show(
        Transaction $transaction
    ): Transaction {

        if (

            auth()->user()->role->name !== 'admin'

            &&

            $transaction->user_id !== auth()->id()

        ) {

            $this->fail(

                'Anda tidak memiliki akses.',

                403

            );

        }

        return $transaction->load([

            'job',

            'escrow',

            'user'

        ]);

    }

}