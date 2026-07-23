<?php

namespace App\Http\Requests\Job;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateJobStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'category_id' => [
                'sometimes',
                'exists:categories,id',
            ],

            'title' => [
                'sometimes',
                'string',
                'max:255',
            ],

            'description' => [
                'sometimes',
                'string',
            ],

            'budget' => [
                'sometimes',
                'numeric',
                'min:1000',
            ],

            'deadline' => [
                'sometimes',
                'date',
                'after:today',
            ],

        ];
    }
}
