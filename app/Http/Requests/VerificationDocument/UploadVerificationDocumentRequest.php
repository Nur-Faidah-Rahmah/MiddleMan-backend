<?php

namespace App\Http\Requests\VerificationDocument;

use Illuminate\Foundation\Http\FormRequest;

class UploadVerificationDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [

            'document_type' => [
                'required',
                'in:ktp,sim,passport,certificate,portfolio,identity,other'
            ],

            'document' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png,zip,rar',
                'max:5120'
            ],

        ];
    }
}