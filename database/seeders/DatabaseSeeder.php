<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        \App\Models\Role::insert([

            ['id'=>1,'name'=>'admin'],

            ['id'=>2,'name'=>'requester'],

            ['id'=>3,'name'=>'worker'],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        $this->call(CategorySeeder::class);

        /*
        |--------------------------------------------------------------------------
        | Admin
        |--------------------------------------------------------------------------
        */

        \App\Models\User::create([

            'role_id'=>1,

            'name'=>'Administrator',

            'email'=>'admin@example.com',

            'password'=>bcrypt('password'),

        ]);

        /*
        |--------------------------------------------------------------------------
        | Requester
        |--------------------------------------------------------------------------
        */

        \App\Models\User::factory(10)
            ->create([
                'role_id'=>2
            ]);

        /*
        |--------------------------------------------------------------------------
        | Worker
        |--------------------------------------------------------------------------
        */

        \App\Models\User::factory(20)
            ->create([
                'role_id'=>3
            ]);

        $this->call(UserProfileSeeder::class);
        $this->call(VerificationSeeder::class);

    }
}