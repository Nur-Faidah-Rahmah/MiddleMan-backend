<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class UserProfileSeeder extends Seeder
{
    public function run(): void
    {
        User::all()->each(function($user){

            UserProfile::create([

                'user_id'        => $user->id,

                'phone'          => fake()->phoneNumber(),

                'address'        => fake()->address(),

                'bio'            => fake()->paragraph(),

                'skills'         => implode(', ', fake()->words(4)),

                'wallet_balance' => $user->role_id === 1 ? 10000000 : 5000000,

                'rating'         => 5.00,

                'level'          => 1,

                'exp'            => 0,

            ]);

        });
    }
}