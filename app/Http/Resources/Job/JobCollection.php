<?php

namespace App\Http\Resources\Job;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class JobCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [

            'data'=>JobResource::collection(
                $this->collection
            )

        ];
    }
}