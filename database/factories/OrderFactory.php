<?php

namespace Database\Factories;

use App\Models\Tryout;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'user_id' => User::factory(),
            'tryout_id' => Tryout::factory(),
            'order_number' => 'order-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
            'amount' => $this->faker->randomFloat(2, 50000, 300000),
            'status' => $this->faker->randomElement(['pending', 'paid', 'failed', 'cancelled']),
        ];
    }
}
