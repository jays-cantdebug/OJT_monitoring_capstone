<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DeanSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'dean@normi.edu.ph'],
            [
                'name' => 'NORMI Dean',
                'password' => Hash::make('password'),
                'role' => 'dean',
                'email_verified_at' => now(),
            ]
        );
    }
}
