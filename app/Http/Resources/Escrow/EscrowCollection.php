<?php

namespace App\Http\Resources\Escrow;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EscrowCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [

            'data' => EscrowResource::collection(
                $this->collection
            )

        ];
    }
}