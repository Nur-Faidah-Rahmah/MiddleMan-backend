<?php

namespace App\Http\Resources\Job;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id'=>$this->id,

            'title'=>$this->title,

            'description'=>$this->description,

            'budget'=>$this->budget,

            'deadline' => optional($this->deadline)
                ->format('Y-m-d H:i:s'),

            'status'=>$this->status,

            'category'=>[
                'id'=>$this->category?->id,
                'name'=>$this->category?->name,
            ],

            'requester' => UserResource::make(
                $this->whenLoaded('requester')
            ),

            'selected_worker' => UserResource::make(
                $this->whenLoaded('selectedWorker')
            ),

            'created_at'=>optional($this->created_at)
                ->format('Y-m-d H:i:s'),

            'updated_at'=>optional($this->updated_at)
                ->format('Y-m-d H:i:s'),

        ];
    }
}