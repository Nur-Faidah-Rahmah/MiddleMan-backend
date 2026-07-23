<?php

namespace App\Http\Requests\VerificationDocument;

use Illuminate\Foundation\Http\FormRequest;

class ReviewVerificationDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [

            'review_note' => [

                'required',

                'string',

                'max:500'

            ]

        ];
    }
}