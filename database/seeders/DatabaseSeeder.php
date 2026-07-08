<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Panggil kedua seeder yang baru kita buat
        $this->call([
            RoleSeeder::class,
            CategorySeeder::class,
        ]);
    }
}