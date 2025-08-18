<?php

namespace Database\Factories;

use App\Models\Tryout;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StartTryout>
 */
class StartTryoutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = fake()->optional()->dateTimeBetween('-1 week', 'now');
        $finish = $start ? fake()->optional()->dateTimeBetween($start, '+2 hours') : null;
        return [
            'tryout_id' => Tryout::factory(),
            'user_id' => User::factory(),
            'start_at'  => $start,
            'finish_at' => $finish,
        ];
    }
}
