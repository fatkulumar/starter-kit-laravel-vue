<?php

namespace Database\Factories;

use App\Models\Tryout;
use App\Models\User;
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
        return [
            'tryout_id' => Tryout::factory(),
            'title' => $this->faker->sentence(3),
            'amount_question' => $this->faker->numberBetween(5, 100),
            'amount_minutes' => $this->faker->numberBetween(5, 100),
        ];
    }
}
