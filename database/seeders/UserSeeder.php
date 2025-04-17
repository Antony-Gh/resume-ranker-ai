<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Use your User model
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create 10 random users
        User::factory(10)->create();

        // Create 5 admins
        User::factory(5)->admin()->create();

        // Create 5 inactive users
        User::factory(5)->inactive()->create();

        // Example using DB facade to insert data directly
        DB::table('users')->insert([
            'name' => 'Abdelrahman Hytham',
            'email' => 'abdelrahmanhytham22@gmail.com',
            'email_verified_at' => now(), // Randomly set email as verified or not
            'password' => Hash::make('abdohytham2025'),
            'last_login_at' => now(), // Random date within this year for last login
            'last_login_ip' => fake()->ipv4(), // Random IP for last login
            'remember_token' => Str::random(60),
        ]);
    }
}
