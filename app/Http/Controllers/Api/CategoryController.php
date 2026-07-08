<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\Category\StoreCategoryRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // 1. READ ALL (Semua User)
    public function index(): JsonResponse
    {
        $categories = Category::all();
        return $this->successResponse($categories, 'Daftar kategori berhasil diambil.', 200);
    }

    // 2. CREATE (Khusus Admin)
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->validated());
        return $this->successResponse($category, 'Kategori baru berhasil ditambahkan.', 201);
    }

    // 3. READ SINGLE (Semua User)
    public function show(Category $category): JsonResponse
    {
        return $this->successResponse($category, 'Detail kategori ditemukan.', 200);
    }

    // 4. UPDATE (Khusus Admin)
    public function update(StoreCategoryRequest $request, Category $category): JsonResponse
    {
        $category->update($request->validated());
        return $this->successResponse($category, 'Kategori berhasil diperbarui.', 200);
    }

    // 5. DELETE (Khusus Admin)
    public function destroy(Category $category): JsonResponse
    {
        // Antisipasi jika kategori masih dipakai oleh data tugas (jobs)
        if ($category->jobs()->count() > 0) {
            // Menggunakan Trait errorResponse
            return $this->errorResponse('Kategori tidak bisa dihapus karena masih digunakan oleh beberapa tugas.', 422);
        }

        $category->delete();
        return $this->successResponse(null, 'Kategori berhasil dihapus.', 200);

    }
}