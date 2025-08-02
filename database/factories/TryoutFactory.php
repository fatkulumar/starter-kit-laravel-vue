<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tryout>
 */
class TryoutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('+2 weeks', '+3 weeks');
        $end = (clone $start)->modify('+2 hours');
        $duration = 120;

        return [
            'id' => (string) Str::uuid(),
            'event_id' => Event::inRandomOrder()->first()?->id, // ambil salah satu event
            'title' => 'Tryout - ' . $this->faker->word(),
            'description' => $this->faker->optional()->paragraph(),

            'start_time' => $start,
            'end_time' => $end,
            'duration' => $duration,

            'is_active' => $this->faker->boolean(90), // 90% aktif
            'is_locked' => $this->faker->boolean(10), // 10% terkunci

            'thumbnail' => $this->faker->optional()->imageUrl(640, 360, 'tryout', true, 'Cover'),
            'guide_link' => $this->faker->optional()->url(),
            'price' => $this->faker->numberBetween(10000, 200000)
        ];
    }
}
