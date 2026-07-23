<?php

namespace App\Http\Requests\Submission;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [

            'note'=>[
                'nullable',
                'string'
            ],

            'attachment'=>[
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png,zip,rar',
                'max:10240'
            ]

        ];
    }
}