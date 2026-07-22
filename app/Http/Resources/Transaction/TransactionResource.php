<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'amount' => $this->amount,

            'type' => $this->type,

            'status' => $this->status,

            'description' => $this->description,

            'transaction_at' => $this->transaction_at,

            'user' => [

                'id' => $this->user?->id,

                'name' => $this->user?->name,

            ],

            'job' => [

                'id' => $this->job?->id,

                'title' => $this->job?->title,

            ],

            'escrow' => [

                'id' => $this->escrow?->id,

                'status' => $this->escrow?->status,

            ],

        ];
    }
}