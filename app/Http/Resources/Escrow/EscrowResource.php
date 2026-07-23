<?php

namespace App\Http\Resources\Escrow;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EscrowResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'amount' => $this->amount,

            'status' => $this->status,

            'requester' => UserResource::make(
                $this->whenLoaded('requester')
            ),

            'worker' => UserResource::make(
                $this->whenLoaded('worker')
            ),

            'funded_at' => optional(
                $this->funded_at
            )->format('Y-m-d H:i:s'),

            'released_at' => optional(
                $this->released_at
            )->format('Y-m-d H:i:s'),

            'refunded_at' => optional(
                $this->refunded_at
            )->format('Y-m-d H:i:s'),

        ];
    }
}