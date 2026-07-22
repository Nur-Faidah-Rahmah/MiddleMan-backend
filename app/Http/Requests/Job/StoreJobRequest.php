<?php

namespace App\Http\Requests\Job;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Pastikan hanya Customer (role_id = 2) yang bisa membuat Job
        return auth()->check() && auth()->user()->role_id === 2;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'budget'      => 'required|numeric|min:1000',
            // Pastikan deadline tidak boleh di masa lalu (minimal hari ini/besok)
            'deadline'    => 'required|date|after:today', 
        ];
    }
}