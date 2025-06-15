<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call your seeders here
        $this->call([
            UserSeeder::class,
            SubscriptionSeeder::class,
            // Add other seeders as needed
        ]);
    }
}
