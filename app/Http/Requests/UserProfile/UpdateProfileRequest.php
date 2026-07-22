<?php

namespace App\Http\Requests\UserProfile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'string',
                'max:255'
            ],

            'phone'=>[
                'nullable',
                'regex:/^[0-9+\-\s]+$/',
                'string',
                'max:20'
            ],

            'gender'=>[
                'nullable',
                'in:male,female'
            ],

            'birth_date'=>[
                'nullable',
                'date'
            ],

            'province'=>[
                'nullable',
                'string'
            ],

            'city'=>[
                'nullable',
                'string'
            ],

            'address'=>[
                'nullable',
                'string'
            ],

            'bio'=>[
                'nullable',
                'string'
            ],

            'skills'=>[
                'nullable',
                'string'
            ],

            'avatar'=>[
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048'
            ],

        ];
    }
}