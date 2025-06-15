<?php

namespace Database\Seeders;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if it doesn't exist
        $admin = User::firstOrCreate(
            ['email' => 'admin@resumeranker.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@resumeranker.com',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // Create regular users with subscriptions
        $users = User::factory()->count(5)->create([
            'role' => 'user',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Create active subscriptions for each user
        foreach ($users as $user) {
            // Create an active subscription
            Subscription::factory()->create([
                'user_id' => $user->id,
                'plan_id' => 'basic',
                'status' => 'active',
                'starts_at' => Carbon::now()->subDays(rand(1, 30)),
                'expires_at' => Carbon::now()->addMonths(rand(1, 12)),
            ]);

            // Create a cancelled subscription for some users
            if (rand(0, 1)) {
                Subscription::factory()->create([
                    'user_id' => $user->id,
                    'plan_id' => 'pro',
                    'status' => 'cancelled',
                    'starts_at' => Carbon::now()->subMonths(6),
                    'expires_at' => Carbon::now()->subMonths(3),
                    'cancelled_at' => Carbon::now()->subMonths(4),
                ]);
            }

            // Create an expired subscription for some users
            if (rand(0, 1)) {
                Subscription::factory()->create([
                    'user_id' => $user->id,
                    'plan_id' => 'business',
                    'status' => 'expired',
                    'starts_at' => Carbon::now()->subYears(1),
                    'expires_at' => Carbon::now()->subMonths(1),
                ]);
            }
        }
    }
}
