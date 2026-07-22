<?php

namespace App\Http\Resources\Submission;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubmissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'note' => $this->note,

            'attachment' => $this->attachment_path,

            /* jika nanti frontend perlu URL file yang bisa langsung dibuka, ubah field attachment.*/
            // 'attachment' => $this->attachment_path
            //     ? asset('storage/' . $this->attachment_path)
            //     : null,

            'attachment_type' => $this->attachment_type,

            'status' => $this->status,

            'worker' => UserResource::make(
                $this->whenLoaded('worker')
            ),

            'submitted_at' => optional(
                $this->submitted_at
            )->format('Y-m-d H:i:s'),

        ];
    }
}