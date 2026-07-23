<?php

namespace App\Http\Resources\Application;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ApplicationCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [

            'data' => ApplicationResource::collection(
                $this->collection
            )

        ];
    }
}