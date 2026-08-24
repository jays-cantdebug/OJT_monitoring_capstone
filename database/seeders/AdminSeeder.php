<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@normi.edu.ph'],
            [
                'name' => 'NORMI Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'department' => null,
                'email_verified_at' => now(),
            ]
        );
    }
}
