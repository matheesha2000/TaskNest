<?php

namespace Database\Factories;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition(): array
    {
        return [
            'name' => 'Pro',
            'price' => 9.99,
            'duration' => 30,
            'features' => ['Unlimited tasks', 'Priority Support', 'Advanced Analytics'],
        ];
    }
}
