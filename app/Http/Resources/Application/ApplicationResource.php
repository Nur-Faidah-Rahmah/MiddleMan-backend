<?php

namespace App\Http\Resources\Application;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\UserResource;

class ApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id'=>$this->id,

            'status'=>$this->status,

            'worker' => UserResource::make(
                $this->whenLoaded('worker')
            ),

            'created_at' => optional($this->created_at)
                ->format('Y-m-d H:i:s')

        ];
    }
}