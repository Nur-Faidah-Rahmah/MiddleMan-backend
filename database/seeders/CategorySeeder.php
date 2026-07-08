<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Mentoring & Akademik',
                'description' => 'Bimbingan belajar, tugas kuliah, persiapan ujian, atau sertifikasi.'
            ],
            [
                'name' => 'IT & Coding',
                'description' => 'Pembuatan website, perbaikan bug, atau instalasi perangkat lunak.'
            ],
            [
                'name' => 'Desain & Kreatif',
                'description' => 'Pembuatan logo, editing video, atau desain UI/UX Figma.'
            ],
            [
                'name' => 'Jasa Logistik Ringan',
                'description' => 'Mengantar barang belanjaan, membelikan makanan, atau tugas fisik ringan lainnya.'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}