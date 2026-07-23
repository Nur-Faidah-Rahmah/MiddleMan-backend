<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;

class DemoAccountsSeeder extends Seeder
{
    public function run()
    {
        // Admin is already admin@example.com, let's update password or just ensure it exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@sidequest.com'],
            ['name' => 'Admin Demo', 'role_id' => 1, 'password' => Hash::make('password')]
        );
        UserProfile::firstOrCreate(['user_id' => $admin->id]);

        $client = User::firstOrCreate(
            ['email' => 'client@sidequest.com'],
            ['name' => 'Client Demo', 'role_id' => 2, 'password' => Hash::make('password')]
        );
        UserProfile::firstOrCreate(['user_id' => $client->id]);

        $worker = User::firstOrCreate(
            ['email' => 'worker@sidequest.com'],
            ['name' => 'Worker Demo', 'role_id' => 3, 'password' => Hash::make('password')]
        );
        UserProfile::firstOrCreate(['user_id' => $worker->id]);
    }
}
