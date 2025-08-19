<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Subject;
use App\Models\Tryout;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subtest>
 */
class SubtestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $order = Order::inRandomOrder()->first();
        return [
            'tryout_id' => $order->tryout_id,
            'subject_id' => Subject::inRandomOrder()->first()->id,
            'title' => $this->faker->sentence(3),
            'amount_question' => $this->faker->numberBetween(5, 100),
            'amount_minutes' => $this->faker->numberBetween(5, 100),
        ];
    }
}
