<?php

namespace App\Http\Requests;
namespace App\Http\Requests\Category;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Otorisasi sudah ditangani oleh middleware role
    }

    public function rules(): array
    {
        // Jika sedang update, abaikan pengecekan unik untuk ID kategori yang sama
        $categoryId = $this->route('category') ? $this->route('category')->id : null;

        return [
            'name' => 'required|string|max:255|unique:categories,name,' . $categoryId,
            'description' => 'nullable|string|max:500',
        ];
    }
}