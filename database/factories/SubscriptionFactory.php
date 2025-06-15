<?php

namespace Database\Factories;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subscription::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $plans = ['basic', 'pro', 'business'];
        $statuses = ['active', 'cancelled', 'expired', 'paused'];
        $paymentMethods = ['credit_card', 'paypal', 'bank_transfer'];
        $currencies = ['USD', 'EUR', 'GBP'];

        $startsAt = Carbon::now()->subDays(fake()->numberBetween(1, 180));
        $expiresAt = Carbon::parse($startsAt)->addMonths(fake()->numberBetween(1, 12));

        return [
            'user_id' => User::factory(),
            'plan_id' => fake()->randomElement($plans),
            'status' => fake()->randomElement($statuses),
            'starts_at' => $startsAt,
            'expires_at' => $expiresAt,
            'cancelled_at' => null,
            'payment_method' => fake()->randomElement($paymentMethods),
            'payment_id' => 'payment_' . fake()->uuid(),
            'amount' => fake()->randomFloat(2, 9.99, 499.99),
            'currency' => fake()->randomElement($currencies),
        ];
    }

    /**
     * Indicate that the subscription is active.
     *
     * @return static
     */
    public function active(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'active',
                'cancelled_at' => null,
                'expires_at' => Carbon::now()->addMonths(fake()->numberBetween(1, 12)),
            ];
        });
    }

    /**
     * Indicate that the subscription is cancelled.
     *
     * @return static
     */
    public function cancelled(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'cancelled',
                'cancelled_at' => Carbon::now()->subDays(fake()->numberBetween(1, 30)),
            ];
        });
    }

    /**
     * Indicate that the subscription is expired.
     *
     * @return static
     */
    public function expired(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'expired',
                'expires_at' => Carbon::now()->subDays(fake()->numberBetween(1, 30)),
            ];
        });
    }

    /**
     * Indicate that the subscription belongs to a specific user.
     *
     * @param User $user
     * @return static
     */
    public function forUser(User $user): static
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id,
            ];
        });
    }
}
