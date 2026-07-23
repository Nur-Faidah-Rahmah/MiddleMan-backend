<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VerificationDocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'document_name' => $this->document_name,

            'document_type' => $this->document_type,

            'status' => $this->status,

            'review_note' => $this->review_note,

            'mime_type' => $this->mime_type,

            'file_size' => $this->file_size,

            'verified_at' => $this->verified_at,

            'document_url' => $this->document_path
                ? asset('storage/'.$this->document_path)
                : null,

        ];
    }
}