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

                'user_id'=>$user->id,

                'phone'=>fake()->phoneNumber(),

                'gender'=>fake()->randomElement([
                    'male',
                    'female'
                ]),

                'city'=>fake()->city(),

                'province'=>fake()->state(),

                'bio'=>fake()->paragraph(),

                'skills'=>implode(', ', fake()->words(4))

            ]);

        });
    }
}