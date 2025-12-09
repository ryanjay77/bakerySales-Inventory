<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin User
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@bakery.com',
            'role' => 'admin',
            'password' => bcrypt('password'), // The password is "password"
        ]);
    }
}
