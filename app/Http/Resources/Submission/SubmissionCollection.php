<?php

namespace App\Http\Resources\Submission;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SubmissionCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [

            'data' => SubmissionResource::collection(
                $this->collection
            )

        ];
    }
}