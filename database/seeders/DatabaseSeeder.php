<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Subscriptions
        $freePlan = \App\Models\Subscription::firstOrCreate(
            ['name' => 'Free'],
            [
                'price' => 0.00,
                'duration' => 30,
                'features' => ['Up to 10 tasks', 'Basic Task Management', 'Standard Support'],
            ]
        );

        $proPlan = \App\Models\Subscription::firstOrCreate(
            ['name' => 'Pro'],
            [
                'price' => 9.99,
                'duration' => 30,
                'features' => ['Unlimited tasks', 'Priority Support', 'Advanced Analytics', 'Categories and Nesting'],
            ]
        );

        // 2. Assign Free Subscription to all existing users without one
        User::whereNull('subscription_id')->update([
            'subscription_id' => $freePlan->id,
        ]);

        // 3. Create a default test user if none exists
        if (User::count() === 0) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'subscription_id' => $freePlan->id,
            ]);
        }
    }
}
